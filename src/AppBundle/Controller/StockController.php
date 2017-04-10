<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StockController extends Controller
{

    /**
     * @Route("/stock", name="stock")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
