<?php

namespace App\Controller;

use App\Entity\ResultSearch;
use App\Form\ResultSearchType;
use App\Repository\UserActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    /**
     * @Route("/resultats", name="resultat")
     */
    public function index(UserActivityRepository $userActivityRepository, Request $request)
    {

        $search = new ResultSearch();
        $form = $this->createForm(ResultSearchType::class, $search);
        $form->handleRequest($request);
        $user_activity = $userActivityRepository->findAllVisibleQuery($search);
        //$user_activity = $userActivityRepository->findAll();
        //$user_activity = $userActivityRepository->myFind(31);

        return $this->render('resultat/index.html.twig', [
            'user_activity' => $user_activity,
            'form_result' => $form->createView()
        ]);
    }

    /**
     * @param UserActivityRepository $userActivityRepository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/points/{id}", name="resultatPerso")
     */
    public function resultatPerso($id = null, UserActivityRepository $userActivityRepository, Request $request){

        $search = new ResultSearch();
        $form = $this->createForm(ResultSearchType::class, $search);
        $form->handleRequest($request);
        $user_activity = $userActivityRepository->findBy(['user_id' => $id]);

        return $this->render('resultat/index.html.twig', [
            'user_activity' => $user_activity,
            'form_result' => $form->createView()
        ]);
    }
}
