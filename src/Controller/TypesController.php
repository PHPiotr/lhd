<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class TypesController extends AbstractController
{
    #[Route('/transport-to-europe', name: 'types')]
    #[Template('Types/index.html.twig')]
    public function indexAction(): array
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
