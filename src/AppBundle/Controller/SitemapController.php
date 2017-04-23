<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SecurityController
 *
 * @package AppBundle\Controller
 */
class SitemapController extends Controller
{

    private $perPage = 6;

    /**
     * @Route("/sitemap.{_format}", name="sitemap", defaults={"_format": "html"}, requirements={"_format": "xml|html"})
     * @Template()
     */
    public function indexAction()
    {
        return $this->getListData();
    }

    protected function getListData()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Car');
        $cars = $repo->findBy([], ['isSold' => 'ASC', 'id' => 'DESC']);
        $countAll = $repo->getCountAll();
        $pagesCount = $countAll > 0 ? ceil($countAll / $this->perPage) : 1;

        return ['cars' => $cars, 'pagesCount' => $pagesCount];
    }
}

