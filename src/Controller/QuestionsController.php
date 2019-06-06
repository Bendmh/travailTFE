<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ReponseEleveQCMRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuestionsController
 * @package App\Controller
 *
 * Cette classe permet de gérer les questions de type QCM (création, modification et suppression)
 */
class QuestionsController extends AbstractController
{
    /**
     * Route permettant la création et la modification de questions (la question est liée à l'activité)
     *
     * @Route("/prof/activity/{activityId}/questions/new", name="activity_QCM_new")
     * @Route("/prof/activity/{activityId}/questions/{slug}/edit", name="activity_QCM_edit")
     * @param $activityId
     * @param null $slug
     * @param Request $request
     * @param ObjectManager $manager
     * @param ActivityRepository $activityRepository
     * @param QuestionsRepository $questionsRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newOrEditQuestionsQCM($activityId, $slug = null, Request $request, ObjectManager $manager, ActivityRepository $activityRepository, QuestionsRepository $questionsRepository)
    {
        $question = $questionsRepository->findOneby(['id' => $slug]);

        if(!$question){
            $question = new Questions();
        }
        $user = $this->getUser();

        $activity = $activityRepository->findOneby(['id' => $activityId]);

        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }

        $form = $this->createForm(QuestionsType::class, $question);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $activity = $activityRepository->findOneby(['id' => $activityId]);

            $question->setActivity($activity);

            $manager->persist($question);

            $manager->flush();

            if(!$slug){
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() .'_new', ['id' => $activity->getId()]);
            }else{
                $this->addFlash('success', 'Questions modifiées avec succès');
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() , ['id' => $activity->getId()]);
            }


        }

        return $this->render('questions/new.html.twig', [
            'form_quest' => $form->createView(),
            'activity' => $activity,
            'question' => $question
        ]);
    }

    /**
     * Route permettant la suppression de la question (la question est liée à l'activité)
     *
     * @Route("/prof/activity/{activityId}/questions/{slug}/delete", name="activity_QCM_delete")
     * @param $activityId
     * @param $slug
     * @param QuestionsRepository $questionRepository
     * @param ObjectManager $manager
     * @param ReponseEleveQCMRepository $eleveQCMRepository
     */
    public function deleteQuestionsQCM($activityId, $slug, QuestionsRepository $questionRepository, ObjectManager $manager, ReponseEleveQCMRepository $eleveQCMRepository, ActivityRepository $activityRepository){

        $user = $this->getUser();
        $activity = $activityRepository->findOneBy(['id' => $activityId]);
        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }
        $question = $questionRepository->findOneBy(['id' => $slug]);
        $reponses = $eleveQCMRepository->findBy(['questionId' => $slug]);

        foreach ($reponses as $reponse){
            $manager->remove($reponse);
        }
        $manager->flush();

        $manager->remove($question);
        $manager->flush();


        return $this->redirectToRoute('activity_QCM', ['id' => $activityId]);
    }
}
