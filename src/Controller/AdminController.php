<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\User;
use App\Form\EntrepriseRegistrationType;
use App\Form\RegistrationType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
#[IsGranted("ROLE_ADMIN")]

class AdminController extends AbstractController
{
//    #[Route('/admin', name: 'app')]
//    public function index(): Response
//    {
//        return $this->render('admin/index.html.twig', [
//            'controller_name' => 'AdminController',
//        ]);
//    }

    #[Route('/admin', name: 'ajouter_entreprise')]
    public function addEntreprise(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher,FileUploader $fileUploader): Response
    {
        $entreprise = new Entreprise();
        $user= new User();
        $form = $this->createForm(EntrepriseRegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                ));
            if ($form['logoPath']->getData()) {
                /** @var UploadedFile $Profileimg */
                $Profileimg = $form->get('logoPath')->getData();
                $ProfileimgName = $fileUploader->upload($Profileimg);
                $entreprise->setLogoPath($ProfileimgName);
            } else {
                $entreprise->setLogoPath("default.jpg");
            }

            $user -> setEmail($form->get('email')->getData());
            
            $user->setRoles(["ROLE_ENTREPRISE"]);
            $em->persist($user);
            $em->flush();

            $entreprise->setName($form->get('name')->getData());
            $entreprise->setAdress($form->get('adress')->getData());
            $entreprise->setUser($user);
            $entreprise->setDescription($form->get('description')->getData());

            $em->persist($entreprise);
            $em->flush();
        }
            return $this->render('admin/addEntreprise.html.twig', [
                'form' => $form->createView(), 'controller_name' => 'RegistrationController'
            ]);
        }
}

