<?php

namespace App\Controller;


use App\Entity\Activity;
use App\Entity\ActivitySearch;
use App\Entity\UserActivity;
use App\Form\ActivitySearchType;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionsRepository;
use App\Repository\UserActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    /**
     * @Route("/activity", name="activity")
     * @param ActivityRepository $activityRepository
     * @param Request $request
     * @return Response
     */
    public function showActivities(ActivityRepository $activityRepository, Request $request)
    {
        $search = new ActivitySearch();
        $form = $this->createForm(ActivitySearchType::class, $search);
        $form->handleRequest($request);
        $activities = $activityRepository->findActivitySearch($search);

        return $this->render('activity/index.html.twig', [
            'activites' => $activities,
            'current_menu' => 'activity',
            'perso' => false,
            'form_activity' => $form->createView()
        ]);
    }

    /**
     * @param ActivityRepository $activityRepository
     * @return Response
     * @Route("/activity/perso", name="activityPerso")
     */
    public function showTeacherActivities(ActivityRepository $activityRepository){

        $activities = $activityRepository->findBy(['created_by' => $this->getUser()]);

        return $this->render('activity/index.html.twig', [
            'activites' => $activities,
            'current_menu' => 'activity',
            'perso' => true
        ]);
    }

    /**
     * @Route("/activity/new", name="new_activity")
     * @Route("/activity/{id}/edit", name="edit_activity")
     * @param null $id
     * @param Activity|null $activity
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create($id = null, Activity $activity = null, Request $request, ObjectManager $manager){

        if(!$activity){
            $activity = new Activity();
        }

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $activity->setCreatedBy($this->getUser());

            $manager->persist($activity);

            $manager->flush();

            if(!$id){
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() .'_new', ['id' => $activity->getId()]);
            }else {
                $this->addFlash('success', 'Activité modifiée avec succès');
                return $this->redirectToRoute('activity_' . $activity->getType()->getName(), ['id' => $activity->getId()]);
            }


        }



        return $this->render('activity/new.html.twig', [
                'form_act' => $form->createView(),
                'activity' => $activity
        ]);
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
    public function verification($id, Request $request, QuestionsRepository $questionRepository, UserActivityRepository $userActivityRepository, ObjectManager $manager){

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
    }

    /**
     * @param Activity $activity
     * @param ObjectManager $manager
     * @Route("/activity/{id}/delete", name="activity_delete")
     */
    public function delete($id, ActivityRepository $activityRepository, ObjectManager $manager){

        $activities = $activityRepository->findOneby(['id' => $id]);
        $manager->remove($activities);
        $manager->flush();
        return $this->redirectToRoute('activityPerso');
    }
}
