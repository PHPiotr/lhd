<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LocationController extends Controller
{

    /**
     * @Route("/location", name="location")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
