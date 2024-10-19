<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Images;
use App\Entity\TourPackage;
use App\Form\BlogType;
use App\Form\TourPackageType;
use App\Service\UploadImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

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
            dd($request->files->get('tour_package'));
            $tourPackageImage1 = $request->files->get('tour_package')['images']['tour_image']['tour_image_1'];
            $tourPackageImage2 = $request->files->get('tour_package')['images']['tour_image']['tour_image_2'];
            $tourPackageImage3 = $request->files->get('tour_package')['images']['tour_image']['tour_image_3'];

            $tour_images = [$tourPackageImage1, $tourPackageImage2, $tourPackageImage3];
            foreach ($tour_images as $image) {
                $images = new Images();
                $images->setImageName($uploadImage->uploadImage($image));
                $tourPackage->addImage($images);
            }
            $this->em->persist($tourPackage);
            $this->em->flush();

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
            $tourPackageImage1 = $request->files->get('tour_package')['images']['tour_image']['tour_image_1'];
            $tourPackageImage2 = $request->files->get('tour_package')['images']['tour_image']['tour_image_2'];
            $tourPackageImage3 = $request->files->get('tour_package')['images']['tour_image']['tour_image_3'];
            $tour_images = [$tourPackageImage1, $tourPackageImage2, $tourPackageImage3];

            foreach ($tour_images as $key => $image) {
                if ($image) {
                    $imageObj = $this->em->getRepository(Images::class)->find($tourPackageImagesId[$key]);
                    $imageObj->setImageName($uploadImage->uploadImage($image));
                    $tourPackage->addImage($imageObj);
                }
            }
            
            $this->em->persist($tourPackage);
            $this->em->flush();

            return $this->redirectToRoute('tour-packages');
        }
        return $this->render('admin/add_tour.html.twig', [
            'form' => $form,
            'form_status' => 'Update',
            'tourPackageImages' => $tourPackageImages
        ]);
    }

    #[Route('/admin/add-blog', name: 'add-blog')]
    public function addBlog(Request $request, UploadImage $uploadImage): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog_image_file = $request->files->get('blog')['blog_image'];
            $image = new Images();
            $image->setImageName($uploadImage->uploadImage($blog_image_file));
            $blog->setImage($image);
            $this->em->persist($blog);
            $this->em->flush();
        }
        return $this->render('admin/add_blog.html.twig', ['form' => $form]);
    }

    #[Route('/admin/{id}/update-blog', name: 'update-blog')]
    public function updateBlog(Request $request, UploadImage $uploadImage, $id): Response
    {
        $blog = $this->em->getRepository(Blog::class)->find($id);
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog_image_file = $request->files->get('blog')['blog_image'];
            $image = new Images();
            $image->setImageName($uploadImage->uploadImage($blog_image_file));
            $blog->setImage($image);
            $this->em->persist($blog);
            $this->em->flush();
        }
        return $this->render('admin/add_blog.html.twig', ['form' => $form]);
    }


    #[Route('/admin/blogs', name: 'blogs')]
    public function blogs()
    {
        $blogs = $this->em->getRepository(Blog::class)->findAll();
        return $this->render('admin/blogs.html.twig', ['blogs' => $blogs]);
    }
}
