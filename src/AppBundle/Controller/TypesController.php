<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypesController extends Controller
{

    /**
     * @Route("/transport-to-europe", name="types")
     * @Template()
     */
    public function indexAction()
    {
        $types = [
            [
                'title' => 'France - £400',
                'image' => 'panel_van.jpg',
                'description' => '',
            ],
            [
                'title' => 'Spain - £500',
                'image' => 'half_panel.jpg',
                'description' => '',
            ],
            [
                'title' => 'Portugal - £600',
                'image' => 'kombi.jpg',
                'description' => '',
            ],
            [
                'title' => 'Netherlands - £300',
                'image' => 'shuttle.jpg',
                'description' => '',
            ],
            [
                'title' => 'Germany - £250',
                'image' => 'caravelle.jpg',
                'description' => '',
            ],
        ];
        return ['types' => $types];
    }

}
