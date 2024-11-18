<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SocialshareController extends AbstractController
{
    #[Route('/social-share')]
    public function socialShare(Request $request)
    {
        return $this->render('main/pages/_social_sharer.html.twig');
    }
}
