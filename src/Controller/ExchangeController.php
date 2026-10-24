<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExchangeController extends AbstractController
{
    #[Route('/part-exchange', name: 'exchange')]
    #[Template('Exchange/index.html.twig')]
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', null, ['label' => 'Name *', 'constraints' => [new NotBlank()], 'required' => true])
            ->add('telephone_number', null, ['label' => 'Telephone number *', 'constraints' => [new NotBlank()], 'required' => true])
            ->add('email_address', EmailType::class, ['label' => 'Email address *', 'constraints' => [new NotBlank(), new Email()], 'required' => true])
            ->add('reg_number', null, ['label' => 'Reg number *', 'constraints' => [new NotBlank()]])
            ->add('make', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('model', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('mileage', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('mot', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('fuel_type', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('transmission', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('colour', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('service_history', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('general_condition', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('defects', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('vehicle_interested_in', null, ['constraints' => [new Length(max: 255)], 'required' => true])
            ->add('additional_notes', TextareaType::class, ['attr' => ['maxlength' => 1000], 'constraints' => [new Length(max: 1000)]])
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

        $data = $form->getData();
        $message = $this->renderView('Exchange/email.html.twig', [
            'data' => $data,
        ]);

        $to = $this->getParameter('mailer_to');
        $subject = 'LHD Part Exchange';
        $emailFrom = $data['email_address'];

        $headers[] = sprintf('From: %s', $emailFrom);
        $headers[] = sprintf('Reply-To: %s', $emailFrom);
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $sent = mail($to, $subject, $message, implode("\r\n", $headers), '-f ' . $emailFrom);

        if (!$sent) {
            $this->addFlash('danger', 'Sorry, something went wrong and your message has not been sent.');

            return ['form' => $form->createView()];
        }

        $this->addFlash('success', 'Thanks, your message was sent. We will contact you soon.');

        return $this->redirectToRoute('exchange');
    }
}
