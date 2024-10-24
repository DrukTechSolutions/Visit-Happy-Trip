<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\ContactValidation;
use App\Service\SendEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SendmailController extends AbstractController
{
    public function contact(Request $request, SendEmail $emailService)
    {
        $form = $this->createForm(ContactType::class, null);
        $form->handleRequest($request);

        return $this->render('main/pages/_contact.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/validate-contact', name: 'validate-contact')]
    public function validateContact(Request $request, ContactValidation $validator, SendEmail $sendEmail)
    {
        $contact = $request->get('contact');
        $validationErrors = $validator->getErrors($contact);
        
        $validationExists = false;
        foreach($validationErrors as $errorMsgs) {
            if(!empty($errorMsgs)) {
                $validationExists = true;
                break;
            }
        } 
        
        if(!$validationExists) {
            //$sendEmail->sendEmail($contact);
        }

        return new JsonResponse($validationErrors);
    }

    #[Route('test')]
    public function test()
    {
        $arr = array('a' => '', 'b' => '','c' => '','d' => '','e' => '');
        $validationExists = false;
        foreach($arr as $val) {
            if(!empty($val)) {
            	$validationExists = true;
            }
        }

       return new JsonResponse($validationExists);
    }
}
