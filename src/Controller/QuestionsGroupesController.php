<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Entity\QuestionsGroupes;
use App\Entity\ReponseEleveAssociation;
use App\Entity\ReponseEleveQCM;
use App\Form\QuestionsGroupesType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Repository\QuestionsGroupesRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ReponseEleveAssociationRepository;
use App\Repository\ReponseEleveQCMRepository;
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
     * @Route("activity/{id}/association/new", name="activity_association_new")
     * @Route("activity/{id}/association/{slug}/edit", name="activity_association_edit")
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
    public function correctionGroups($id, Request $request, ActivityRepository $activityRepository, QuestionsRepository $questionsRepository, UserActivityRepository $userActivityRepository, ObjectManager $manager, ReponseEleveQCMRepository $reponseEleveQCMRepository, ActivityTypeRepository $activityTypeRepository, ReponseEleveAssociationRepository $eleveAssociationRepository){

        $data = utf8_encode($request->getContent());

        $json = json_decode($data);

        $user = $this->getUser();
        $typeQCM = $activityTypeRepository->findOneBy(['name' => ActivityType::QCM_ACTIVITY]);
        $typeAssociation = $activityTypeRepository->findOneBy(['name' => ActivityType::ASSOCIATION_ACTIVITY]);

        $activity = $activityRepository->findOneBy(['id' => $id]);

        $user_activity = $userActivityRepository->findOneby(['user_id' => $this->getUser(), 'activity_id' => $id]);
        $user_activity->setPoint($json->point);
        $user_activity->setTotal($json->total);

        $responseList = $json->response;

        //Si l'activité est du type QCM on enregistre les résultats (pour le moment)
        if($activity->getType()->getId() == $typeQCM->getId()){

            //je supprime les réponses déjà existantes
            $responseEleveQCMList = $reponseEleveQCMRepository->findBy(['userId' => $user->getId(), 'activityId' => $id]);
            foreach ($responseEleveQCMList as $response){
                $manager->remove($response);
            }

            foreach ($responseList as $response){
                $reponseEleveQCM = new ReponseEleveQCM();
                $activityId = $activityRepository->findOneBy(['id' => $response->activityId]);
                $reponseEleveQCM->setActivityId($activityId);
                $reponseEleveQCM->setUserId($user);
                $questionId = $questionsRepository->findOneBy(['id' => $response->questionId]);
                $reponseEleveQCM->setQuestionId($questionId);
                $reponseEleveQCM->setReponse($response->value);
                $manager->persist($reponseEleveQCM);
            }
        }
        //Si elle est de type association
        elseif ($activity->getType()->getId() == $typeAssociation->getId()){

            //je supprime les réponses déjà existantes
            $responseEleveAssociationList = $eleveAssociationRepository->findBy(['userId' => $user->getId(), 'activityId' => $id]);
            foreach ($responseEleveAssociationList as $response){
                $manager->remove($response);
            }
            $manager->flush();

            foreach($responseList as $response){
                $reponseEleveAssociation = new ReponseEleveAssociation();
                $activityId = $activityRepository->findOneBy(['id' => $id]);
                $reponseEleveAssociation->setActivityId($activityId);
                $reponseEleveAssociation->setUserId($user);
                $reponseEleveAssociation->setGroupe($response->groupe);
                $reponseEleveAssociation->setReponse($response->reponse);
                $manager->persist($reponseEleveAssociation);
            }
        }


        $manager->persist($user_activity);
        $manager->flush();

        //Permet de récupérer les données que je passe en ajax.
        //$total = $json->total;


        return $this->json(['code' => 200, 'message' => $data], 200);
    }
}
