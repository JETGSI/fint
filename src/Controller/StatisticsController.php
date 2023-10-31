<?php

namespace App\Controller;

use App\Entity\CurriculumVitae;
use App\Entity\Entreprise;
use App\Entity\JobInterview;
use App\Entity\JobOffer;
use App\Entity\JobRequest;
use App\Entity\Student;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StatisticsController extends AbstractController
{

    #[Route('/Admin-dashboard', name: 'admin_dashboard')]
    public function AdminStatistics(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subscribedStudents = $entityManager->getRepository(Student::class)->count([]);
        $completedProfiles = $entityManager->getRepository(CurriculumVitae::class)->count([]);
        $nbEntreprises = $entityManager->getRepository(Entreprise::class)->count([]);
        $nbJobOffer = $entityManager->getRepository(JobOffer::class)->count([]);
        $nbJobInterview = $entityManager->getRepository(JobInterview::class)->count([]);
        $nbJobRequest = $entityManager->getRepository(JobRequest::class)->count([]);
        $AllJobOffers = $entityManager->getRepository(JobOffer::class)->findAll();

        $TotalNbViews = 0;
        $minViews = PHP_INT_MAX;
        $maxViews = 0;
        
        foreach ($AllJobOffers as $jobOffer) {
            $views = $jobOffer->getNbViews();
            $TotalNbViews += $views;

            if ($views < $minViews) {
                $minViews = $views;
            }
            if ($views > $maxViews) {
                $maxViews = $views;
            }
        }
        
        $moyNbViews= 0;
        if($nbJobOffer!=0){
            $moyNbViews = number_format($TotalNbViews / $nbJobOffer, 2);
        }

        // dd($TotalNbViews, $minViews, $maxViews);

        $nbJEStudents = $entityManager->getRepository(Student::class)
        ->createQueryBuilder('s')
        ->select('COUNT(s.id)')
        ->where('s.je = true')
        ->getQuery()
        ->getSingleScalarResult();



        $linkedinProfiles = $entityManager->getRepository(student::class)->createQueryBuilder('s')
        ->select('COUNT(s.id)')
        ->where('s.linkedinLink IS NOT NULL')
        ->getQuery()
        ->getSingleScalarResult();

        // $connection = $entityManager->getConnection();
        // $sql = '
        //     SELECT DATE(u.created_at) as createdAt, COUNT(u.id) as studentCount
        //     FROM student s INNER JOIN user u on u.id = s.user_id
        //     WHERE u.roles LIKE "%ROLE_STUDENT%"
        //     GROUP BY createdAt
        //     ORDER BY createdAt ASC
        // ';
        // $statement = $connection->prepare($sql);
        // $results = $statement->execute()->fetchAll();
        // $dates = [];
        // $studentCounts = [];
        // foreach ($results as $result) {
        //     $dates[] = $result['createdAt']->format('Y-m-d');
        //     $studentCounts[] = (int) $result['studentCount'];
        // }
        // dd($results);


        return $this->render('admin/Dashboard.html.twig',[
        'subscribedStudents' => $subscribedStudents,
        'completedProfiles' => $completedProfiles,
        'linkedinProfiles' => $linkedinProfiles,
        'nbJEStudents' => $nbJEStudents,
        'nbEntreprises' => $nbEntreprises,
        'nbJobOffer' => $nbJobOffer,
        'nbJobInterview' => $nbJobInterview,
        'nbJobRequest' => $nbJobRequest,
        'TotalNbViews' => $TotalNbViews,
        'minViews' => $minViews,
        'maxViews' => $maxViews,
        'moyNbViews' => $moyNbViews,
        ]);
    }

    #[Route('/Entreprise-dashboard', name: 'entreprise_dashboard')]
    public function EntrepriseStatistics(Request $request, EntityManagerInterface $entityManager): Response
    {

        $subscribedStudents = $entityManager->getRepository(Student::class)->count([]);
        $completedProfiles = $entityManager->getRepository(CurriculumVitae::class)->count([]);
        $nbEntreprises = $entityManager->getRepository(Entreprise::class)->count([]);
        $nbJobOffer = $entityManager->getRepository(JobOffer::class)->count([]);
        $nbJobInterview = $entityManager->getRepository(JobInterview::class)->count([]);
        $nbJobRequest = $entityManager->getRepository(JobRequest::class)->count([]);
        $AllJobOffers = $entityManager->getRepository(JobOffer::class)->findAll();
        
        $userId=$this->getUser()->getId();
        $entreprise = $entityManager->getRepository(Entreprise::class)->findEntrepriseByUserId($userId);

        //get number of views of job Offer of the connected entreprise
        $nbViewsOfJobOfferOfConnectedEntreprise= $entreprise->getJobOffers()[0]->getNbViews();

        $TotalNbViews = 0;
        $php_int_max= PHP_INT_MAX;
        $minViews = $php_int_max;
        $maxViews = 0;
        
        foreach ($AllJobOffers as $jobOffer) {
            $views = $jobOffer->getNbViews();
            $TotalNbViews += $views;

            if ($views < $minViews) {
                $minViews = $views;
            }
            if ($views > $maxViews) {
                $maxViews = $views;
            }
        }
        if($minViews=$php_int_max){
            $minViews =0;
        }
        
        $moyNbViews= 0;
        if($nbJobOffer!=0){
            $moyNbViews = number_format($TotalNbViews / $nbJobOffer, 2);
        }

        // dd($TotalNbViews, $minViews, $maxViews);

        $nbJEStudents = $entityManager->getRepository(Student::class)
        ->createQueryBuilder('s')
        ->select('COUNT(s.id)')
        ->where('s.je = true')
        ->getQuery()
        ->getSingleScalarResult();



        $linkedinProfiles = $entityManager->getRepository(student::class)->createQueryBuilder('s')
        ->select('COUNT(s.id)')
        ->where('s.linkedinLink IS NOT NULL')
        ->getQuery()
        ->getSingleScalarResult();

        return $this->render('entreprise/Dashboard.html.twig',[
        'subscribedStudents' => $subscribedStudents,
        'completedProfiles' => $completedProfiles,
        'linkedinProfiles' => $linkedinProfiles,
        'nbJEStudents' => $nbJEStudents,
        'nbEntreprises' => $nbEntreprises,
        'nbJobOffer' => $nbJobOffer,
        'nbJobInterview' => $nbJobInterview,
        'nbJobRequest' => $nbJobRequest,
        'TotalNbViews' => $TotalNbViews,
        'minViews' => $minViews,
        'maxViews' => $maxViews,
        'moyNbViews' => $moyNbViews,
        'nbViewsOfJobOfferOfConnectedEntreprise' => $nbViewsOfJobOfferOfConnectedEntreprise,
        ]);
    }

}