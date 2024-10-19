<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmail
{
    public function __construct(private MailerInterface $mailer)
    {

    }
    public function sendEmail($form)
    {
        $email = new TemplatedEmail();
        $email->subject('this is some random subject');
        $email->from('iamdemigod123@gmail.com');
        $email->to('stevenzong321@gmail.com');
        $email->htmlTemplate('main/email/contact-email.html.twig');
        $email->context([
            'name' => $form['name'],
            'sender_email' => $form['email'],
            'subject' => $form['subject'],
            'message' => $form['message']
        ]);
        $this->mailer->send($email);
        dd('EMail Sent');
    }
}
