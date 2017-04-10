<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExchangeController extends Controller
{

    /**
     * @Route("/part-exchange", name="exchange")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

}
