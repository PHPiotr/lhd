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

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    #[Template('Contact/index.html.twig')]
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('subject', null, ['label' => 'Subject *', 'constraints' => [new NotBlank()], 'required' => true])
            ->add('name', null, ['label' => 'Name *', 'constraints' => [new NotBlank()], 'required' => true])
            ->add('company', null, ['label' => 'Company'])
            ->add('telephone_number', null, ['label' => 'Telephone number *', 'constraints' => [new NotBlank()], 'required' => true])
            ->add('email_address', EmailType::class, ['label' => 'Email address *', 'constraints' => [new NotBlank(), new Email()], 'required' => true])
            ->add('message', TextareaType::class, ['label' => 'Message *', 'attr' => ['maxlength' => 1000], 'constraints' => [new NotBlank(), new Length(max: 1000)]])
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
        $message = $this->renderView('Contact/email.html.twig', [
            'data' => $data,
        ]);

        $to = $this->getParameter('mailer_to');
        $subject = 'LHD Contact Form';
        $emailFrom = $data['email_address'];
        $siteEmail = 'sales@lhdvancentre.com';

        $headers[] = sprintf('From: %s', $siteEmail);
        $headers[] = sprintf('Reply-To: %s', $emailFrom);
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $sent = mail($to, $subject, $message, implode("\r\n", $headers), '-f ' . $siteEmail);

        if (!$sent) {
            $this->addFlash('danger', 'Sorry, something went wrong and your message has not been sent.');

            return ['form' => $form->createView()];
        }

        $this->addFlash('success', 'Thanks, your message was sent. We will contact you soon.');

        return $this->redirectToRoute('contact');
    }
}
