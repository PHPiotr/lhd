<?php

namespace AppBundle\Controller;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\CarType;
use AppBundle\Form\CarDeleteType;
use AppBundle\Entity\Car;
use AppBundle\Entity\CarPhoto;
use Symfony\Component\Filesystem\Filesystem;
use Gedmo\Sluggable\Util as Sluggable;

class StockController extends Controller
{

    private $carPhotosDirectory = 'bundles/app/img/stock/';

    private $specification = [
        'make' => 'make', 'model' => 'model', 'variant' => 'variant', 'firstRegistration' => 'first registration', 'mileage' => 'mileage', 'mot' => 'mot', 'doors' => 'doors', 'seats' => 'seats',
        'colour' => 'colour', 'body' => 'body', 'fuelType' => 'fuel type', 'transmission' => 'transmission', 'engineSize' => 'engine size', 'bhp' => 'bhp', 'mpgUrban' => 'mpg urban',
        'mpgExtraUrban' => 'mpg extra urban', 'mpgCombined' => 'mpg combined', 'length' => 'length', 'width' => 'width', 'height' => 'height', 'countryOfOrigin' => 'country of origin',
    ];

    /**
     * @Route("/stock/{page}", name="stock", requirements={"page": "\d+"}, defaults={"page" = 1})
     * @Template()
     */
    public function indexAction($page)
    {
        $cars = $this->getDoctrine()->getRepository('AppBundle:Car')->findBy([], ['id' => 'DESC'], 6, 6 * ($page - 1));

        return ['cars' => $cars];
    }

    /**
     * @Route("/stock/{slug}", name="stock_view")
     * @Template()
     */
    public function viewAction($slug)
    {
        $car = $this->getDoctrine()->getRepository('AppBundle:Car')->findOneBy(['slug' => $slug]);

        return ['car' => $car, 'specification' => $this->specification];
    }

    /**
     * @Route("/admin/stock/{page}", name="admin_stock_list", requirements={"page": "\d+"}, defaults={"page" = 1}))
     * @Template()
     */
    public function adminListAction($page)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $cars = $this->getDoctrine()->getRepository('AppBundle:Car')->findBy([], ['id' => 'DESC'], 10, 10 * ($page - 1));

        return ['cars' => $cars];
    }

    /**
     * @Route("/admin/stock/edit/{id}", name="admin_stock_edit", requirements={"id": "\d+"}))
     * @Template()
     */
    public function adminEditAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $car = $this->getDoctrine()->getRepository('AppBundle:Car')->findOneById($id);

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

        $car->setSlug(Sluggable\Urlizer::urlize($car->getTitle()));
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('success', 'Your car was edited!');

        $this->addCarPhotosIfNeeded($request, $car, new Filesystem());
        return $this->redirectToRoute('admin_stock_edit', ['id' => $car->getId()]);
    }

    /**
     * @Route("/admin/stock/delete/{id}", name="admin_stock_delete", requirements={"id": "\d+"}))
     * @Template()
     */
    public function adminDeleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $car = $this->getDoctrine()->getRepository('AppBundle:Car')->findOneById($id);


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

        $em = $this->getDoctrine()->getManager();
        $em->remove($car);
        $em->flush();
        $this->addFlash('success', 'Your car was removed from stock!');

        return $this->redirectToRoute('admin_stock_list');
    }

    /**
     * @Route("/admin/stock/add", name="admin_stock_add")
     * @Template()
     */
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

        $car->setSlug(Sluggable\Urlizer::urlize($car->getTitle()));
        $em = $this->getDoctrine()->getManager();
        $em->persist($car);
        $em->flush();
        $this->addFlash('success', 'Your car was added to stock!');

        $this->addCarPhotosIfNeeded($request, $car, new Filesystem());

        return $this->redirectToRoute('admin_stock_list');
    }

    /**
     * @Route("/admin/stock/photos/{id}", name="admin_stock_car_photos", requirements={"id": "\d+"})
     * @Template()
     */
    public function adminListPhotos(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null);
        $car = $this->getDoctrine()->getRepository('AppBundle:Car')->findOneById($id);

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

        if (empty($request->request->get('photo'))) {
            $this->addFlash('warning', 'No photos were marked for deletion!');
            return ['car' => $car, 'form' => $form->createView()];
        }

        $this->deleteCarPhotosIfNeeded($car, $request->request->get('photo'));
        $this->addFlash('success', 'Photos of car were removed!');

        return $this->redirectToRoute('admin_stock_car_photos', ['id' => $car->getId(), 'request' => $request]);
    }

    protected function addCarPhotosIfNeeded(Request $request, Car $car, Filesystem $fs)
    {
        $files = $request->files->all();

        if (!isset($files['car']['carPhotos'])) {
            return;
        }
        $carPhotos = $files['car']['carPhotos'];
        if ($carPhotos[0] === null) {
            return;
        }
        $carPhotosDirectory = $this->carPhotosDirectory;

        foreach ($carPhotos as $uploadedFile) {
            $carPhoto = new CarPhoto();
            $clientOriginalName = $uploadedFile->getClientOriginalName();
            $filename = pathinfo($clientOriginalName, PATHINFO_FILENAME);
            $extension = pathinfo($clientOriginalName, PATHINFO_EXTENSION);
            $sluggedFilename = sprintf('%s-%s', Sluggable\Urlizer::urlize($filename), md5(time().rand(0, 1000)));
            $sluggedName = strtolower(sprintf('%s.%s', $sluggedFilename, $extension));
            $uploadedFile->move($carPhotosDirectory, $sluggedName);
            $thumbnailCarousel = $carPhotosDirectory . 'thumbnails/carousel/' . $sluggedName;
            $thumbnailBig = $carPhotosDirectory . 'thumbnails/big/' . $sluggedName;
            $thumbnailMedium = $carPhotosDirectory . 'thumbnails/medium/' . $sluggedName;
            $thumbnail = $this->get('app.utils.thumbnail');
            try {
                $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailCarousel);
                $thumbnail->create($thumbnailCarousel, 'carousel_thumb_out');
                $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailBig);
                $thumbnail->create($thumbnailBig, 'big_thumb_out');
                $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailMedium);
                $thumbnail->create($thumbnailMedium, 'medium_thumb_out');
            } catch (Exception $e) {
                $fs->remove([
                    $carPhotosDirectory . $sluggedName,
                    $thumbnailCarousel,
                    $thumbnailBig,
                    $thumbnailMedium,
                ]);
                throw $e;
            }
            $em = $this->getDoctrine()->getManager();
            $carPhoto->setCar($car);
            $carPhoto->setName($sluggedName);
            $em->persist($carPhoto);
            $em->flush();
        }
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

        $em = $this->getDoctrine()->getManager();
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
            $imgRemove += [$original, $thumbnailCarousel, $thumbnailBig, $thumbnailMedium];
        }
        $em->flush();
        try {
            $fs->remove($imgRemove);
        } catch (IOException $e) {
            $this->addFlash('warning', $e->getMessage());
        }
    }

}
