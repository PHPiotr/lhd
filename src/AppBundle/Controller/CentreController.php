<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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
        $newestCars = $this->getDoctrine()->getRepository('AppBundle:Car')->findBy([], ['isComingSoon' => 'DESC', 'isSold' => 'ASC', 'id' => 'DESC'], 3);

        return ['newestCars' => $newestCars];
    }

}
