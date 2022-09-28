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
                'title' => 'France - £500',
                'image' => 'panel_van.jpg',
                'description' => '',
            ],
            [
                'title' => 'Spain - £600',
                'image' => 'half_panel.jpg',
                'description' => '',
            ],
            [
                'title' => 'Portugal - £700',
                'image' => 'kombi.jpg',
                'description' => '',
            ],
            [
                'title' => 'Netherlands - £400',
                'image' => 'shuttle.jpg',
                'description' => '',
            ],
            [
                'title' => 'Germany - £350',
                'image' => 'caravelle.jpg',
                'description' => '',
            ],
        ];
        return ['types' => $types];
    }

}
