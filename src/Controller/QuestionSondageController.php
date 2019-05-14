<?php

namespace App\Controller;

use App\Entity\Activity;
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

class QuestionSondageController extends AbstractController
{
    /**
     * @Route("activity/{id}/sondage/new", name="activity_sondage_new")
     * @Route("activity/{id}/sondage/{slug}/edit", name="activity_sondage_edit")
     */
    public function index($id, $slug = null, Request $request, ObjectManager $manager, ActivityRepository $activityRepository, QuestionSondageRepository $questionSondageRepository)
    {
        $questionSondage = $questionSondageRepository->findOneby(['id' => $slug]);

        if(!$questionSondage){
            $questionSondage = new QuestionSondage();
        }

        $activity = $activityRepository->findOneby(['id' => $id]);

        $form = $this->createForm(QuestionSondageNewType::class, $questionSondage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $activity = $activityRepository->findOneby(['id' => $id]);
            $questionSondage->setActivity($activity);

            $manager->persist($questionSondage);

            $manager->flush();

            if(!$slug){
                return $this->redirectToRoute('activity_'. $activity->getType()->getName(), ['id' => $activity->getId()]);
            }else{
                $this->addFlash('success', 'Questions modifiées avec succès');
                return $this->redirectToRoute('activity_'. $activity->getType()->getName() , ['id' => $activity->getId()]);
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/sondage/{id}/result", name="sondage_result")
     */
    public function resultSondage($id, ReponseSondageRepository $reponseSondageRepository, QuestionSondageRepository $questionSondageRepository, ActivityRepository $activityRepository){

        $questionSondage = $activityRepository->findOneBy(['id' => $id]);
        $questionSondageId = $questionSondage->getQuestionSondage()->getId();
        //$reponseSondage = $reponseSondageRepository->findBy(['questionSondage' => $id]);
        $reponseSondage = $reponseSondageRepository->resultSondage($questionSondageId);
        $total = $reponseSondageRepository->returnCount($questionSondageId);


        $tabData = [['sondage', 'Percentage']];
        foreach ($reponseSondage as $value){
            array_push($tabData, [$value['response'], 24*($value[1]/$total[0][1])]);
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($tabData);
        $pieChart->getOptions()->setIs3D(true);
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(500);

        return $this->render('question_sondage/result.html.twig', [
            'questionSondage' => $questionSondage->getQuestionSondage(),
            'results' => $reponseSondage,
            'piechart' => $pieChart,
            'total' => $total[0][1],
            'current_menu' => 'resultat'
        ]);
    }

    /**
     * @Route("/sondage/list", name="list_sondage")
     */
    public function listSondage(ActivityRepository $activityRepository){
        $userId = $this->getUser()->getId();
        $activitySondageTeacher = $activityRepository->activitySondageByTeacher($userId);
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
