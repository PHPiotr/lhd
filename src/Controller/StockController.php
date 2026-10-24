<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarPhoto;
use App\Form\CarDeleteType;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Utils\Thumbnail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class StockController extends AbstractController
{
    private $carPhotosDirectory;
    private $perPage = 6;

    private $specification = [
        'make' => 'make', 'model' => 'model', 'variant' => 'variant', 'firstRegistration' => 'first registration', 'mileage' => 'mileage', 'mot' => 'mot', 'doors' => 'doors', 'seats' => 'seats',
        'colour' => 'colour', 'body' => 'body', 'fuelType' => 'fuel type', 'transmission' => 'transmission', 'engineSize' => 'engine size', 'bhp' => 'bhp', 'mpgUrban' => 'mpg urban',
        'mpgExtraUrban' => 'mpg extra urban', 'mpgCombined' => 'mpg combined', 'length' => 'length', 'width' => 'width', 'height' => 'height', 'countryOfOrigin' => 'country of origin',
    ];

    public function __construct(
        private CarRepository $repo,
        private EntityManagerInterface $em,
        private SluggerInterface $slugger,
        private Thumbnail $thumbnail,
        #[Autowire('%kernel.project_dir%/public/bundles/app/img/stock/')] string $carPhotosDirectory,
    ) {
        $this->carPhotosDirectory = $carPhotosDirectory;
    }

    protected function getListData($page)
    {
        $cars = $this->repo->findBy([], ['isComingSoon' => 'DESC', 'isSold' => 'ASC', 'id' => 'DESC'], $this->perPage, $this->perPage * ($page - 1));
        $countAll = $this->repo->getCountAll();
        $pagesCount = $countAll > 0 ? ceil($countAll / $this->perPage) : 1;

        if ($page > $pagesCount) {
            $page = $pagesCount;
        }

        return ['cars' => $cars, 'currentPage' => $page, 'pagesCount' => $pagesCount];
    }

    #[Route('/stock/{page}', name: 'stock', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    #[Template('Stock/index.html.twig')]
    public function indexAction(int $page)
    {
        return $this->getListData($page);
    }

    #[Route('/stock/{slug}', name: 'stock_view')]
    #[Template('Stock/view.html.twig')]
    public function viewAction($slug)
    {
        $car = $this->repo->findOneBy(['slug' => $slug]);
        if (null === $car) {
            throw $this->createNotFoundException('This car is not in stock.');
        }

        return ['car' => $car, 'specification' => $this->specification];
    }

    #[Route('/admin/stock/{page}', name: 'admin_stock_list', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    #[Template('Stock/adminList.html.twig')]
    public function adminListAction(int $page)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);

        return $this->getListData($page);
    }

    #[Route('/admin/stock/edit/{id}', name: 'admin_stock_edit', requirements: ['id' => '\d+'])]
    #[Template('Stock/adminEdit.html.twig')]
    public function adminEditAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $repo = $this->repo;
        $car = $repo->find($id);
        if (null === $car) {
            throw $this->createNotFoundException('This car is not in stock.');
        }

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return ['car' => $car, 'form' => $form->createView()];
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
            return ['car' => $car, 'form' => $form->createView()];
        }

        try {
            if (true !== $this->addCarPhotosIfNeeded($request, $car, new Filesystem())) {
                throw new \Exception('File upload failed!');
            }
            $title = $car->getTitle();
            $slug = $this->urlize($title);
            $sameSlugCar = $repo->findOneBy(['slug' => $slug]);

            if (null === $sameSlugCar) {
                $car->setSlug($slug);
            } else if ($sameSlugCar->getId() !== $car->getId()) {
                $availableSlug = $this->getAvailableSlugForTitle($title);
                $car->setSlug($availableSlug);
            }

            $this->em->flush();
            $this->addFlash('success', 'Your car was edited!');
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
            return ['car' => $car, 'form' => $form->createView()];
        }

        return $this->redirectToRoute('admin_stock_edit', ['id' => $car->getId()]);
    }

    #[Route('/admin/stock/delete/{id}', name: 'admin_stock_delete', requirements: ['id' => '\d+'])]
    #[Template('Stock/adminDelete.html.twig')]
    public function adminDeleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $car = $this->repo->find($id);
        if (null === $car) {
            throw $this->createNotFoundException('This car is not in stock.');
        }

        $form = $this->createForm(CarDeleteType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return ['car' => $car, 'form' => $form->createView()];
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
            return ['car' => $car, 'form' => $form->createView()];
        }

        $this->deleteCarPhotosIfNeeded($car);

        $this->em->remove($car);
        $this->em->flush();
        $this->addFlash('success', 'Your car was removed from stock!');

        return $this->redirectToRoute('admin_stock_list');
    }

    #[Route('/admin/stock/add', name: 'admin_stock_add')]
    #[Template('Stock/adminAdd.html.twig')]
    public function adminAddAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);

        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return ['form' => $form->createView()];
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
            return ['form' => $form->createView()];
        }

        $title = $car->getTitle();
        $slug = $this->urlize($title);
        if (null === $this->repo->findOneBy(['slug' => $slug])) {
            $car->setSlug($slug);
        } else {
            $availableSlug = $this->getAvailableSlugForTitle($title);
            $car->setSlug($availableSlug);
        }

        $this->em->persist($car);

        try {
            if (true !== $this->addCarPhotosIfNeeded($request, $car, new Filesystem())) {
                throw new \Exception('File upload failed!');
            }
        } catch (\Exception $e) {
            return ['form' => $form->createView()];
        }
        $this->em->flush();
        $this->addFlash('success', 'Your car was added to stock!');

        return $this->redirectToRoute('admin_stock_list');
    }

    #[Route('/admin/stock/photos/{id}', name: 'admin_stock_car_photos', requirements: ['id' => '\d+'])]
    #[Template('Stock/adminListPhotos.html.twig')]
    public function adminListPhotos(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $car = $this->repo->find($id);
        if (null === $car) {
            throw $this->createNotFoundException('This car is not in stock.');
        }

        $form = $this->createFormBuilder($car)->getForm();

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return ['car' => $car, 'form' => $form->createView()];
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
            return ['car' => $car, 'form' => $form->createView()];
        }

        $selectedPhotos = $request->request->all('photo');
        if (empty($selectedPhotos)) {
            $this->addFlash('warning', 'No photos were marked for deletion!');
            return ['car' => $car, 'form' => $form->createView()];
        }

        $this->deleteCarPhotosIfNeeded($car, $selectedPhotos);
        $this->addFlash('success', 'Photos of car were removed!');

        return $this->redirectToRoute('admin_stock_car_photos', ['id' => $car->getId()]);
    }

    protected function addCarPhotosIfNeeded(Request $request, Car $car, Filesystem $fs)
    {
        $files = $request->files->all();

        if (!isset($files['car']['carPhotos'])) {
            return true;
        }
        $carPhotos = $files['car']['carPhotos'];
        if ($carPhotos[0] === null) {
            return true;
        }
        $carPhotosDirectory = $this->carPhotosDirectory;

        $em = $this->em;
        $imgAdded = [];
        $error = null;
        $thumbnail = $this->thumbnail;

        foreach ($carPhotos as $uploadedFile) {

            $carPhoto = new CarPhoto();
            $clientOriginalName = $uploadedFile->getClientOriginalName();
            $filename = pathinfo($clientOriginalName, PATHINFO_FILENAME);
            $extension = pathinfo($clientOriginalName, PATHINFO_EXTENSION);
            $sluggedFilename = sprintf('%s-%s', $this->urlize($filename), md5(time() . rand(0, 1000)));
            $sluggedName = strtolower(sprintf('%s.%s', $sluggedFilename, $extension));

            $carPhoto->setCar($car);
            $carPhoto->setName($sluggedName);
            $em->persist($carPhoto);

            $original = $carPhotosDirectory . $sluggedName;
            $thumbnailCarousel = $carPhotosDirectory . 'thumbnails/carousel/' . $sluggedName;
            $thumbnailBig = $carPhotosDirectory . 'thumbnails/big/' . $sluggedName;
            $thumbnailMedium = $carPhotosDirectory . 'thumbnails/medium/' . $sluggedName;

            try {
                $uploadedFile->move($carPhotosDirectory, $sluggedName);
                $imgAdded[] = $original;
                $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailCarousel);
                $imgAdded[] = $thumbnailCarousel;
                $thumbnail->create($thumbnailCarousel, 'carousel_thumb_out');
                $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailBig);
                $imgAdded[] = $thumbnailBig;
                $thumbnail->create($thumbnailBig, 'big_thumb_out');
                $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailMedium);
                $imgAdded[] = $thumbnailMedium;
                $thumbnail->create($thumbnailMedium, 'medium_thumb_out');
            } catch (\Exception $e) {
                $error = $e->getMessage();
                break;
            }
        }
        if (!$error) {
            $this->addFlash('success', 'Photos added to car!');
            $em->flush();

            return true;
        }

        $this->addFlash('danger', $error);
        $fs->remove($imgAdded);

        return false;
    }

    protected function deleteCarPhotosIfNeeded(Car $car, array $selectedPhotos = [])
    {
        $photos = $car->getCarPhotos();

        if (!$photos) {
            return;
        }

        $fs = new Filesystem();
        $carPhotosDirectory = $this->carPhotosDirectory;
        $imgRemove = [];

        $em = $this->em;
        foreach ($photos as $carPhoto) {
            if (!empty($selectedPhotos)) {
                if (!in_array($carPhoto->getId(), $selectedPhotos)) {
                    continue;
                }
            }
            $name = $carPhoto->getName();
            $em->remove($carPhoto);
            $original = $carPhotosDirectory . $name;
            $thumbnailCarousel = $carPhotosDirectory . 'thumbnails/carousel/' . $name;
            $thumbnailBig = $carPhotosDirectory . 'thumbnails/big/' . $name;
            $thumbnailMedium = $carPhotosDirectory . 'thumbnails/medium/' . $name;
            // was `+=`, which kept only the first photo's paths and left the other files on disk
            array_push($imgRemove, $original, $thumbnailCarousel, $thumbnailBig, $thumbnailMedium);
        }

        try {
            $fs->remove($imgRemove);
            $em->flush();
        } catch (IOException $e) {
            $this->addFlash('warning', $e->getMessage());
        }
    }

    protected function getAvailableSlugForTitle($title, $index = 0)
    {
        $suffix = $index > 0 ? sprintf('-%d', $index) : null;
        $slug = $this->urlize($title) . $suffix;
        if (null === $this->repo->findOneBy(['slug' => $slug])) {
            return $slug;
        }
        return $this->getAvailableSlugForTitle($title, $index + 1);
    }

    private function urlize(string $text): string
    {
        return $this->slugger->slug($text)->lower()->toString();
    }
}
