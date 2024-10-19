<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\TopDestination;
use App\Entity\TourPackage;
use App\Form\ContactType;
use App\Service\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }

    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        $tour_packages = $this->em->getRepository(TourPackage::class)->findTourPackages();
        $top_destinations = $this->em->getRepository(TopDestination::class)->findAll();

        return $this->render('main/index.html.twig', [
            'tour_packages' => $tour_packages,
            'top_destinations' => $top_destinations
        ]);
    }

    #[Route('/tour/{slug}', name: 'tour-package')]
    public function tourPackage($slug)
    {
        $tours = $this->em->getRepository(TourPackage::class)->findBy(['tour_category' => $slug]);

        $image_name  = [
            'cultural-tour' => 'culture.jpg',
            'festival-tour' => 'dance.jpg',
            'adventure-tour' => 'rafting.jpg',
            'trekking-tour' => 'trekking.png',
        ];

        return $this->render('main/tour-package.html.twig', [
            'tours' => $tours,
            'tour_category' => $slug,
            'image_name' => $image_name[$slug]
        ]);
    }

    #[Route('/tour/view-tours/{id}', name: 'view-tours')]
    public function viewTours($id): Response
    {
        $tour_package = $this->em->getRepository(TourPackage::class)->find($id);

        return $this->render('main/view-tours.html.twig', [
            'tour_packages' => $tour_package
        ]);
    }

    #[Route('/blog', name: 'blog')]
    public function blog(): Response
    {
        $blogs = $this->em->getRepository(Blog::class)->findAll();

        return $this->render('main/blogs.html.twig', [
            'blogs' => $blogs
        ]);
    }

    #[Route('/blog/{id}/{slug}', name: 'view-blog')]
    public function viewblog($id): Response
    {
        $blog = $this->em->getRepository(Blog::class)->find($id);
        return $this->render('main/view-blog.html.twig', [
            'blog' => $blog
        ]);
    }

    #[Route('/about-us', name: 'about-us')]
    public function aboutUs(): Response
    {
        return $this->render('main/about.html.twig');
    }

    #[Route('/travel-and-visa', name: 'travel-and-visa')]
    public function travelAndVisa(): Response
    {
        return $this->render('main/travel_and_visa.html.twig');
    }

    #[Route('/faqs-and-raqs', name: 'faqs-and-raqs')]
    public function faqsAndRaqs(): Response
    {
        return $this->render('main/faqs.html.twig');
    }

    #[Route('/about-bhutan', name: 'about-bhutan')]
    public function aboutBhutan()
    {
        return $this->render('main/about-bhutan.html.twig');
    }

    #[Route('/getting-into-bhutan', name: 'getting-into-bhutan')]
    public function gettingIntoBhutan()
    {
        return $this->render('main/getting-into-bhutan.html.twig');
    }

    #[Route('/contact-us', name: 'contact-us')]
    public function contactUs(Request $request, SendEmail $emailService)
    {
        $form = $this->createForm(ContactType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $emailService->sendEmail($form->getData());
        }
        return $this->render('main/contact.html.twig', ['form' => $form]);
    }

    #[Route('/top-destinations' , name: 'top-destinations')]
    public function topDestination()
    {
        $topDestinations = $this->em->getRepository(TopDestination::class)->findAll();
        return $this->render('main/top-destinations.html.twig',['topDestinations' => $topDestinations ]);
    }
}
