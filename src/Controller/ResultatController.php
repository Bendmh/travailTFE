<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Entity\ReponseEleveAssociation;
use App\Entity\ReponseEleveBrainstorming;
use App\Entity\ReponseEleveQCM;
use App\Entity\ResultSearch;
use App\Entity\User;
use App\Form\ResultSearchType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionsGroupesRepository;
use App\Repository\QuestionsReponsesRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ReponseEleveAssociationRepository;
use App\Repository\ReponseEleveBrainstormingRepository;
use App\Repository\ReponseEleveQCMRepository;
use App\Repository\UserActivityRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResultatController
 * @package App\Controller
 *
 * Cette classe permet de gérer les résultats des activités
 */
class ResultatController extends AbstractController
{
    /**
     * Route permettant d'afficher les résultats de l'élève associé au professeur
     *
     * @Route("/prof/resultats", name="resultat")
     */
    public function resultatsTable(UserActivityRepository $userActivityRepository, Request $request)
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
     * Route permettant de d'afficher les résultats personnels de l'utilisateur
     *
     * @param UserActivityRepository $userActivityRepository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/points/{id}", name="resultatPerso")
     */
    public function resultatPerso($id, UserActivityRepository $userActivityRepository, Request $request){

        /** @var User $user */
        $user = $this->getUser();
        if($id != $user->getId()) {
            return $this->render('index/error.html.twig');
        }
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
     * Route permettant d'envoyer les erreurs que l'élève selon l'activité
     *
     * @Route("/point/{activityId}/{userId}", name="result_student_activity")
     */
    public function reponseEleve($userId, $activityId, ReponseEleveQCMRepository $reponseEleveQCMRepository, ActivityRepository $activityRepository, ReponseEleveAssociationRepository $eleveAssociationRepository){
        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        switch($activity->getType()->getName()){
            case ActivityType::QCM_ACTIVITY:
                $responseEleveQCMList = $reponseEleveQCMRepository->findBy(['userId' => $userId, 'activityId' => $activityId]);

                if(empty($responseEleveQCMList)){
                    $template = 'Ce n\'était pas encore implémenté ou il n\' a pas d\'erreur';
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
                    $template = 'Ce n\'était pas encore implémenté ou il n\' a pas d\'erreur';
                }
                else{
                    $user = $responseEleveAssociationList[0]->getUserId();

                    $template = $this->renderView('resultat/resultPersoAssociation.html.twig', [
                        'responseEleveList' => $responseEleveAssociationList,
                    ]);
                }
                break;
        }

        $response = new Response($template, 200);
        //$response->headers->set('Content-Type', 'application/json');
        return $response;

    }


    /**
     * Route permettant de corriger l'activié selon son type
     *
     * @param $activityId
     * @param Request $request
     * @param UserActivityRepository $userActivityRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("{activityId}/verification", name="correction_groups")
     */
    public function verificationActivity($activityId, Request $request, ActivityRepository $activityRepository, QuestionsRepository $questionsRepository, UserActivityRepository $userActivityRepository, ObjectManager $manager, ReponseEleveQCMRepository $reponseEleveQCMRepository, ReponseEleveAssociationRepository $eleveAssociationRepository, QuestionsReponsesRepository $questionsReponsesRepository, QuestionsGroupesRepository $groupesRepository){

        $data = $request->getContent();

        $json = json_decode($data);

        $user = $this->getUser();

        $point = 0;

        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        $user_activity = $userActivityRepository->findOneby(['user_id' => $this->getUser(), 'activity_id' => $activityId]);
        $user_activity->setTotal($json->total);

        $responseList = $json->response;

        // enregistrement des mauvaises réponses de l'élève
        switch ($activity->getType()->getName()){
            case ActivityType::QCM_ACTIVITY :
                $responseEleveQCMList = $reponseEleveQCMRepository->findBy(['userId' => $user->getId(), 'activityId' => $activityId]);
                foreach ($responseEleveQCMList as $response){
                    $manager->remove($response);
                }
                $manager->flush();
                foreach ($responseList as $response){
                    $questionId = $questionsRepository->findOneBy(['id' => $response->questionId]);
                    if($questionId->getBonneReponse1() == $response->value || $questionId->getBonneReponse2() == $response->value || $questionId->getBonneReponse3() == $response->value){
                        $point++;
                    }
                    else{
                        $point--;
                        $reponseEleveQCM = new ReponseEleveQCM();
                        $activityId = $activityRepository->findOneBy(['id' => $activityId]);
                        $reponseEleveQCM->setActivityId($activityId);
                        $reponseEleveQCM->setUserId($user);
                        $reponseEleveQCM->setQuestionId($questionId);
                        $reponseEleveQCM->setReponse($response->value);
                        $manager->persist($reponseEleveQCM);
                    }
                }
                if(is_null($user_activity->getPoint()) || $user_activity->getPoint() < $point){
                    $user_activity->setPoint($point);
                }
                break;
            case ActivityType::ASSOCIATION_ACTIVITY :
                //je supprime les réponses déjà existantes
                $responseEleveAssociationList = $eleveAssociationRepository->findBy(['userId' => $user->getId(), 'activityId' => $activityId]);
                foreach ($responseEleveAssociationList as $response){
                    $manager->remove($response);
                }
                $manager->flush();

                foreach($responseList as $response){
                    $reponseDB = $questionsReponsesRepository->findOneBy(['id' => $response->reponse]);
                    $groupeDB = $groupesRepository->findOneBy(['id' => $response->groupe]);
                    if($reponseDB->getQuestion()->getId() == $response->groupe){
                        $point++;
                    }
                    else{
                        $reponseEleveAssociation = new ReponseEleveAssociation();
                        $activityId = $activityRepository->findOneBy(['id' => $activityId]);
                        $reponseEleveAssociation->setActivityId($activityId);
                        $reponseEleveAssociation->setUserId($user);
                        $reponseEleveAssociation->setGroupe($groupeDB->getName());
                        $reponseEleveAssociation->setReponse($reponseDB->getName());
                        $manager->persist($reponseEleveAssociation);
                    }
                }
                if(is_null($user_activity->getPoint()) || $user_activity->getPoint() < $point){
                    $user_activity->setPoint($point);
                }
                break;
        }

        $manager->persist($user_activity);
        $manager->flush();

        $response = json_encode(['point' => $point, 'total' => $json->total]);

        //Permet de récupérer les données que je passe en ajax.
        //$total = $json->total;


        return $this->json(['code' => 200, 'message' => $response], 200);
    }

    /**
     * Route permettant au prof de voir les résultats de son brainstorming
     *
     * @param $activityId
     * @param ReponseEleveBrainstormingRepository $eleveBrainstormingRepository
     * @param ActivityRepository $activityRepository
     * @return Response
     * @Route("/prof/brainstorming/{activityId}/result", name="brainstorming_result")
     */
    public function resultBrainstorming($activityId, ReponseEleveBrainstormingRepository $eleveBrainstormingRepository, ActivityRepository $activityRepository){
        $user = $this->getUser();
        $activity = $activityRepository->findOneBy(['id' => $activityId]);
        $reponsesEleves = $eleveBrainstormingRepository->findBy(['activity' => $activityId]);

        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }

        return $this->render('resultat/resultBrainstorming.html.twig', [
            'responsesEleves' => $reponsesEleves,
            'activity' => $activity,
            'current_menu' => 'resultat'
        ]);
    }


}
