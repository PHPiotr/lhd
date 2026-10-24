<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SitemapController extends AbstractController
{
    private $perPage = 6;

    #[Route('/sitemap.{_format}', name: 'sitemap', requirements: ['_format' => 'xml|html'], defaults: ['_format' => 'html'])]
    public function indexAction(string $_format, CarRepository $repo): Response
    {
        $cars = $repo->findBy([], ['isSold' => 'ASC', 'id' => 'DESC']);
        $countAll = $repo->getCountAll();
        $pagesCount = $countAll > 0 ? ceil($countAll / $this->perPage) : 1;

        return $this->render(sprintf('Sitemap/index.%s.twig', $_format), ['cars' => $cars, 'pagesCount' => $pagesCount]);
    }
}
