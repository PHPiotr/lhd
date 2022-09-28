<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LocationController extends Controller
{

    /**
     * @Route("/location", name="location")
     * @Template()
     */
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
