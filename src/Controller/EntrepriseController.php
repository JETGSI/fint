<?php

namespace App\Controller;

use App\Entity\CurriculumVitae;
use App\Entity\Entreprise;
use App\Entity\JobOffer;
use App\Entity\Student;
use App\Form\CvType;
use App\Form\EntrepriseRegistrationType;
use App\Form\EntrepriseType;
use App\Form\JobOfferType;
use App\Repository\CurriculumVitaeRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\JobRequestRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[IsGranted("ROLE_ENTREPRISE")]
#[Route('/entreprise', name: 'entreprise')]
class EntrepriseController extends AbstractController
{

    #[Route('/', name: 'home_entreprise')]
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(EntrepriseRepository $entrepo, EntityManagerInterface $entityManager): Response
    {

        $userId=$this->getUser()->getId();
        $entreprise = $entityManager->getRepository(Entreprise::class)->findEntrepriseByUserId($userId);
        // dd($entreprise);
        // $entreprise=$entrepo->findOneBy(['user'=> $this->getUser()]);
        if($entreprise->getJobOffers()[0]) {
            $catalogue = $entreprise->getJobOffers()[0]->getCatalogue();
        }else{
            $catalogue= null;
        }

        return $this->render('entreprise/profile.html.twig',
            [
                'entreprise' => $entreprise,
                'user' => $this->getUser(),
                'catalogue'=> $catalogue
                ]
        );
    }

    #[Route('/addjob', name: 'addjob')]
    public function addjob(Request $request, EntityManagerInterface $em, EntrepriseRepository $EntRepo, FileUploader $fileUploader): Response
    {


        $job=new JobOffer();
        $form = $this->createForm(JobOfferType::class);
        $form->handleRequest($request);
        // $e=$EntRepo->findOneBy(['user' => $this->getUser()->getId()]);
        $userId=$this->getUser()->getId();
        $e = $EntRepo->findEntrepriseByUserId($userId);
        if($e->getJobOffers()[0]) {
            $jobexist = $e->getJobOffers()[0]->getCatalogue();
        }else{
            $jobexist= null;
        }
        if (( is_null($jobexist) ) and ($form->isSubmitted() && $form->isValid())) {
            $job=$form->getData();
            $job->setNbViews(0);
            $job->setEntreprise($e);
            /** @var UploadedFile $catalogue */
            $catalogue = $form->get('catalogue')->getData();
            $CatalogueName = $fileUploader->upload($catalogue);
            $job->setCatalogue($CatalogueName);
            // $e->getJobOffers()[0]->setNbViews(0);
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('entrepriseprofile', [], Response::HTTP_SEE_OTHER);
        }elseif ($form->isSubmitted() && $form->isValid()){
            unlink($jobexist);
            $e->getJobOffers()[0]->setApplicationDeadline($form->get('applicationDeadline')->getData());
            $e->getJobOffers()[0]->setNbViews(0);
            /** @var UploadedFile $catalogue */
            $catalogue = $form->get('catalogue')->getData();
            $CatalogueName = $fileUploader->upload($catalogue);
            $e->getJobOffers()[0]->setCatalogue($CatalogueName);
            $job->setNbViews(0);
            $em->flush();
            return $this->redirectToRoute('entrepriseprofile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/addJobOffer.html.twig', [
            'form' => $form->createView(),
            'Jobexist' => $jobexist]);
    }


    #[Route('/candidatures', name: 'entreprise_candidatures')]
    public function cand(JobRequestRepository $jbrepo, EntrepriseRepository $entrepo, CurriculumVitaeRepository $cvRepo): Response
    {

        $entreprise=$entrepo->findOneBy(['user'=> $this->getUser()]);
        $cand=$jbrepo->findBy(['entreprise'=>$entreprise]);


        return $this->render('entreprise/candidatures.html.twig',
            [
                'entreprise' => $entreprise,
                'candidatures' => $cand ,

            ]
        );
    }

    #[Route('/candidatures/{id}', name: 'candidatures_show')]
    public function cand_show(JobRequestRepository $jbrepo, EntrepriseRepository $entrepo, Student $student, StudentRepository $studentRepo,CurriculumVitaeRepository $cvrepo, UserRepository $userRepository): Response
    {

        $entreprise=$entrepo->findOneBy(['user'=> $this->getUser()]);
        $cand=$jbrepo->findBy(['entreprise'=>$entreprise]);
        $user= $userRepository->findOneBy(['id'=>$student->getUser()]);
        $cv=$cvrepo->findOneBy(['user'=>$user]);

        $Edu=$cv->getEducationalExperiences();
        $Asso=$cv->getAssociativeExperiences();
        $Pro=$cv->getProfessionalExperiences();

        return $this->render('user/Cv_print.html.twig',
            [
                'student'=>$student,
            'user'=>$user,
            'cv'=> $cv,
            'edu' => $Edu,
            'pro' => $Pro,
            'asso' => $Asso
            ]
        );
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntrepriseRepository $entRepo ): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entRepo->save($entreprise, true);

            return $this->redirectToRoute('entrepriseprofile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprise/edit.html.twig', [
            'ent' => $entreprise,
            'form' => $form,
        ]);
    }


}
