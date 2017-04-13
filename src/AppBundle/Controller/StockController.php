<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\CarType;
use AppBundle\Entity\Car;
use AppBundle\Entity\CarPhoto;
use Symfony\Component\Filesystem\Filesystem;
use Gedmo\Sluggable\Util as Sluggable;

class StockController extends Controller
{

    private $carPhotosDirectory = 'bundles/app/img/stock/';

    /**
     * @Route("/stock/{page}", name="stock", requirements={"page": "\d+"}, defaults={"page" = 1})
     * @Template()
     */
    public function indexAction($page)
    {
        $cars = $this->getDoctrine()->getRepository('AppBundle:Car')->findAll();

        return ['cars' => $cars];
    }

    /**
     * @Route("/stock/{slug}", name="stock_view")
     * @Template()
     */
    public function viewAction($slug)
    {
        $specification = [
            'make',
            'model',
            'variant',
            'firstRegistration',
            'mileage',
            'mot',
            'doors',
            'seats',
            'colour',
            'body',
            'fuelType',
            'transmission',
            'engineSize',
            'bhp',
            'mpgUrban',
            'mpgExtraUrban',
            'mpgCombined',
            'length',
            'width',
            'height',
            'countryOfOrigin',
        ];
        $car = $this->getDoctrine()->getRepository('AppBundle:Car')->findOneBy(['slug' => $slug]);

        return ['car' => $car, 'specification' => $specification];
    }

    /**
     * @Route("/admin/stock", name="admin_stock_list")
     * @Template()
     */
    public function adminListAction()
    {
        return [];
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

        if ($form->isSubmitted() && $form->isValid()) {

            $car->setSlug(Sluggable\Urlizer::urlize($car->getTitle()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($car);
            $em->flush();

            $this->addCarPhotosIfNeeded($request, $car, new Filesystem());

            return $this->redirectToRoute('admin_stock_list');
        }

        return ['form' => $form->createView()];
    }

    protected function addCarPhotosIfNeeded(Request $request, Car $car, Filesystem $fs)
    {
        $files = $request->files->all();

        if (!isset($files['car']['carPhotos'])) {
            return;
        }
        $carPhotos = $files['car']['carPhotos'];
        if (!$carPhotos) {
            return;
        }
        $carPhotosDirectory = $this->carPhotosDirectory;

        foreach ($carPhotos as $uploadedFile) {
            $carPhoto = new CarPhoto();
            $clientOriginalName = $uploadedFile->getClientOriginalName();
            $filename = pathinfo($clientOriginalName, PATHINFO_FILENAME);
            $extension = pathinfo($clientOriginalName, PATHINFO_EXTENSION);
            $sluggedFilename = Sluggable\Urlizer::urlize($filename);
            $sluggedName = strtolower(sprintf('%s.%s', $sluggedFilename, $extension));
            $uploadedFile->move($carPhotosDirectory, $sluggedName);
            $thumbnailCarousel = $carPhotosDirectory . 'thumbnails/carousel/' . $sluggedName;
            $thumbnailBig = $carPhotosDirectory . 'thumbnails/big/' . $sluggedName;
            $thumbnailMedium = $carPhotosDirectory . 'thumbnails/medium/' . $sluggedName;
            $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailCarousel);
            $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailBig);
            $fs->copy($carPhotosDirectory . $sluggedName, $thumbnailMedium);
            try {
                $this->get('app.utils.thumbnail')->create($thumbnailCarousel, 'carousel_thumb_out');
                $this->get('app.utils.thumbnail')->create($thumbnailBig, 'big_thumb_out');
                $this->get('app.utils.thumbnail')->create($thumbnailMedium, 'medium_thumb_out');
            } catch (Exception $e) {
                $fs->remove([
                    $carPhotosDirectory.$sluggedName,
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

}
