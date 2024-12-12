<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\HotelsInBhutan;
use App\Entity\Images;
use App\Entity\TopDestination;
use App\Entity\TourCategory;
use App\Entity\TourPackage;
use App\Entity\TravelInfo;
use App\Entity\TravelInfoCategory;
use App\Form\BlogType;
use App\Form\HotelsInBhutanType;
use App\Form\TopDestinationType;
use App\Form\TourCategoryType;
use App\Form\TourPackageType;
use App\Form\TravelInfoType;
use App\Service\CategoryService;
use App\Service\UploadImage;
use App\Enum\TravelInfoEnum;
use App\Event\DeleteCategoryEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $tours_packages = $this->em->getRepository(TourPackage::class)->findAll();
        $top_destinations = $this->em->getRepository(TopDestination::class)->findAll();
        $hotels_in_bhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        $blogs = $this->em->getRepository(Blog::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'total_tours_packages' => count($tours_packages),
            'total_top_destinations' => count($top_destinations),
            'total_hotels_in_bhutan' => count($hotels_in_bhutan),
            'total_blogs' => count($blogs),
        ]);
    }

    #[Route('/admin/tour-packages', name: 'admin-tour-packages')]
    public function tourPackages(PaginatorInterface $paginator, Request $request): Response
    {
        $tours_packages = $this->em->getRepository(TourPackage::class)->findAll();
        $pagination = $paginator->paginate(
            $tours_packages, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );
        return $this->render('admin/tour_packages.html.twig', ['tours_packages' => $pagination]);
    }

    #[Route('/admin/add-tour', name: 'add-tour')]
    public function addTour(Request $request, UploadImage $uploadImage, SluggerInterface $slug): Response
    {
        $tourPackage = new TourPackage();
        $form = $this->createForm(TourPackageType::class, $tourPackage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $tour_images = $request->files->get('tour_package')['images'];

            foreach ($tour_images as $image) {
                if ($image instanceof UploadedFile) {
                    $images = new Images();
                    $images->setImageName($uploadImage->uploadImage($image));
                    $tourPackage->addImage($images);
                }
            }

            $titleSlug = $tourPackage->getTourTitle();
            $tourPackage->setTourTitleSlug($slug->slug(strtolower($titleSlug)));
            $this->em->persist($tourPackage);
            $this->em->flush();

            $this->addFlash('notice', 'Added successfully.');

            return $this->redirectToRoute('admin-tour-packages');
        }
        return $this->render('admin/add_tour.html.twig', [
            'form' => $form,
            'form_status' => 'Save'
        ]);
    }

    #[Route('/admin/sub-category-select', name: 'sub-category-select')]
    public function subCategory(Request $request)
    {
        $categories = [];
        $categoryId = $request->request->get('category_id');
        if ($categoryId) {
            $subCategories = $this->em->getRepository(TourCategory::class)->findBy(['sub_category' => $categoryId ]);

            foreach ($subCategories as $subCategory) {
                $categories[$subCategory->getId()] = $subCategory->getCategory();
            }
        }
        return $this->json($categories);
    }

    #[Route('/admin/update-tour-package/{id}', name: 'update-tour-package')]
    public function updateTourPackage(Request $request, UploadImage $uploadImage, $id, SluggerInterface $slug): Response
    {
        $tourPackageImages = [];
        $tourPackageImagesId = [];
        $tourPackage = $this->em->getRepository(TourPackage::class)->find($id);
        $form = $this->createForm(TourPackageType::class, $tourPackage);
        $form->handleRequest($request);

        foreach ($tourPackage->getImages() as $key => $image) {
            $tourPackageImagesId[$key] = $image->getId();
            $tourPackageImages[$key] = $image->getImageName();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tourPackageImage1 = $request->files->get('tour_package')['images']['image_1'];
            $tourPackageImage2 = $request->files->get('tour_package')['images']['image_2'];
            $tourPackageImage3 = $request->files->get('tour_package')['images']['image_3'];
            $tour_images = [$tourPackageImage1, $tourPackageImage2, $tourPackageImage3];

            foreach ($tour_images as $key => $image) {
                if ($image) {
                    $imageObj = array_key_exists($key, $tourPackageImagesId) ? $this->em->getRepository(Images::class)->find($tourPackageImagesId[$key]) : new Images();
                    $imageObj->setImageName($uploadImage->uploadImage($image));
                    $tourPackage->addImage($imageObj);
                }
            }
            $titleSlug = $tourPackage->getTourTitle();
            $tourPackage->setTourTitleSlug($slug->slug(strtolower($titleSlug)));
            $this->em->persist($tourPackage);
            $this->em->flush();

            $this->addFlash('notice', 'Updated successfully.');

            return $this->redirectToRoute('admin-tour-packages');
        }
        return $this->render('admin/add_tour.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'tourPackageImages' => $tourPackageImages
        ]);
    }

    #[Route('/admin/{id}/delete-tour-package', name: 'delete-tour-package')]
    public function deleteTourPackage($id)
    {
        $tourPackage = $this->em->getRepository(TourPackage::class)->find($id);

        $this->em->remove($tourPackage);
        $this->em->flush();

        $this->addFlash('notice', 'Deleted successfully.');

        return $this->redirectToRoute('admin-tour-packages');
    }

    #[Route('/admin/add-blog', name: 'add-blog')]
    public function addBlog(Request $request, UploadImage $uploadImage, SluggerInterface $slug): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog_image_file = $request->files->get('blog')['blog_image'];
            $image = new Images();
            $image->setImageName($uploadImage->uploadImage($blog_image_file));
            $blogSlug = $blog->getBlogTitle();
            $blog->setBlogSlug($slug->slug(strtolower($blogSlug)));
            $blog->setImage($image);
            $this->em->persist($blog);
            $this->em->flush();

            $this->addFlash('notice', 'Added successfully.');

            return $this->redirectToRoute('blogs');
        }
        return $this->render('admin/add_blog.html.twig', [
            'form' => $form,
            'form_status' => 'Add'
        ]);
    }

    #[Route('/admin/{id}/update-blog', name: 'update-blog')]
    public function updateBlog(Request $request, UploadImage $uploadImage, $id, SluggerInterface $slug): Response
    {
        $blog = $this->em->getRepository(Blog::class)->find($id);
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog_image_file = $request->files->get('blog')['blog_image'];
            if ($blog_image_file) {
                $image = new Images();
                $image->setImageName($uploadImage->uploadImage($blog_image_file));
                $blog->setImage($image);
            }
            $blogSlug = $blog->getBlogTitle();
            $blog->setBlogSlug($slug->slug(strtolower($blogSlug)));
            $this->em->persist($blog);
            $this->em->flush();

            $this->addFlash('notice', 'Updated successfully.');

            return $this->redirectToRoute('blogs');
        }
        return $this->render('admin/add_blog.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'image_name' => $blog->getImage()->getImageName()
        ]);
    }

    #[Route('/admin/{id}/delete-blog', name: 'delete-blog')]
    public function deleteBlog($id)
    {
        $blog = $this->em->getRepository(Blog::class)->find($id);

        $this->em->remove($blog);
        $this->em->flush();

        $this->addFlash('notice', 'Deleted successfully.');

        return $this->redirectToRoute('blogs');
    }

    #[Route('/admin/blogs', name: 'blogs')]
    public function blogs(PaginatorInterface $paginator, Request $request)
    {
        $blogs = $this->em->getRepository(Blog::class)->findAll();
        $pagination = $paginator->paginate(
            $blogs, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );
        return $this->render('admin/blogs.html.twig', ['blogs' => $pagination]);
    }

    #[Route('/admin/top-destinations', name: 'top-destinations-all')]
    public function topDestinations(PaginatorInterface $paginator, Request $request)
    {
        $topDestinations = $this->em->getRepository(TopDestination::class)->findAll();

        $pagination = $paginator->paginate(
            $topDestinations, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );

        return $this->render('admin/top-destinations.html.twig', ['pagination' => $pagination]);
    }

    #[Route('/admin/add-top-destination', name: 'add-top-destination')]
    public function addTopDestination(Request $request, UploadImage $uploadImage, SluggerInterface $slug)
    {
        $topDestination = new TopDestination();
        $form = $this->createForm(TopDestinationType::class, $topDestination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $destinationImage = $request->files->get('top_destination')['destination_image'];
            $image = new Images();
            $image->setImageName($uploadImage->uploadImage($destinationImage));
            $topDestination->setImage($image);

            $titleSlug = $slug->slug(strtolower($topDestination->getDestinationTitle()));
            $topDestination->setSlug($titleSlug);
            $this->em->persist($topDestination);
            $this->em->flush();

            $this->addFlash('notice', 'Added successfully.');

            return $this->redirectToRoute('top-destinations-all');
        }
        return $this->render('admin/add-top-destination.html.twig', [
            'form' => $form,
            'form_status' => 'Add'
        ]);
    }

    #[Route('/admin/{id}/update-top-destination/', name: 'update-top-destination')]
    public function updateTopDestination(Request $request, $id, UploadImage $uploadImage, SluggerInterface $slug)
    {
        $topDestination = $this->em->getRepository(TopDestination::class)->find($id);
        $form = $this->createForm(TopDestinationType::class, $topDestination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $destinationImage = $request->files->get('top_destination')['destination_image'];
            if ($destinationImage) {
                $image = new Images();
                $image->setImageName($uploadImage->uploadImage($destinationImage));
                $topDestination->setImage($image);
            }
            $titleSlug = $slug->slug(strtolower($topDestination->getDestinationTitle()));
            $topDestination->setSlug($titleSlug);
            $this->em->persist($topDestination);
            $this->em->flush();

            $this->addFlash('notice', 'Updated successfully.');

            return $this->redirectToRoute('top-destinations-all');
        }
        return $this->render('admin/add-top-destination.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'image_name' => $topDestination->getImage()->getImageName()
        ]);
    }

    #[Route('/admin/{id}/delete-top-destination', name: 'delete-top-destination')]
    public function deleteTopDestinations($id)
    {
        $topDestination = $this->em->getRepository(TopDestination::class)->find($id);
        $this->em->remove($topDestination);
        $this->em->flush();

        $this->addFlash('notice', 'Deleted successfully.');

        return $this->redirectToRoute('top-destinations-all');
    }

    #[Route('/admin/hotels-in-bhutan', name: 'hotels-in-bhutan')]
    public function hotelsInBhutan(PaginatorInterface $paginator, Request $request)
    {
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        $pagination = $paginator->paginate(
            $hotelsInBhutan, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            5 /* limit per page */
        );
        return $this->render('admin/hotels-in-bhutan.html.twig', [
            'hotelsInBhutan' => $pagination
        ]);
    }

    #[Route('/admin/add-hotels-in-bhutan', name: 'add-hotels-in-bhutan')]
    public function addHotelsInBhutan(Request $request, UploadImage $uploadImage, SluggerInterface $slug)
    {
        $hotelsInBhutan = new HotelsInBhutan();
        $form = $this->createForm(HotelsInBhutanType::class, $hotelsInBhutan);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $hotel_images = $request->files->get('hotels_in_bhutan')['images'];
            foreach ($hotel_images as $image) {
                $images = new Images();
                $images->setImageName($uploadImage->uploadImage($image));
                $hotelsInBhutan->addImage($images);
            }
            $hotelNameSlug = $hotelsInBhutan->getHotelName();
            $hotelsInBhutan->setSlug($slug->slug(strtolower($hotelNameSlug)));
            $this->em->persist($hotelsInBhutan);
            $this->em->flush();

            $this->addFlash('notice', 'Added successfully.');

            return $this->redirectToRoute('hotels-in-bhutan');
        }

        return $this->render('admin/add-hotels-in-bhutan.html.twig', [
            'form' => $form,
            'form_status' => 'Add'
        ]);
    }

    #[Route('/admin/{id}/update-hotels-in-bhutan', name: 'update-hotels-in-bhutan')]
    public function updateHotelsInBhutan(Request $request, UploadImage $uploadImage, SluggerInterface $slug, $id)
    {
        $hotelsInBhutanImages = [];
        $hotelsInBhutanImagesId = [];
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->find($id);
        foreach ($hotelsInBhutan->getImages() as $key => $image) {
            $hotelsInBhutanImagesId[$key] = $image->getId();
            $hotelsInBhutanImages[$key] = $image->getImageName();
        }

        $form = $this->createForm(HotelsInBhutanType::class, $hotelsInBhutan);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $hotelImage1 = $request->files->get('hotels_in_bhutan')['images']['image_1'];
            $hotelImage2 = $request->files->get('hotels_in_bhutan')['images']['image_2'];
            $hotelImage3 = $request->files->get('hotels_in_bhutan')['images']['image_3'];

            $hotel_images = [$hotelImage1, $hotelImage2, $hotelImage3];
            foreach ($hotel_images as $key => $image) {
                if ($image) {
                    $imageObj = array_key_exists($key, $hotelsInBhutanImagesId) ? $this->em->getRepository(Images::class)->find($hotelsInBhutanImagesId[$key]) : new Images();
                    $imageObj->setImageName($uploadImage->uploadImage($image));
                    $hotelsInBhutan->addImage($imageObj);
                }
            }
            $hotelNameSlug = $hotelsInBhutan->getHotelName();
            $hotelsInBhutan->setSlug($slug->slug(strtolower($hotelNameSlug)));
            $this->em->persist($hotelsInBhutan);
            $this->em->flush();

            $this->addFlash('notice', 'Updated successfully.');

            return $this->redirectToRoute('hotels-in-bhutan');
        }

        return $this->render('admin/add-hotels-in-bhutan.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'hotelsInBhutanImages' => $hotelsInBhutanImages
        ]);
    }

    #[Route('admin/{id}/delete-hotel-in-bhutan', name: 'delete-hotel-in-bhutan')]
    public function deleteHotelInBhutan($id)
    {
        $hotelInBhutan = $this->em->getRepository(HotelsInBhutan::class)->find($id);

        $this->em->remove($hotelInBhutan);
        $this->em->flush();

        $this->addFlash('notice', 'Deleted successfully.');

        return $this->redirectToRoute('hotels-in-bhutan');
    }

    #[Route('admin/add-tour-category', name: 'add-tour-category')]
    public function addTourCategory(Request $request, CategoryService $categoryService, SluggerInterface $slug)
    {
        $tourCategory = new TourCategory();
        $categories = $this->em->getRepository(TourCategory::class)->findAll();
        $form = $this->createForm(TourCategoryType::class, $tourCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($parentCategoryId = $request->get('tour_category')['parent_category']) {
                $parentCategory = $this->em->getRepository(TourCategory::class)->find($parentCategoryId);
                $tourCategory->setSubCategory($parentCategory);
            }

            $category = strtolower($tourCategory->getCategory());
            $tourCategory->setSlug($slug->slug($category));
            $this->em->persist($tourCategory);
            $this->em->flush();

            return $this->redirectToRoute('add-tour-category');
        }

        return $this->render('admin/add-tour-category.html.twig', [
            'form' => $form,
            'form_status' => 'Add',
            'categories' => $categoryService->categoryAndSubCategory($categories)
        ]);
    }

    #[Route('admin/{id}/delete-tour-category' , name: 'delete-tour-category')]
    public function deleteTourCategory($id, EventDispatcherInterface $eventDispatcherInterface)
    {
        $tourCategory = $this->em->getRepository(TourCategory::class)->find($id);
        $tours = $this->em->getRepository(TourPackage::class)->findOneBy(['tourCategory' => $tourCategory]);
        
        if($tours) {
            $event = new DeleteCategoryEvent($tours);
            $eventDispatcherInterface->dispatch($event);
        }

        $this->em->remove($tourCategory);
        $this->em->flush();

        return $this->redirectToRoute('add-tour-category');
    }

    #[Route('admin/{id}/update-tour-category', name: 'update-tour-category')]
    public function updateTourCategory(Request $request, CategoryService $categoryService, SluggerInterface $slug, $id)
    {

        $categories = $this->em->getRepository(TourCategory::class)->findAll();
        $tourCategory = $this->em->getRepository(TourCategory::class)->find($id);
        $form = $this->createForm(TourCategoryType::class, $tourCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($parentCategoryId = $request->get('tour_category')['parent_category']) {
                $parentCategory = $this->em->getRepository(TourCategory::class)->find($parentCategoryId);
                $tourCategory->setSubCategory($parentCategory);
            }

            $category = strtolower($tourCategory->getCategory());
            $tourCategory->setSlug($slug->slug($category));
            $this->em->persist($tourCategory);
            $this->em->flush();

            return $this->redirectToRoute('add-tour-category');
        }

        return $this->render('admin/add-tour-category.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'categories' => $categoryService->categoryAndSubCategory($categories)
        ]);
    }

    #[Route('admin/faqs-and-raqs', name: 'all-faqs-and-raqs')]
    public function faqsAndRaqs(Request $request)
    {
        $travelInfo = new TravelInfo();
        $faqsRaqsCategory = $this->em->getRepository(TravelInfoCategory::class)->findOneBy(['travel_info_category_name' => TravelInfoEnum::FAQS_AND_RAQS->value]);
        $form = $this->createForm(TravelInfoType::class, $travelInfo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $travelInfo->setTravelInfoCategory($faqsRaqsCategory);
            $this->em->persist($travelInfo);
            $this->em->flush();

            $this->addFlash('notice', 'FAQs & RAQs saved!');

            return $this->redirectToRoute('all-faqs-and-raqs');
        }
        return $this->render('admin/faqs-and-raqs.html.twig', [
            'form' => $form,
            'faqsRaqs' => $faqsRaqsCategory->getTravelInfo()
        ]);
    }

    #[Route('admin/edit-faqs-and-raqs/{id?}', name: 'edit-faqs-and-raqs')]
    public function editFaqsAndRaqs(Request $request, $id)
    {
        $faqsRaqsCategory = $this->em->getRepository(TravelInfoCategory::class)->findOneBy(['travel_info_category_name' => TravelInfoEnum::FAQS_AND_RAQS->value]);
        $travelInfoId = !empty($request->request->get('id')) ? $request->request->get('id') : $id;
        $faqRaq = $this->em->getRepository(TravelInfo::class)->find($travelInfoId);
        $form = $this->createForm(TravelInfoType::class, $faqRaq, [
            'action' => $this->generateUrl('edit-faqs-and-raqs', ['id' => $travelInfoId]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $faqRaq->setTravelInfoCategory($faqsRaqsCategory);
            $this->em->persist($faqRaq);
            $this->em->flush();
            $this->addFlash('notice', 'FAQs & RAQs updated!');
            return $this->redirectToRoute('all-faqs-and-raqs');
        }
        $html = $this->renderView('admin/includes/_travel_info.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json(['html' => $html]);
    }

    #[Route('admin/travel-and-visa', name: 'all-travel-and-visa')]
    public function travelAndVisa(Request $request)
    {
        $travelInfo = new TravelInfo();
        $travelVisaCategory = $this->em->getRepository(TravelInfoCategory::class)->findOneBy(['travel_info_category_name' => TravelInfoEnum::TRAVEL_AND_VISA->value]);
        $form = $this->createForm(TravelInfoType::class, $travelInfo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $travelInfo->setTravelInfoCategory($travelVisaCategory);
            $this->em->persist($travelInfo);
            $this->em->flush();

            $this->addFlash('notice', 'Travel & Visa saved!');

            return $this->redirectToRoute('all-travel-and-visa');
        }
        return $this->render('admin/travel-and-visa.html.twig', [
            'form' => $form,
            'travelVisas' => $travelVisaCategory->getTravelInfo()
        ]);
    }

    #[Route('admin/edit-travel-and-visa/{id?}', name: 'edit-travel-and-visa')]
    public function editTravelAndVisa(Request $request, $id)
    {
        $travelVisaCategory = $this->em->getRepository(TravelInfoCategory::class)->findOneBy(['travel_info_category_name' => TravelInfoEnum::TRAVEL_AND_VISA->value]);
        $travelInfoId = !empty($request->request->get('id')) ? $request->request->get('id') : $id;
        $travelandVisa = $this->em->getRepository(TravelInfo::class)->find($travelInfoId);
        $form = $this->createForm(TravelInfoType::class, $travelandVisa, [
            'action' => $this->generateUrl('edit-travel-and-visa', ['id' => $travelInfoId]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $travelandVisa->setTravelInfoCategory($travelVisaCategory);
            $this->em->persist($travelandVisa);
            $this->em->flush();
            $this->addFlash('notice', 'Travel & Visa updated!');
            return $this->redirectToRoute('all-travel-and-visa');
        }
        $html = $this->renderView('admin/includes/_travel_info.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json(['html' => $html]);
    }

    #[Route('admin/delete-faqs-raqs/{id}', name: 'delete-faqs-raqs')]
    public function deleteFaqsRaqs($id)
    {
        $faqRaq = $this->em->getRepository(TravelInfo::class)->find($id);
        $this->em->remove($faqRaq);
        $this->em->flush($faqRaq);
        $this->addFlash('notice', 'FAQs & RAQs deleted!');
        return $this->redirectToRoute('all-faqs-and-raqs');
    }

    #[Route('admin/delete-travel-visa/{id}', name: 'delete-travel-visa')]
    public function deleteTravelVisa($id)
    {
        $travelVisa = $this->em->getRepository(TravelInfo::class)->find($id);
        $this->em->remove($travelVisa);
        $this->em->flush($travelVisa);
        $this->addFlash('notice', 'Travel & Visa deleted!');
        return $this->redirectToRoute('all-travel-and-visa');
    }
}
