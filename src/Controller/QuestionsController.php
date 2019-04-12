<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsController extends AbstractController
{
    /**
     * @Route("activity/{id}/questions/new", name="activity_QCM_new")
     * @Route("activity/{id}/questions/{slug}/edit", name="activity_QCM_edit")
     */
    public function index($id, $slug = null, Request $request, ObjectManager $manager, ActivityRepository $activityRepository, QuestionsRepository $questionsRepository)
    {
        $question = $questionsRepository->findOneby(['id' => $slug]);

        if(!$question){
            $question = new Questions();
        }

        $activities = $activityRepository->findOneby(['id' => $id]);

        $form = $this->createForm(QuestionsType::class, $question);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $activities = $activityRepository->findOneby(['id' => $id]);

            $question->setActivity($activities);

            $manager->persist($question);

            $manager->flush();

            if(!$slug){
                return $this->redirectToRoute('activity_'. $activities->getType()->getName() .'_new', ['id' => $activities->getId()]);
            }else{
                $this->addFlash('success', 'Questions modifiées avec succès');
                return $this->redirectToRoute('activity_'. $activities->getType()->getName() , ['id' => $activities->getId()]);
            }


        }

        return $this->render('questions/new.html.twig', [
            'form_quest' => $form->createView(),
            'activity' => $activities,
            'question' => $question
        ]);
    }

    /**
     * @Route("activity/{id}/questions/{slug}/delete", name="activity_question_delete")
     */
    public function delete($id, $slug, QuestionsRepository $questionRepository, ObjectManager $manager){

        $question = $questionRepository->findOneBy(['id' => $slug]);

        $manager->remove($question);
        $manager->flush();


        return $this->redirectToRoute('activity_QCM', ['id' => $id]);
    }
}
