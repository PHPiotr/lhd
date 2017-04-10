<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypesController extends Controller
{

    /**
     * @Route("/van-mpv-types", name="types")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
