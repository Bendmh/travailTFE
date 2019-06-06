<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Entity\QuestionSondage;
use App\Form\QuestionSondageNewType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Repository\QuestionSondageRepository;
use App\Repository\ReponseSondageRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuestionSondageController
 * @package App\Controller
 *
 * Cette classe permet de gérer les sondages ainsi que les résultats obtenus
 */
class QuestionSondageController extends AbstractController
{
    /**
     * Route permettant la création et la modification d'un sondage et la question
     *
     * @Route("/prof/activity/{activityId}/sondage/new", name="activity_sondage_new")
     * @Route("/prof/activity/{activityId}/sondage/{slug}/edit", name="activity_sondage_edit")
     */
    public function newOrEditSondage($activityId, $slug = null, Request $request, ObjectManager $manager, ActivityRepository $activityRepository, QuestionSondageRepository $questionSondageRepository)
    {
        $questionSondage = $questionSondageRepository->findOneby(['id' => $slug]);

        if(!$questionSondage){
            $questionSondage = new QuestionSondage();
        }
        $user = $this->getUser();
        $activity = $activityRepository->findOneby(['id' => $activityId]);
        if($activity->getCreatedBy() != $user) {
            return $this->render('index/error.html.twig');
        }

        $form = $this->createForm(QuestionSondageNewType::class, $questionSondage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $activity = $activityRepository->findOneby(['id' => $activityId]);
            $questionSondage->setActivity($activity);

            $manager->persist($questionSondage);

            $manager->flush();

            if(!$slug){
                return $this->redirectToRoute('activity_'. $activity->getType()->getName(), ['activityId' => $activity->getId()]);
            }else{
                $this->addFlash('success', 'Questions modifiées avec succès');
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() , ['activityId' => $activity->getId()]);
            }

        }

        return $this->render('question_sondage/index.html.twig', [
            'form_quest' => $form->createView(),
            'activity' => $activity,
            'question' => $questionSondage,
            'current_menu' => 'activity'
        ]);

    }

    /**
     * Route permettant de récupérer les résultats sous forme de texte pour un affichage plus soft
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/prof/sondage/{id}/result", name="sondage_result")
     */
    public function resultSondage($id, ReponseSondageRepository $reponseSondageRepository, QuestionSondageRepository $questionSondageRepository, ActivityRepository $activityRepository){

        $questionSondage = $activityRepository->findOneBy(['id' => $id]);
        $questionSondageId = $questionSondage->getQuestionSondage()->getId();
        //$reponseSondage = $reponseSondageRepository->findBy(['questionSondage' => $id]);
        $reponseSondage = $reponseSondageRepository->resultSondage($questionSondageId);
        $total = $reponseSondageRepository->returnCount($questionSondageId);


        // Cette partie sert pour faire un google chart (peut être utile si il y a une connexion internet)
        /*$tabData = [['sondage', 'Percentage']];
        foreach ($reponseSondage as $value){
            array_push($tabData, [$value['response'], 24*($value[1]/$total[0][1])]);
        }*/

        /*$pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($tabData);
        $pieChart->getOptions()->setIs3D(true);
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(500);*/

        return $this->render('question_sondage/result.html.twig', [
            'questionSondage' => $questionSondage->getQuestionSondage(),
            'results' => $reponseSondage,
            /*'piechart' => $pieChart,*/
            'total' => $total[0][1],
            'id' => $id,
            'current_menu' => 'resultat'
        ]);
    }

    /**
     * Route permettant de construire un graphique à partir des résultats obtenus
     *
     * @param $id
     * @param ReponseSondageRepository $reponseSondageRepository
     * @param ActivityRepository $activityRepository
     * @Route("/prof/sondage/{id}/graphique", name="sondage_graphique")
     */
    public function dataGraphique($id, ReponseSondageRepository $reponseSondageRepository, ActivityRepository $activityRepository){
        $questionSondage = $activityRepository->findOneBy(['id' => $id]);
        $questionSondageId = $questionSondage->getQuestionSondage()->getId();
        $reponseSondage = $reponseSondageRepository->resultSondage($questionSondageId);

        return $this->json(['code' => 200, 'message' => $reponseSondage], 200);
    }

    /**
     * Route affichant les sondages du professeur
     *
     * @Route("/prof/sondage/list", name="list_sondage")
     */
    public function listSondage(ActivityRepository $activityRepository){
        $userId = $this->getUser()->getId();
        $activitySondageTeacher = $activityRepository->activityByTypeAndByTeacher($userId, ActivityType::SONDAGE_ACTIVITY);
        //$typeSondage = $activityTypeRepository->findOneBy(['name' => 'sondage']);
        /*if(!$typeSondage){
            $this->addFlash('error', 'Aucun sondage n\'est créé par vous.');
        }*/
        return $this->render('question_sondage/list.html.twig', [
            'activitySondage' => $activitySondageTeacher,
            'current_menu' => 'resultat'
        ]);
    }
}
