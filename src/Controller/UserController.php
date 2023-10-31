<?php

namespace App\Controller;

use App\Entity\AssociativeExperience;
use App\Entity\CurriculumVitae;
use App\Entity\EducationalExperience;
use App\Entity\Entreprise;
use App\Entity\JobOffer;
use App\Entity\Project;
use App\Entity\JobRequest;
use App\Entity\ProfessionalExperience;
use App\Entity\Student;
use App\Form\AddcertificatType;
use App\Form\AssociativeExperienceType;
use App\Form\AssoeditType;
use App\Form\CvType;
use App\Form\EduExpType;
use App\Form\EntrepriseType;
use App\Form\ProExperienceType;
use App\Form\UploadcvType;
use App\Form\ProjectType;
use App\Repository\AssociativeExperienceRepository;
use App\Repository\EducationalExperienceRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\JobOfferRepository;
use App\Repository\JobRequestRepository;
use App\Repository\ProfessionalExperienceRepository;
use App\Repository\StudentRepository;
use App\Repository\ProjectRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use phpDocumentor\Reflection\Types\Array_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CurriculumVitaeRepository;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;



use Twig\Environment;
use function PHPUnit\Framework\throwException;


#[IsGranted("ROLE_STUDENT")]
#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
//    #[Route('/', name: 'profile')]
//    public function profile(Request $request, StudentRepository $sRepo): Response
//    {
//        $cv=$sRepo->findOneBy(['user'=> $this->getUser()]) ;
//        return $this->render('user/profile.html.twig',
//            [
////                'cv'=> $cv, //check thiiiis erreur
//                'form'=> null
//
//        ]);
//    }

    #[Route('/cvbuild', name: 'cvbuild')]
    public function cvbuild(Request $request,EntityManagerInterface $em ,StudentRepository $surepo, FileUploader $fileUploader): Response
    {
        $student=$surepo->findOneby(["user"=>$this->getUser()]);

        if($student->isJe()){
            return $this->render('user/cvbuild.html.twig');
        }else{
            $form=$this->createForm(UploadcvType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if($student->getLinkCv()){
                    unlink($student->getLinkCv());}
                $form->getData();
                /** @var UploadedFile $cv */
                $cv = $form->get('linkcv')->getData();
                $cvName = $fileUploader->upload($cv);
                $student->setLinkCv($cvName);

                $em->flush();
            
            }
            $cv=$student->getLinkCv();

            return $this->render('user/profile.html.twig',
            ['form'=> $form->createView(),
                'cv'=> $cv]
            );
        };

    }

    #[Route('/cv', name: 'cv')]
    public function viewprofile(Request $request,
                                CurriculumVitaeRepository $cvRepo,
                                StudentRepository $StuRepo,
                                ProjectRepository $pRepo,
                                Environment $twig,
                                EntityManagerInterface $em,
                                Pdf $pdf
                                ): Response
    {

        $user = $this->getUser();
        $student=$StuRepo->findOneBy(['user' => $user->getId()]);
        $cv=$cvRepo->findOneBy(['user' => $user->getId()]);
        if(is_null($cv)){
        $cv = new CurriculumVitae();
        $cv->setUser($this->getUser());

        $em->persist($cv);
        $em->flush();
            };



        $Edu=$cv->getEducationalExperiences();
        $Asso=$cv->getAssociativeExperiences();
        $Pro=$cv->getProfessionalExperiences();
        $proj=$pRepo->findBy(['cv'=> $cv]);
//        -------------------------------------------------------
//              GENERATING CV TO PDF
////
//        $html = $twig->render('user/Cv_print.html.twig', [
//            'student'=>$student,
//            'user'=>$user,
//            'cv'=> $cv,
//            'edu' => $Edu,
//            'pro' => $Pro,
//            'asso' => $Asso
//        ]);
//
//        $pdfPath = '../public/CVs/' . $student->getFirstName() . ' ' . $student->getLastName() . '.pdf';
//
//        $options = [
//
//            'enable-local-file-access' => true,
//
//        ];
//        $pdf->generateFromHtml($html, $pdfPath , $options);

//        ------------------------------------------------------------------

        return $this->render('user/showcv.html.twig', [
            'student'=>$student,
            'user'=>$user,
            'cv'=> $cv,
            'edu' => $Edu,
            'pro' => $Pro,
            'asso' => $Asso,
            'proj'=>$proj
        ]);
    }


    #[Route('/addcv', name: 'add_cv')]
    public function cv(Request $request, EntityManagerInterface $em): Response
    {
        $cv = new CurriculumVitae();
        $form = $this->createForm(CvType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $certificat =  array(array($form['Nomcertificats']->getData(),$form['Descriptionducertifs']->getData()));
            $cv->setCertificates($certificat);
            $cv->setUser($this->getUser());

            $em->persist($cv);
            $em->flush();
        }
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(), 'controller_name' => 'UserController',
        ]);
    }

    #[Route('/cv/addcertif', name: 'addcertificat')]
    public function addcertif(Request $request, CurriculumVitaeRepository $cvRepo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddcertificatType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $cv=$cvRepo->findOneBy(['user' => $user->getId()]);
        if ( is_null($cv)){
            $cv = new CurriculumVitae();
            $cv->setUser($this->getUser());
        };
        if ($form->isSubmitted() && $form->isValid()) {
            $certificat =  array($form['NomCertificat']->getData(),$form['Description']->getData());
            if ( is_null($cv)){
                $cv->setCertificates(array($certificat));
            }else{
                $cv->updateCertificates($certificat); //Correct the function update
            }
            $em->persist($cv);
            $em->flush();

            return $this->redirectToRoute('app_usercvbuild');
        }
    return $this->render('user/Addcertif.html.twig', [
        'form' => $form->createView()
    ]);
    }
    #[Route('/cv/addassoexp', name: 'addassoexp')]
    public function addassoexp(Request $request, CurriculumVitaeRepository $cvRepo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AssociativeExperienceType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $cv=$cvRepo->findOneBy(['user' => $user->getId()]);
        if(is_null($cv)){
            $cv=new CurriculumVitae();
            $cv->setUser($this->getUser());
            $em->persist($cv);
            $em->flush();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $ExpAsso=new AssociativeExperience();
            $ExpAsso->setDescription($form['description']->getData());
            $ExpAsso->setOrganization($form['organization']->getData());
            $ExpAsso->setStartdate($form['startdate']->getData());
            $ExpAsso->setEnddate($form['enddate']->getData());
            $ExpAsso->setCurriculumVitae($cv);
            $em->persist($ExpAsso);
            $em->flush();

            return $this->redirectToRoute('app_usercvbuild');
        }
        return $this->render('user/AddAssoExp.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'Associative experience controller',
        ]);
    }

    #[Route('/cv/addproexp', name: 'addproexp')]
    public function addproexp(Request $request, CurriculumVitaeRepository $cvRepo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProExperienceType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $cv=$cvRepo->findOneBy(['user' => $user->getId()]);
        if(is_null($cv)){
            $cv=new CurriculumVitae();
            $cv->setUser($this->getUser());
            $em->persist($cv);
            $em->flush();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $ExpPro= new ProfessionalExperience();
            $ExpPro->setType($form['type']->getData());
            $ExpPro->setPoste($form['poste']->getData());
            $ExpPro->setDescription($form['description']->getData());
            $ExpPro->setEntreprise($form['entreprise']->getData());
            $ExpPro->setStartdate($form['startdate']->getData());
            $ExpPro->setEnddate($form['enddate']->getData());
            $ExpPro->setCurriculumVitae($cv);
            $em->persist($ExpPro);
            $em->flush();

            return $this->redirectToRoute('app_usercvbuild');
        }
        return $this->render('user/AddProExp.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'Professional experience controller',
        ]);
    }
    #[Route('/cv/addeduexp', name: 'Addeduexp')]
    public function addeduexp(Request $request, CurriculumVitaeRepository $cvRepo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EduExpType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $cv=$cvRepo->findOneBy(['user' => $user->getId()]);
        if(is_null($cv)){
            $cv=new CurriculumVitae();
            $cv->setUser($this->getUser());
            $em->persist($cv);
            $em->flush();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $ExpEdu= new EducationalExperience();
            $ExpEdu->setUniversity($form['university']->getData());
            $ExpEdu->setDescription($form['description']->getData());
            $ExpEdu->setStartdate($form['startdate']->getData());
            $ExpEdu->setEnddate($form['enddate']->getData());
            $ExpEdu->setCurriclumVitae($cv);
            $em->persist($ExpEdu);
            $em->flush();

            return $this->redirectToRoute('app_usercvbuild');
        }
        return $this->render('user/AddEduExp.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'Educational experience controller',
        ]);
    }


    public function joinofferandentreprise(EntrepriseRepository $entRepo, JobOfferRepository $jobRepo):array
    {
        $e=$entRepo->findAll();
        $j=$jobRepo->findAll();

        $res=[];

        for($i=0; $i<count($e) ; $i++)
        {
            $ent=new entreprise();
            $ent= $e[$i];
            $offer=$ent->getJobOffers();
            $aux=array_merge($ent,$offer);
            array_push($res,$aux);
        }
        return $res;
    }

    #[Route('/catalogue', name: 'catalogue')]
    public function showcatalogue(Request $request, EntrepriseRepository $entRepo, JobOfferRepository $jobRepo): Response
    {

        return $this->render('user/catalogue.html.twig',
            [
                'offers' => $jobRepo->joinentreprise(),
            ]);
    }
    #[Route('/catalogue/{id}', name: 'offer')]
    public function offer(JobOffer $jobOffer, StudentRepository $studentRepository, Request $request, EntityManagerInterface $em , MailerInterface $mailer): Response
    {

        $user=$this->getUser();
        $student=$studentRepository->findOneBy(['user'=>$user]);
        $ent=$jobOffer->getEntreprise();
        $entrepriseEmail= $ent->getUser()->getEmail();
        if (in_array("ROLE_STUDENT", $user->getRoles())) {
            $ent->getJobOffers()[0]->setNbViews($ent->getJobOffers()[0]->getNbViews()+1);
            $em->persist($ent->getJobOffers()[0]);
            $em->flush();
        }


        $sujet=$this->createFormBuilder()
            ->add("sujet", TextType::class,['label' => 'Choisir le Référence du votre Projet PFE'])
            ->add("postuler", SubmitType::class)
            ->getForm();

        $sujet->handleRequest($request);
        if ($sujet->isSubmitted() && $sujet->isValid()) {
            $JobRequest = new JobRequest();
            $JobRequest->setEntreprise($ent);
            $JobRequest->setDate(new \DateTime('now'));
            $JobRequest->setRefsujet($sujet['sujet']->getData());
            $student->addJobRequest($JobRequest);

            $em->persist($JobRequest);
            $em->flush();


            $email = (new TemplatedEmail())
                ->from('mansouri.firas@esprit.tn')
                ->to($entrepriseEmail)
                ->subject('nouvelle candidature')
                ->htmlTemplate('emails/mail_template.html.twig');

            $mailer->send($email);


            return $this->redirectToRoute('app_usercatalogue');

        }
        return $this->render('user/offer.html.twig',
            [
                'offer' => $jobOffer,
                'ent'=> $ent,
                'student'=>$student,
                'sujet'=>$sujet->createView()
            ]);
    }

    #[Route('/postuler/{entreprise}', name: 'post')]
    public function post(Entreprise $entreprise, StudentRepository $studentRepository, EntityManagerInterface $em)
    {

        //popup didn't run - Tocheck
        return $this->redirectToRoute('app_usercatalogue');
    }

    #[Route('/candidature', name: 'candidature')]
    public function showCandidatures (StudentRepository $studentRepository, JobRequestRepository $JobRequestRepo)
    {
        $user=$this->getUser();
        $student=$studentRepository->findOneBy(['user'=>$user]);
        $cand=$JobRequestRepo->findBy(['Student' => $student]);
        return $this->render('user/candidature.html.twig',
            [
                'candidatures' => $cand
            ]);
    }

    #[Route('/editasso/{id}', name: 'editasso', methods: ['GET', 'POST'])]
    public function editasso (Request $request, AssociativeExperience $asso, AssociativeExperienceRepository $assoRepo ): Response
    {
        $form = $this->createForm(AssociativeExperienceType::class,$asso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assoRepo->save($asso, true);

            return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('user/edit.html.twig', [
            'ent' => $asso,
            'form' => $form->createView()
        ]
        );
    }

    #[Route('/editedu/{id}', name: 'editedu', methods: ['GET', 'POST'])]
    public function editedu (Request $request, EducationalExperience $edu, EducationalExperienceRepository $eduRepo ): Response
    {
        $form = $this->createForm(EduExpType::class,$edu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eduRepo->save($edu, true);

            return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('user/edit.html.twig', [
            'ent' => $edu,
            'form' => $form->createView()
        ]
        );
    }

    #[Route('/editpro/{id}', name: 'editpro', methods: ['GET', 'POST'])]
    public function editpro (Request $request, ProfessionalExperience $pro, ProfessionalExperienceRepository $prorepo ): Response
    {
        $form = $this->createForm(ProExperienceType::class,$pro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prorepo->save($pro, true);

            return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('user/edit.html.twig', [
                'ent' => $pro,
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/cv/addproject', name: 'Addproj')]
    public function addproj(Request $request, CurriculumVitaeRepository $cvRepo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjectType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $cv=$cvRepo->findOneBy(['user' => $user->getId()]);
        if(is_null($cv)){
            $cv=new CurriculumVitae();
            $cv->setUser($this->getUser());
            $em->persist($cv);
            $em->flush();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $p= new Project();
            $p=$form->getData();
            $p->setCv($cv);

            $em->persist($p);
            $em->flush();

            return $this->redirectToRoute('app_usercvbuild');
        }
        return $this->render('user/AddEduExp.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'Project',
        ]);
    }

    #[Route('/editproject/{id}', name: 'editproject', methods: ['GET', 'POST'])]
    public function editproject (Request $request, Project $pro, ProjectRepository $prorepo ): Response
    {
        $form = $this->createForm(ProjectType::class,$pro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prorepo->save($pro, true);

            return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('user/edit.html.twig', [
                'ent' => $pro,
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/deletepro/{id}', name: 'deletepro', methods: ['GET', 'POST'])]
    public function deletepro (Request $request, ProfessionalExperience $pro, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($pro);
        $entityManager->flush();

        return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/deleteproj/{id}', name: 'deleteproj', methods: ['GET', 'POST'])]
    public function deleteproj (Request $request, Project $pro, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($pro);
        $entityManager->flush();

        return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/deleteasso/{id}', name: 'deleteasso', methods: ['GET', 'POST'])]
    public function deleteasso (Request $request, AssociativeExperience $pro, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($pro);
        $entityManager->flush();

        return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/deleteedu/{id}', name: 'deleteedu', methods: ['GET', 'POST'])]
    public function deleteedu (Request $request, EducationalExperience $edu, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($edu);
        $entityManager->flush();

        return $this->redirectToRoute('app_usercv', [], Response::HTTP_SEE_OTHER);
    }



}