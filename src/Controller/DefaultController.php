<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function index(): Response
    {   if ($this->getUser()){
            if ($this->isGranted('ROLE_STUDENT')){
                $this->addFlash("string" ,"You are already connected !!");
                return $this->redirectToRoute('app_userprofile');
            }elseif ($this->isGranted('ROLE_ENTREPRISE')){
                $this->addFlash("You are already connected !!");
                return $this->redirectToRoute('entrepriseprofile');
            }

    }
        return $this->render('default/index.html.twig');
    }

    #[Route('/apropos', name: 'apropos')]
    public function propos(): Response
    {
        
        return $this->render('Apropos.html.twig');
    }
}
