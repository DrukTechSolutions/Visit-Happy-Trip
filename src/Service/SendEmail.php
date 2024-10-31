<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Intl\Countries;

class SendEmail
{
    public function __construct(private MailerInterface $mailer) {}

    public function sendEmail($form)
    {
        $email = new TemplatedEmail();
        $email->subject($form['subject']);
        $email->from($form['email']);
        $email->to('info@visithappytrip.com');
        $email->htmlTemplate('main/email/contact-email.html.twig');
        $email->context([
            'name' => $form['name'],
            'sender_email' => $form['email'],
            'subject' => $form['subject'],
            'message' => $form['message']
        ]);
        $this->mailer->send($email);
    }

    public function sendBookingEmail($data) {

        $email = new TemplatedEmail();
        $email->subject('Booking inquiry');
        $email->from($data->getEmail());
        $email->to('info@visithappytrip.com');
        $email->htmlTemplate('main/email/booking-email.html.twig');
        $email->context([
                'name' => $data->getName(),
                'sender_email' => $data->getEmail(),
                'contact_no' => $data->getContactNo(),
                'country' => Countries::getName($data->getCountry()),
                'doa' => $data->getDateOfArrival()->format('j M Y'),
                'doe' => $data->getDateOfDeparture()->format('j M Y'),
                'no_of_adults' => $data->getNoOfAdults(),
                'no_of_child' => $data->getNoOfChild(),
                'tour_type' => $data->getTourType(),
                'tour_packages' => $data->getTourPackages(),
                'message' => $data->getMessage()
            ]);
        $this->mailer->send($email);
    }
}
