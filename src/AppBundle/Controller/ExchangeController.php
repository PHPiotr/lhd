<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExchangeController extends Controller
{

    /**
     * @Route("/part-exchange", name="exchange")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', null, ['constraints' => [new NotBlank()], 'required' => true, 'attr' => ['heading' => 'About you']])
            ->add('telephone_number', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('email_address', EmailType::class, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('reg_number', null, ['constraints' => [new NotBlank()], 'attr' => ['heading' => 'About your car']])
            ->add('make', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('model', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('mileage', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('mot', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('fuel_type', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('transmission', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('colour', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('service_history', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('general_condition', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('defects', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('vehicle_interested_in', null, ['constraints' => [new NotBlank()], 'required' => true])
            ->add('additional_notes', TextareaType::class)
            ->add('send', SubmitType::class, ['attr' => ['class' => 'btn-danger']])
            ->getForm();

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return ['form' => $form->createView()];
        }

        if (!$form->isValid()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
            return ['form' => $form->createView()];
        }

        $this->addFlash('success', 'Thanks, your message has been sent. We will contact you soon.');
        return $this->redirectToRoute('exchange');
    }

}
