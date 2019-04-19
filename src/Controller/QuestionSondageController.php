<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\QuestionSondage;
use App\Form\QuestionSondageNewType;
use App\Repository\ActivityRepository;
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
        ]);

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/sondage/{id}/result", name="sondage_result")
     */
    public function resultSondage($id, ReponseSondageRepository $reponseSondageRepository, QuestionSondageRepository $questionSondageRepository){

        $questionSondage = $questionSondageRepository->findOneBy(['id' => $id]);
        //$reponseSondage = $reponseSondageRepository->findBy(['questionSondage' => $id]);
        $reponseSondage = $reponseSondageRepository->resultSondage($id);
        $total = $reponseSondageRepository->returnCount($id);


        $tabData = [['sondage', 'Percentage']];
        foreach ($reponseSondage as $value){
            array_push($tabData, [$value['response'], 24*($value[1]/$total[0][1])]);
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable($tabData);
        $pieChart->getOptions()->setIs3D(true);
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);/*
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);*//*
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');*//*
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);*//*
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);*/

        return $this->render('question_sondage/result.html.twig', [
            'questionSondage' => $questionSondage,
            'results' => $reponseSondage,
            'piechart' => $pieChart,
            'total' => $total[0][1]
        ]);
    }
}
