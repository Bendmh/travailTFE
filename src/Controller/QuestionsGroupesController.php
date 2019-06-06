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
use App\Repository\QuestionsReponsesRepository;
use App\Repository\QuestionsRepository;
use App\Repository\ReponseEleveAssociationRepository;
use App\Repository\ReponseEleveQCMRepository;
use App\Repository\UserActivityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuestionsGroupesController
 * @package App\Controller
 *
 * Cette classe permet de gérer les groupes et les éléments d'association
 */
class QuestionsGroupesController extends AbstractController
{
    /**
     * Route permettant la création et la modification des groupes d'association avec leurs éléments
     *
     * @param ObjectManager $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/prof/activity/{activityId}/association/new", name="activity_association_new")
     * @Route("/prof/activity/{activityId}/association/{slug}/edit", name="activity_association_edit")
     */
    public function newOrEditQuestionsGroups($activityId, $slug = null, ObjectManager $manager, Request $request, ActivityRepository $activityRepository, QuestionsGroupesRepository $groupesRepository, QuestionsReponsesRepository $questionsReponsesRepository){

        $question = $groupesRepository->findOneby(['id' => $slug]);

        if(!$question){
            $question = new QuestionsGroupes();
        }
        $user = $this->getUser();
        $activity = $activityRepository->findOneby(['id' => $activityId]);
        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }
        $form = $this->createForm(QuestionsGroupesType::class, $question);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $question->setActivity($activity);

            $questionRemoveAll = $questionsReponsesRepository->findBy(['question' => null]);
            foreach ($questionRemoveAll as $item){
                $manager->remove($item);
            }

            $manager->persist($question);
            $manager->flush();

            if(!$slug){
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() .'_new', ['activityId' => $activity->getId()]);
            }else{
                $this->addFlash('success', 'Questions modifiées avec succès');
                return $this->redirectToRoute('activity_'. $activity->getType()->getName(), ['activityId' => $activity->getId()]);
            }
        }

        return $this->render('questions_groupes/index.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity,
            'slug' => $slug
        ]);
    }

    /**
     * Route permettant la suppression des groupes d'association avec leurs éléments
     *
     * @param $groupId
     * @param ObjectManager $manager
     * @Route("/prof/{groupId}/delete", name="groupe_delete")
     */
    public function deleteQuestionsGroups($groupId, ObjectManager $manager, QuestionsGroupesRepository $groupesRepository){

        $user = $this->getUser();
        $groupId = $groupesRepository->findOneBy(['id' => $groupId]);
        $activityId = $groupId->getActivity()->getId();
        if($groupId->getActivity()->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }
        $manager->remove($groupId);
        $manager->flush();

        return $this->redirectToRoute('activity_association', ['activityId' => $activityId]);
    }
}
