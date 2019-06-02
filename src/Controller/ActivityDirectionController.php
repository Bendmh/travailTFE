<?php
/**
 * Created by PhpStorm.
 * User: B
 * Date: 29-03-19
 * Time: 20:12
 */

namespace App\Controller;


use App\Entity\ReponseSondage;
use App\Entity\User;
use App\Entity\UserActivity;
use App\Form\QuestionSondageSelectType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionSondageRepository;
use App\Repository\QuestionsReponsesRepository;
use App\Repository\ReponseSondageRepository;
use App\Repository\UserActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActivityDirectionController
 * @package App\Controller
 *
 * Cette classe permet de rediriger vers le bon type d'activité lorsque l'élève veut accomplir une activité
 */
class ActivityDirectionController extends AbstractController
{
    /**
     * Route renvoyant les données utiles pour un QCM
     *
     * @Route("/activity/{id}/qcm", name="activity_QCM")
     * @param $id
     * @param ActivityRepository $activityRepository
     * @param ObjectManager $manager
     * @param UserActivityRepository $userActivityRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function activityQCM($id, ActivityRepository $activityRepository, ObjectManager $manager, UserActivityRepository $userActivityRepository){

        $user = $this->getUser();

        if(!$user){
            $this->addFlash('error', 'Il faut se connecter pour faire l\'activité');

            return $this->redirectToRoute('activity');
        }

        $activity = $activityRepository->findOneby(['id' => $id]);

        $user_activity = $userActivityRepository->findOneby(['user_id' => $user, 'activity_id' => $activity->getId()]);

        if(!$user_activity){
            $user_activity = new UserActivity();
        }

        $user_activity->setUserId($user);
        $user_activity->setActivityId($activity);

        $manager->persist($user_activity);
        $manager->flush();


        $tab=['question.bonneReponse1', 'question.bonneReponse2', 'question.bonneReponse3', 'question.mauvaiseReponse1', 'question.mauvaiseReponse2', 'question.mauvaiseReponse3'];

        return $this->render('activity/activity.html.twig', [
            'activity' => $activity,
            'current_menu' => 'activity',
            'tab' => $tab
        ]);
    }

    /**
     * Route renvoyant les données utiles pour l'association
     *
     * @Route("/activity/{id}/association", name="activity_association")
     * @param $id
     * @param ActivityRepository $activityRepository
     * @param ObjectManager $manager
     * @param UserActivityRepository $userActivityRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function activityAssociation($id, ActivityRepository $activityRepository, QuestionsReponsesRepository $questionsReponsesRepository, ObjectManager $manager, UserActivityRepository $userActivityRepository){

        $user = $this->getUser();

        if(!$user){
            $this->addFlash('error', 'Il faut se connecter pour faire l\'activité');

            return $this->redirectToRoute('activity');
        }

        $activity = $activityRepository->findOneby(['id' => $id]);

        $answers = $questionsReponsesRepository->findAnswerByActivity($id);

        $user_activity = $userActivityRepository->findOneby(['user_id' => $user, 'activity_id' => $activity->getId()]);

        if(!$user_activity){
            $user_activity = new UserActivity();
        }

        $user_activity->setUserId($user);
        $user_activity->setActivityId($activity);

        $manager->persist($user_activity);
        $manager->flush();

        return $this->render('questions_groupes/activity.html.twig', [
            'activity' => $activity,
            'current_menu' => 'activity',
            'answers' => $answers
        ]);
    }

    /**
     * Route renvoyant les données utiles pour un sondage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/activity/{id}/sondage", name="activity_sondage")
     */
    public function activitySondage($id, ReponseSondageRepository $reponseSondageRepository, ActivityRepository $activityRepository, ObjectManager $manager, QuestionSondageRepository $questionSondageRepository, Request $request){

        /** @var User $user */
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('error', 'Il faut se connecter pour faire l\'activité');
            return $this->redirectToRoute('activity');
        }

        $activity = $activityRepository->findOneby(['id' => $id]);
        $questionSondageId = $activity->getQuestionSondage()->getId();

        $questionSondage = $questionSondageRepository->findOneBy(['id' => $questionSondageId]);

        //Cette étape permet de vérifier si l'utilisateur a déjà répondu au sondage.
        $reponseSondage = $reponseSondageRepository->findOneBy(['user' => $user->getId(), 'questionSondage' => $questionSondage->getId()]);

        if(!is_null($reponseSondage)){
            $this->addFlash('error', 'Vous avez déjà répondu à ce sondage !');
            return $this->redirectToRoute('activity');
        }

        $form = $this->createForm(QuestionSondageSelectType::class, $questionSondage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //récupération de la réponse sélectionner du côté client
            $reponseRequest = $request->request->all()["question_sondage_select"]["repTest"];
            if($activity->getCreatedBy() === $user){
                $this->addFlash('error', 'En tant que créateur, impossible de voter');
            }
            else{
                $reponseSondage = new ReponseSondage();
                $reponseSondage->setQuestionSondage($questionSondage);
                $reponseSondage->setResponse($reponseRequest);
                $reponseSondage->setUser($user);

                $manager->persist($reponseSondage);
                $manager->flush();
                $this->addFlash('success', 'Merci d\'avoir participé à ce sondage');
        }
            return $this->redirectToRoute('activity');
        }

        return $this->render('question_sondage/sondage.html.twig', [
            'form_quest' => $form->createView(),
            'current_menu' => 'activity',
            'activity' => $activity
        ]);
    }
}