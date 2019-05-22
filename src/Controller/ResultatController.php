<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Entity\ResultSearch;
use App\Entity\User;
use App\Form\ResultSearchType;
use App\Repository\ActivityRepository;
use App\Repository\ReponseEleveAssociationRepository;
use App\Repository\ReponseEleveQCMRepository;
use App\Repository\UserActivityRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    /**
     * @Route("/resultats", name="resultat")
     */
    public function index(UserActivityRepository $userActivityRepository,Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $classes = $user->getClasses();
        $classesTableau = [];
        foreach ($classes as $classe){
            array_push($classesTableau, $classe->getNom());
        }
        $search = new ResultSearch();
        $form = $this->createForm(ResultSearchType::class, $search);
        $form->handleRequest($request);
        $user_activity = $userActivityRepository->findAllVisibleQuery($search, $classesTableau);
        //$user_activity = $userActivityRepository->findAll();
        //$user_activity = $userActivityRepository->myFind(31);

        return $this->render('resultat/index.html.twig', [
            'current_menu' => 'resultat',
            'user_activity' => $user_activity,
            'form_result' => $form->createView()
        ]);
    }

    /**
     * @param UserActivityRepository $userActivityRepository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/points/{id}", name="resultatPerso")
     */
    public function resultatPerso($id = null, UserActivityRepository $userActivityRepository, Request $request){

        $search = new ResultSearch();
        $form = $this->createForm(ResultSearchType::class, $search);
        $form->handleRequest($request);
        $user_activity = $userActivityRepository->findBy(['user_id' => $id]);

        return $this->render('resultat/index.html.twig', [
            'current_menu' => 'mesResultat',
            'user_activity' => $user_activity,
            'form_result' => $form->createView()
        ]);
    }

    /**
     * @Route("/resultat/{activityId}/{userId}", name="result_student_activity")
     */
    public function reponseEleveQCM($userId, $activityId, ReponseEleveQCMRepository $reponseEleveQCMRepository, ActivityRepository $activityRepository, ReponseEleveAssociationRepository $eleveAssociationRepository){
        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        switch($activity->getType()->getName()){
            case ActivityType::QCM_ACTIVITY:
                $responseEleveQCMList = $reponseEleveQCMRepository->findBy(['userId' => $userId, 'activityId' => $activityId]);

                if(empty($responseEleveQCMList)){
                    $template = 'Ce n\'était pas encore implémenté';
                }
                else{
                    $user = $responseEleveQCMList[0]->getUserId();

                    $template = $this->renderView('resultat/resultPersoQCM.html.twig', [
                        'responseEleveList' => $responseEleveQCMList,
                        'activity' => $activity,
                        'user' => $user
                    ]);
                }
                break;
            case ActivityType::ASSOCIATION_ACTIVITY :
                $responseEleveAssociationList = $eleveAssociationRepository->findBy(['userId' => $userId, 'activityId' => $activityId]);
                if(empty($responseEleveAssociationList)){
                    $template = 'Il n\'y a pas d\'erreur';
                }
                else{
                    $user = $responseEleveAssociationList[0]->getUserId();

                    $template = $this->renderView('resultat/resultPersoAssociation.html.twig', [
                        'responseEleveList' => $responseEleveAssociationList,
                    ]);
                }
                break;
        }

        $response = new Response('test', 200);
        $json = json_encode($template);
        $response = new Response($json, 200);
        //$response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}
