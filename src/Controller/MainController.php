<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Bookings;
use App\Entity\HotelsInBhutan;
use App\Entity\TopDestination;
use App\Entity\TourPackage;
use App\Form\BookingType;
use App\Form\ContactType;
use App\Service\ContactValidation;
use App\Service\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

class MainController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }

    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        $tour_packages_category = [];
        $tour_packages = $this->em->getRepository(TourPackage::class)->findTourPackages();
        $top_destinations = $this->em->getRepository(TopDestination::class)->findAll();
        $tour_packages_all = $this->em->getRepository(TourPackage::class)->findAll();

        foreach ($tour_packages_all  as $package) {
            $tour_packages_category[$package->getTourCategory()][$package->getId()] = $package;
        }
  
        return $this->render('main/index.html.twig', [
            'tour_packages' => $tour_packages,
            'top_destinations' => $top_destinations,
            'tour_packages_category' => $tour_packages_category
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

    #[Route('/tour/{id}/{slug}', name: 'view-tours')]
    public function viewTours($id): Response
    {
        $tour_package = $this->em->getRepository(TourPackage::class)->find($id);

        return $this->render('main/view-tours.html.twig', [
            'tour_packages' => $tour_package
        ]);
    }

    #[Route('/blog', name: 'blog')]
    public function blog(PaginatorInterface $paginator, Request $request): Response
    {
        $blogs = $this->em->getRepository(Blog::class)->findAll();        
        $pagination = $paginator->paginate(
            $blogs, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );
        return $this->render('main/blogs.html.twig', [
            'blogs' => $pagination
        ]);
    }

    #[Route('/blog/{id}-{slug}', name: 'view-blog')]
    public function viewblog(Blog $blog, $id): Response
    {
        $limit = 10;
        $blogs = $this->em->getRepository(Blog::class)->findBlogs($limit);
        $base_url = 'https://www.'.$_SERVER['HTTP_HOST'];
        $blog_url = $base_url.'/blog/'.$id.'-'.$blog->getBlogSlug();
        return $this->render('main/view-blog.html.twig', [
            'blog' => $blog,
            'blog_url' => $blog_url,
            'blogs' => $blogs,
            'base_url' => $base_url
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

    #[Route('/top-destinations', name: 'top-destinations')]
    public function topDestination()
    {
        $topDestinations = $this->em->getRepository(TopDestination::class)->findAll();
        return $this->render('main/top-destinations.html.twig', ['topDestinations' => $topDestinations ]);
    }

    #[Route('/hotels-in-bhutan', name: 'hotels-in-bhutan-front')]
    public function hotelsInBhutan()
    {
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        return $this->render('main/hotels-in-bhutan.html.twig',[
            'hotelsInBhutan' => $hotelsInBhutan
        ]);
    }

    #[Route('/hotels/{slug}', name: 'hotel-in-bhutan')]
    public function hotelInBhutan($slug)
    {
        $hotelInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findOneBy(['slug' => $slug]);
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        return $this->render('main/hotel-in-bhutan.html.twig',[
            'hotelInBhutan' => $hotelInBhutan,
            'hotelsInBhutan' => $hotelsInBhutan
        ]);
    }

    #[Route('/contact-us', name: 'contact-us')]
    public function contactUs(Request $request, SendEmail $emailService, ContactValidation $validation)
    {
        $form = $this->createForm(ContactType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($validation->getErrors($form->getData()));
            //$emailService->sendEmail($form->getData(), 'contact');
        }
        return $this->render('main/contact.html.twig', ['form' => $form]);
    }

    #[Route('/trip-planner', name: 'trip-planner')]
    public function tripPlanner()
    {
        return $this->render('main/trip-planner.html.twig');
    }
}
