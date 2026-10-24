<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class CentreController extends AbstractController
{
    #[Route('/', name: 'centre')]
    #[Template('Centre/index.html.twig')]
    public function indexAction(CarRepository $repo): array
    {
        $newestCars = $repo->findBy([], ['isComingSoon' => 'DESC', 'isSold' => 'ASC', 'id' => 'DESC'], 3);

        return ['newestCars' => $newestCars];
    }
}
