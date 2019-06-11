<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Entity\Brainstorming;
use App\Entity\ReponseEleveBrainstorming;
use App\Entity\User;
use App\Form\BrainstormingType;
use App\Repository\ActivityRepository;
use App\Repository\BrainstormingRepository;
use App\Repository\ReponseEleveBrainstormingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BrainstormingController
 * @package App\Controller
 *
 * Cette classe permet de gérer les questions de type brainstorming (création, modification, suppression et page des questions)
 */
class BrainstormingController extends AbstractController
{
    /**
     * Route permettant la création et la modification du brainstorming
     *
     * @Route("/prof/activity/{activityId}/brainstorming/new", name="activity_brainstorming_new")
     * @Route("/prof/activity/{activityId}/brainstorming/{brainstormingId}/edit", name="activity_brainstorming_edit")
     */
    public function newOrEditBrainstorming($activityId, $brainstormingId = null, BrainstormingRepository $brainstormingRepository, ActivityRepository $activityRepository ,Request $request, ObjectManager $manager){
        $brainstorming = $brainstormingRepository->findOneBy(['id' => $brainstormingId]);
        $user = $this->getUser();
        if(!$brainstorming){
            $brainstorming = new Brainstorming();
        }

        $activity = $activityRepository->findOneby(['id' => $activityId]);
        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }
        $form = $this->createForm(BrainstormingType::class, $brainstorming);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $activity = $activityRepository->findOneby(['id' => $activityId]);

            $brainstorming->setActivity($activity);

            $manager->persist($brainstorming);
            $manager->flush();

            if($brainstormingId){
                $this->addFlash('success', 'Questions modifiées avec succès');
            }
            return $this->redirectToRoute('activity_'. $activity->getType()->getName() , ['activityId' => $activity->getId()]);
        }

        return $this->render('brainstorming/new.html.twig', [
            'form_brainstorming' => $form->createView(),
            'activity' => $activity,
            'brainstorming' => $brainstorming
        ]);
    }

    /**
     * Route permettant à l'utilisateur d'effectuer l'activité
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/activity/{activityId}/brainstorming", name="activity_brainstorming")
     */
    public function activityBrainstorming($activityId, ActivityRepository $activityRepository) {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('error', 'Il faut se connecter pour faire l\'activité');
            return $this->redirectToRoute('activity');
        }

        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        $brainstorming = $activity->getQuestionBrainstorming();

        return $this->render('brainstorming/activity.html.twig', [
            'brainstorming' => $brainstorming,
            'activity' => $activity
        ]);
    }

    /**
     * Route affichant les brainstorming du professeur
     *
     * @Route("/prof/brainstorming/list", name="list_brainstorming")
     */
    public function listBrainstorming(ActivityRepository $activityRepository){
        $userId = $this->getUser()->getId();
        $activityBrainstormingTeacher = $activityRepository->activityByTypeAndByTeacher($userId, ActivityType::BRAINSTORMING_ACTIVITY);

        return $this->render('brainstorming/list.html.twig', [
            'activityBrainstorming' => $activityBrainstormingTeacher,
            'current_menu' => 'resultat'
        ]);
    }

    /**
     * Route permettant de recevoir les réponses du participant au brainstorming
     *
     * @param $activityId
     * @Route("/brainstorming/{activityId}/answer", name="answer_brainstorming")
     */
    public function answerFromBrainstorming($activityId, ActivityRepository $activityRepository, Request $request, ReponseEleveBrainstormingRepository $eleveBrainstormingRepository, ObjectManager $manager){
        $data = $request->getContent();

        $json = json_decode($data);
        /** @var User $user */
        $user = $this->getUser();
        $activity = $activityRepository->findOneBy(['id' => $activityId]);

        $responseEleve = $eleveBrainstormingRepository->findOneBy(['activity' => $activity->getId(), 'user' => $user->getId()]);

        if($responseEleve){
            $message = '<div class="alert alert-danger">Une seule participation possible</div>';
        }
        else{
            $message = '<div class="alert alert-success">Merci d\'avoir participé</div>';
            $responseList = $json->response;
            foreach ($responseList as $response){
                $responseEleve = new ReponseEleveBrainstorming();
                $responseEleve->setActivity($activity);
                $responseEleve->setUser($user);
                $responseEleve->setAnswer($response->value);
                $manager->persist($responseEleve);
            }
            $manager->flush();
        }

        return $this->json(['code' => 200, 'message' => $message], 200);
    }
}
