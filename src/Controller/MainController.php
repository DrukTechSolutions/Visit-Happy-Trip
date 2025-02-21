<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Bookings;
use App\Entity\HotelsInBhutan;
use App\Entity\TopDestination;
use App\Entity\TourCategory;
use App\Entity\TourPackage;
use App\Entity\TravelInfo;
use App\Entity\TravelInfoCategory;
use App\Enum\TravelInfoEnum;
use App\Form\BookingType;
use App\Form\ContactType;
use App\Service\CategoryService;
use App\Service\ContactValidation;
use App\Service\SendEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

ini_set('memory_limit', '256M');

class MainController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }

    #[Route('/', name: 'app_main')]
    public function index(CategoryService $categoryService): Response
    {
        $tour_packages = $this->em->getRepository(TourPackage::class)->findTourPackages();
        $top_destinations = $this->em->getRepository(TopDestination::class)->findAll();
        $tour_packages_all = $this->em->getRepository(TourPackage::class)->findAll();
        $categories = $this->em->getRepository(TourCategory::class)->findAll();
        return $this->render('main/index.html.twig', [
            'tour_packages' => $tour_packages,
            'top_destinations' => $top_destinations,
            'tour_packages_all' => $tour_packages_all,
            'best_selling_packages' => $categoryService->parentCategoryTours($categories)
        ]);
    }

    #[Route('/tour-packages/{page}', name: 'tour-packages', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function tourPackages(CategoryService $categoryService, PaginatorInterface $paginator, $page)
    {
        $categories = $this->em->getRepository(TourCategory::class)->findAll();
        $bestSellingPackages = $categoryService->parentCategoryTours($categories);

        $pagination = $paginator->paginate(
            $bestSellingPackages['tour_categories'], /* query NOT result */
            $page, /* page number */
            9 /* limit per page */
        );
        return $this->render('main/tour-packages.html.twig', [
            'best_selling_packages' => $pagination
        ]);
    }

    #[Route('/tour/{slug}', name: 'tour-package')]
    public function tourPackage($slug)
    {
        $tours = [];
        $image_name = '';
        $category = $this->em->getRepository(TourCategory::class)->findBy(['slug' => $slug]);

        foreach ($category[0]->getTourCategories() as $tourCategories) {
            if ($tourCategories) {
                foreach ($tourCategories->getTourPackage() as $package) {
                    $tours[$package->getId()] = $package;
                    foreach ($package->getImages() as $image) {
                        if ($image) {
                            $image_name = $image->getImageName();
                        }
                    }
                }
            }
        }
        return $this->render('main/tour-package.html.twig', [
            'tours' => $tours,
            'tour_category' => $category[0]->getCategory(),
            'tour_category_slug' => $slug,
            'image_name' => $image_name
        ]);
    }

    #[Route('/tour/{category}/{slug}', name: 'view-tours')]
    public function viewTours($slug): Response
    {
        $tour_package = $this->em->getRepository(TourPackage::class)->findBy(['tour_title_slug' => $slug]);

        return $this->render('main/view-tours.html.twig', [
            'tour_packages' => $tour_package[0]
        ]);
    }

    #[Route('/blogs/{page}', name: 'blog', requirements: ['page' => '\d+'], defaults: ['page' => 1])]
    public function blog(PaginatorInterface $paginator, $page): Response
    {
        $blogs = $this->em->getRepository(Blog::class)->findAll();
        $pagination = $paginator->paginate(
            $blogs, /* query NOT result */
            $page, /* page number */
            6 /* limit per page */
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
        $travelVisaCategory = $this->em->getRepository(TravelInfoCategory::class)->findOneBy(['travel_info_category_name' => TravelInfoEnum::TRAVEL_AND_VISA->value]);
        return $this->render('main/travel_and_visa.html.twig', [
            'travelVisas' => $travelVisaCategory->getTravelInfo()
        ]);
    }

    #[Route('/faqs-and-raqs', name: 'faqs-and-raqs')]
    public function faqsAndRaqs(): Response
    {
        $faqsRaqsCategory = $this->em->getRepository(TravelInfoCategory::class)->findOneBy(['travel_info_category_name' => TravelInfoEnum::FAQS_AND_RAQS->value]);
        return $this->render('main/faqs.html.twig', [
            'faqsRaqs' => $faqsRaqsCategory->getTravelInfo()
        ]);
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

    #[Route('/top-destination/{slug}', name: 'top-destination')]
    public function topDestination($slug)
    {
        $topDestination = $this->em->getRepository(TopDestination::class)->findOneBy(['slug' => $slug]);
        return $this->render('main/top-destinations.html.twig', ['topDestination' => $topDestination ]);
    }

    #[Route('/hotels-in-bhutan', name: 'hotels-in-bhutan-front')]
    public function hotelsInBhutan()
    {
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        return $this->render('main/hotels-in-bhutan.html.twig', [
            'hotelsInBhutan' => $hotelsInBhutan
        ]);
    }

    #[Route('/hotels/{slug}', name: 'hotel-in-bhutan')]
    public function hotelInBhutan($slug)
    {
        $hotelInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findOneBy(['slug' => $slug]);
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        return $this->render('main/hotel-in-bhutan.html.twig', [
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

    public function _tourCategories($packages_route)
    {
        $mainCategory = [];
        $tourCategories = $this->em->getRepository(TourCategory::class)->findAll();
        $currentRoute = false;
        foreach ($tourCategories as $tourCategory) {
            
            if($tourCategory->getSlug() == $packages_route) {
                $mainCategory[$tourCategory->getId()]['is_current_route'] = true;
            } 
            if ($tourCategory->getSubCategory() == null) {
                $mainCategory[$tourCategory->getId()]['category'] = $tourCategory->getCategory();
                $mainCategory[$tourCategory->getId()]['slug'] = $tourCategory->getSlug();
            }
        }
        return $this->render('main/pages/_packages.html.twig', [
            'categories' => $mainCategory,
        ]);
    }
}
