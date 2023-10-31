<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->redirectToRoute('app_usercvbuild');

        }elseif ($this->isGranted('ROLE_ENTREPRISE')){

            return $this->redirectToRoute('entrepriseprofile');
        }elseif ($this->isGranted('ROLE_ADMIN')){

            return $this->redirectToRoute('admin_dashboard');
        };

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/forgot-password', name: 'forgotten_password')]
    public function forgotten_password(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em,  MailerInterface $mailer): Response
    {
        $form= $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $user = $userRepository->findOneByEmail($form->get('email')->getData());
            if($user){
                // ongénère un token de réinitialisation
                $token= $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $context = compact('url', 'user');
                
                $email = (new TemplatedEmail())
                ->from('mansouri.firas@esprit.tn')
                ->to($user->getEmail())
                ->subject('fint: Réinitialisation de mot de passe ')
                ->htmlTemplate('security/email.html.twig')
                ->context($context);
            
                $mailer->send($email);

                $this->addFlash('Reset_Password_Email_Sent','');
                return $this->redirectToRoute('app_login');

            }else{
                //user est null
                $this->addFlash('user_null','l\'e-mail n\'existe pas ! ');
            }
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView(),
        ]);
    }

    #[Route('/reset-password/{token}', name: 'reset_password')]
    public function resetPassword(Request $request, string $token, UserRepository $userRepository, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= $userRepository->findOneByResetToken($token);
        if($user){
            $form= $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $user->setResetToken('');
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
                $em->persist($user);
                $em->flush();
                $this->addFlash('PASSWORD_Modified','');
                return $this->redirectToRoute('app_login');
            }
            return $this->render('security/reset_password.html.twig', ['passForm'=> $form->createView()]);
        }else{
            $this->addFlash('NOT_VALID_TOKEN','');
            return $this->redirectToRoute('app_login');

        }
        return $this->render('security/reset_password_request.html.twig');
    }
}
