<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CentreController extends Controller
{

    /**
     * @Route("/", name="centre")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
