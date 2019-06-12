<?php

namespace App\Controller;

use App\Entity\QuestionAudio;
use App\Form\QuestionAudioType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionAudioRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsAudioController extends AbstractController
{
    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/prof/activity/{activityId}/audio/new", name="activity_audio_new")
     * @Route("/prof/activity/{activityId}/audio/{questionAudioId}/edit", name="activity_audio_edit")
     */
    public function newOrEditQuestionAudio($activityId, $questionAudioId = null, Request $request, ObjectManager $manager, QuestionAudioRepository $audioRepository, ActivityRepository $activityRepository){

        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        $audio = $audioRepository->findOneBy(['id' => $questionAudioId]);

        if(!$audio){
            $audio = new QuestionAudio();
        }

        $form = $this->createForm(QuestionAudioType::class, $audio);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $audio->setActivity($activity);

            $manager->persist($audio);
            $manager->flush();
            return $this->redirectToRoute('activity_audio', ['activityId' => $activityId]);
        }

        return $this->render('questions_audio/audio.html.twig', [
            'form_audio' => $form->createView()
        ]);
    }

    /**
     * @param QuestionAudioRepository $audioRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/activity/{activityId}/audio", name="activity_audio")
     */
    public function audioActivity($activityId, ActivityRepository $activityRepository){

        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        $audio = $activity->getQuestionAudio();

        return $this->render('questions_audio/activity.html.twig', [
            'current_menu' => 'activity',
            'audio' => $audio,
            'activity' =>$activity
        ]);
    }
}
