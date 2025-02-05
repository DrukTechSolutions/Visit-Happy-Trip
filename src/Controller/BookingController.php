<?php

namespace App\Controller;

use App\Entity\Bookings;
use App\Form\BookingType;
use App\Service\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BookingController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/book-now', name: 'book-now')]
    public function bookNow(Request $request, SendEmail $emailService)
    {
        $booking = new Bookings();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $emailService->sendBookingEmail($booking);
            $this->em->persist($booking);
            $this->em->flush();
            $this->addFlash('notice', 'Booking query sent successfully.');
            return $this->redirectToRoute('book-now');
        }
        return $this->render('main/book-now.html.twig', ['form' => $form]);
    }
}
