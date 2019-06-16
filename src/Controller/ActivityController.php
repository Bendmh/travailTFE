<?php

namespace App\Controller;


use App\Entity\Activity;
use App\Entity\ActivitySearch;
use App\Entity\User;
use App\Entity\UserActivity;
use App\Form\ActivitySearchType;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ReponseEleveAssociationRepository;
use App\Repository\ReponseEleveQCMRepository;
use App\Repository\UserActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActivityController
 * @package App\Controller
 *
 * Cette classe gère tout ce qui est lié aux activités (liste, création, modification et suppression)
 */
class ActivityController extends AbstractController
{
    /**
     * Route pour voir toutes les activités visibles de tous les professeurs
     *
     * @Route("/activity", name="activity")
     * @param ActivityRepository $activityRepository
     * @param Request $request
     * @return Response
     */
    public function showVisibleActivities(ActivityRepository $activityRepository, Request $request)
    {
        $search = new ActivitySearch();
        $form = $this->createForm(ActivitySearchType::class, $search);
        $form->handleRequest($request);
        $activities = $activityRepository->findActivitySearch($search);

        return $this->render('activity/activityList.html.twig', [
            'activites' => $activities,
            'current_menu' => 'activity',
            'perso' => false,
            'form_activity' => $form->createView()
        ]);
    }

    /**
     * Route permettant aux professeurs de voir ses activités et les gérer
     *
     * @param ActivityRepository $activityRepository
     * @return Response
     * @Route("/prof/activity/perso", name="activityPerso")
     */
    public function showTeacherActivities(ActivityRepository $activityRepository){

        $activities = $activityRepository->findBy(['created_by' => $this->getUser()]);

        return $this->render('activity/activityList.html.twig', [
            'activites' => $activities,
            'current_menu' => 'activity',
            'perso' => true
        ]);
    }

    /**
     * Route permettant la création et la modification des activités
     *
     * @Route("/prof/activity/new", name="new_activity")
     * @Route("/prof/activity/{activityId}/edit", name="edit_activity")
     * @param null $activityId
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newOrEditActivity($activityId = null, Request $request, ObjectManager $manager, ActivityRepository $activityRepository){

        $activity = $activityRepository->findOneBy(['id' => $activityId]);
        /** @var User $user */
        $user = $this->getUser();

        if(!$activity){
            $activity = new Activity();
        }
        elseif($activity->getCreatedBy() !== $user && $user->getTitre() !== 'ROLE_SUPER_ADMIN' ) {
            return $this->render('index/error.html.twig');
        }
        $type = $activity->getType();

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if($type){
                $activity->setType($type);
            }
            $creator = $activity->getCreatedBy();
            if(!$creator){
                $activity->setCreatedBy($this->getUser());
            }

            $manager->persist($activity);

            $manager->flush();

            //Après la création => formulaire vers la création de nouvelles questions
            if(!$activityId){
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() .'_new', ['activityId' => $activity->getId()]);
            }
            //sinon renvoie vers les activités du prof avec le message correspondant
            else {
                $this->addFlash('success', 'Activité modifiée avec succès');
                return $this->redirectToRoute('activityPerso');
            }


        }

        return $this->render('activity/new.html.twig', [
                'form_act' => $form->createView(),
                'current_menu' => 'activity',
                'activity' => $activity
        ]);
    }


    /**
     * Route permettant la suppression d'une activité
     *
     * @param Activity $activity
     * @param ObjectManager $manager
     * @Route("/prof/activity/{activityId}/delete", name="delete_activity")
     */
    public function deleteActivity($activityId, ActivityRepository $activityRepository, ObjectManager $manager, ReponseEleveAssociationRepository $eleveAssociationRepository, ReponseEleveQCMRepository $eleveQCMRepository){

        $user = $this->getUser();
        $activity = $activityRepository->findOneby(['id' => $activityId]);

        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }

        //selon le type d'activité, je dois supprimer les résultats des élèves correspondant à cette activité.
        switch ($activity->getType()->getName()){
            case \App\Entity\ActivityType::QCM_ACTIVITY:
                $reponses = $eleveQCMRepository->findBy(['activityId' => $activityId]);
                foreach ($reponses as $reponse){
                    $manager->remove($reponse);
                }
                break;
            case \App\Entity\ActivityType::ASSOCIATION_ACTIVITY:
                $reponses = $eleveAssociationRepository->findBy(['activityId' => $activityId]);
                foreach ($reponses as $reponse){
                    $manager->remove($reponse);
                }
                break;

        }

        $manager->remove($activity);

        $manager->flush();
        return $this->redirectToRoute('activityPerso');
    }

    /**
     * @Route("{id}/verification/qcm", name="verification_qcm")
     * @param $id
     * @param Request $request
     * @param QuestionsRepository $questionRepository
     * @param UserActivityRepository $userActivityRepository
     * @param ObjectManager $manager
     * @return Response
     */
    /*public function verification($id, Request $request, QuestionsRepository $questionRepository, UserActivityRepository $userActivityRepository, ObjectManager $manager){

        $point = 0;
        $total = 0;
        $questionPrecedente = '';
        $tab = $request->request->all();

        $user_activity = $userActivityRepository->findOneby(['user_id' => $this->getUser(), 'activity_id' => $id]);

        while ($reponse = current($tab)){

            $questionIS = str_replace("_", " ", substr(key($tab), 2 ));//je récupère la question
            $question = $questionRepository->findOneBy(['question' => $questionIS]);//je vais chercher les bonnes réponses liées à la question

            if($questionIS == $questionPrecedente){
                $idem = true;
            }else {
                $idem = false;
            }

            if($idem == false){
                $total = $total + $question->getPoints();
            }

            if(in_array($reponse, [$question->getBonneReponse1(), $question->getBonneReponse2(), $question->getBonneReponse3()])){
                $point++;
            }else {
                $point = $point-0.2;
            }
            next($tab);
            $questionPrecedente = $questionIS;
        }

        $user_activity->setPoint($point);
        $user_activity->setTotal($total);

        $manager->persist($user_activity);
        $manager->flush();

        return $this->render('activity/retour.html.twig', [
            'point' => $point,
            'total' => $total,
            'id' => $id
        ]);
    }*/
}
