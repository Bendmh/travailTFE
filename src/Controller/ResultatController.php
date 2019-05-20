<?php

namespace App\Controller;

use App\Entity\ResultSearch;
use App\Entity\User;
use App\Form\ResultSearchType;
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
    public function reponseEleve($userId, $activityId, ReponseEleveQCMRepository $reponseEleveQCMRepository){
        $responseEleveQCMList = $reponseEleveQCMRepository->findBy(['userId' => $userId, 'activityId' => $activityId]);

        $user = $responseEleveQCMList[0]->getUserId();
        $activity = $responseEleveQCMList[0]->getActivityId();

        $template = $this->renderView('resultat/resultPerso.html.twig', [
            'responseEleveList' => $responseEleveQCMList,
            'activity' => $activity,
            'user' => $user
        ]);
        $json = json_encode($template);
        $response = new Response($json, 200);
        //$response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}
