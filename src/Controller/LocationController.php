<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocationController extends AbstractController
{
    #[Route('/location', name: 'location')]
    #[Template('Location/index.html.twig')]
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('origin', null, ['label' => 'Enter your location', 'constraints' => [new NotBlank()], 'required' => true])
            ->add('find_route', SubmitType::class, ['attr' => ['class' => 'btn-danger']])
            ->getForm();

        $data = [
            'title' => 'LHD Van Centre',
            'postcode' => 'EN11 0DU',
            'form' => $form->createView(),
            'apiKey' => $this->getParameter('api_key'),
        ];

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return $data;
        }

        return $this->redirectToRoute('location');
    }
}
