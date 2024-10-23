<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\HotelsInBhutan;
use App\Entity\Images;
use App\Entity\TopDestination;
use App\Entity\TourPackage;
use App\Form\BlogType;
use App\Form\HotelsInBhutanType;
use App\Form\TopDestinationType;
use App\Form\TourPackageType;
use App\Service\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
        $blogs = $this->em->getRepository(Blog::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/tour-packages', name: 'tour-packages')]
    public function tourPackages(): Response
    {
        $tours_packages = $this->em->getRepository(TourPackage::class)->findAll();
        return $this->render('admin/tour_packages.html.twig', ['tours_packages' => $tours_packages]);
    }

    #[Route('/admin/add-tour', name: 'add-tour')]
    public function addTour(Request $request, UploadImage $uploadImage): Response
    {
        $tourPackage = new TourPackage();
        $form = $this->createForm(TourPackageType::class, $tourPackage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tourPackageImage1 = $request->files->get('tour_package')['images']['tour_image']['image_1'];
            $tourPackageImage2 = $request->files->get('tour_package')['images']['tour_image']['image_2'];
            $tourPackageImage3 = $request->files->get('tour_package')['images']['tour_image']['image_3'];

            $tour_images = [$tourPackageImage1, $tourPackageImage2, $tourPackageImage3];
            foreach ($tour_images as $image) {
                $images = new Images();
                $images->setImageName($uploadImage->uploadImage($image));
                $tourPackage->addImage($images);
            }
            $this->em->persist($tourPackage);
            $this->em->flush();

            $this->addFlash('notice', 'Added successfully.');

            return $this->redirectToRoute('tour-packages');
        }
        return $this->render('admin/add_tour.html.twig', [
            'form' => $form,
            'form_status' => 'Save'
        ]);
    }


    #[Route('/admin/update-tour-package/{id}', name: 'update-tour-package')]
    public function updateTourPackage(Request $request, UploadImage $uploadImage, $id): Response
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
            $tourPackageImage1 = $request->files->get('tour_package')['images']['tour_image']['image_1'];
            $tourPackageImage2 = $request->files->get('tour_package')['images']['tour_image']['image_2'];
            $tourPackageImage3 = $request->files->get('tour_package')['images']['tour_image']['image_3'];
            $tour_images = [$tourPackageImage1, $tourPackageImage2, $tourPackageImage3];

            foreach ($tour_images as $key => $image) {
                if ($image) {
                    $imageObj = array_key_exists($key, $tourPackageImagesId) ? $this->em->getRepository(Images::class)->find($tourPackageImagesId[$key]) : new Images();
                    $imageObj->setImageName($uploadImage->uploadImage($image));
                    $tourPackage->addImage($imageObj);
                }
            }

            $this->em->persist($tourPackage);
            $this->em->flush();

            $this->addFlash('notice', 'Updated successfully.');

            return $this->redirectToRoute('tour-packages');
        }
        return $this->render('admin/add_tour.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'tourPackageImages' => $tourPackageImages
        ]);
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
            $blog->setBlogSlug($slug->slug($blog->getBlogTitle()));
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
            $blog->setBlogSlug($slug->slug($blog->getBlogTitle()));
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
    public function addTopDestination(Request $request, UploadImage $uploadImage)
    {
        $topDestination = new TopDestination();
        $form = $this->createForm(TopDestinationType::class, $topDestination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $destinationImage = $request->files->get('top_destination')['destination_image'];
            $image = new Images();
            $image->setImageName($uploadImage->uploadImage($destinationImage));
            $topDestination->setImage($image);
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
    public function updateTopDestination(Request $request, $id, UploadImage $uploadImage)
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
    
    #[Route('/admin/hotels-in-bhutan', name: 'hotels-in-bhutan')]
    public function hotelsInBhutan() 
    {
        $hotelsInBhutan = $this->em->getRepository(HotelsInBhutan::class)->findAll();
        return $this->render('admin/hotels-in-bhutan.html.twig',[
            'hotelsInBhutan' => $hotelsInBhutan
        ]);
    }

    #[Route('/admin/add-hotels-in-bhutan', name: 'add-hotels-in-bhutan')]
    public function addHotelsInBhutan(Request $request, UploadImage $uploadImage, SluggerInterface $slug) {
        $hotelsInBhutan = new HotelsInBhutan();
        $form = $this->createForm(HotelsInBhutanType::class, $hotelsInBhutan);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hotelImage1 = $request->files->get('hotels_in_bhutan')['images']['hotels_image']['image_1'];
            $hotelImage2 = $request->files->get('hotels_in_bhutan')['images']['hotels_image']['image_2'];
            $hotelImage3 = $request->files->get('hotels_in_bhutan')['images']['hotels_image']['image_3'];

            $hotel_images = [$hotelImage1, $hotelImage2, $hotelImage3];
            foreach ($hotel_images as $image) {
                $images = new Images();
                $images->setImageName($uploadImage->uploadImage($image));
                $hotelsInBhutan->addImage($images);
            }
            $hotelsInBhutan->setSlug($slug->slug($hotelsInBhutan->getHotelName()));
            $this->em->persist($hotelsInBhutan);
            $this->em->flush();

            $this->addFlash('notice', 'Added successfully.');

            return $this->redirectToRoute('hotels-in-bhutan');
        }

        return $this->render('admin/add-hotels-in-bhutan.html.twig',[
            'form' => $form,
            'form_status' => 'Add'
        ]);
    }
}
