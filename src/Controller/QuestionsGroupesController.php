<?php

namespace App\Controller;

use App\Entity\QuestionsGroupes;
use App\Form\QuestionsGroupesType;
use App\Repository\ActivityRepository;
use App\Repository\QuestionsGroupesRepository;
use App\Repository\UserActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuestionsGroupesController extends AbstractController
{
    /**
     * @param ObjectManager $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("activity/{id}/regroupement/new", name="activity_regroupement_new")
     * @Route("activity/{id}/regroupement/{slug}/edit", name="activity_regroupement_edit")
     */
    public function questionsGroups($id, $slug = null, ObjectManager $manager, Request $request, ActivityRepository $activityRepository, QuestionsGroupesRepository $groupesRepository){

        $question = $groupesRepository->findOneby(['id' => $slug]);

        if(!$question){
            $question = new QuestionsGroupes();
        }

        $activities = $activityRepository->findOneby(['id' => $id]);

        $form = $this->createForm(QuestionsGroupesType::class, $question);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $question->setActivity($activities);

            $manager->persist($question);
            $manager->flush();

            if(!$slug){
                return $this->redirectToRoute('activity_'. $activities->getType()->getName() .'_new', ['id' => $activities->getId()]);
            }else{
                $this->addFlash('success', 'Questions modifiées avec succès');
                return $this->redirectToRoute('activity_'. $activities->getType()->getName(), ['id' => $activities->getId()]);
            }
        }

        return $this->render('questions_groupes/index.html.twig', [
            'form' => $form->createView(),
            'activity' => $activities
        ]);
    }

    /**
     * @param $id
     * @param Request $request
     * @param UserActivityRepository $userActivityRepository
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("{id}/verification", name="correction_groups")
     */
    public function correctionGroups($id, Request $request, UserActivityRepository $userActivityRepository, ObjectManager $manager){

        $data = utf8_encode($request->getContent());

        $json = json_decode($data);

        $user_activity = $userActivityRepository->findOneby(['user_id' => $this->getUser(), 'activity_id' => $id]);
        $user_activity->setPoint($json->point);
        $user_activity->setTotal($json->total);

        $manager->persist($user_activity);
        $manager->flush();

        //Permet de récupérer les données que je passe en ajax.
        $total = $json->total;


        return $this->json(['code' => 200, 'message' => $data], 200);
    }
}
