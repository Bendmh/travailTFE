<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Form\ActivityTypeType;
use App\Repository\ActivityTypeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/typeActivity", name="admin_list_type")
     */
    public function index(ActivityTypeRepository $activityTypeRepository)
    {
        $activity_type = $activityTypeRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'current_menu' => 'reglage',
            'activity_types' => $activity_type
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/typeActivity/new", name="new_type_activity")
     * @Route("/admin/typeActivity/{id}/edit", name="edit_type_activity")
     */
    public function activityType($id = null, Request $request, ObjectManager $manager, ActivityType $activityType = null){

        if(!$activityType){
            $activityType = new ActivityType();
        }
        $form = $this->createForm(ActivityTypeType::class, $activityType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($activityType);
            $manager->flush();

            return $this->redirectToRoute('admin_list_type');
        }

        return $this->render('admin/typeActivity.html.twig', [
            'current_menu' => 'reglage',
            'id' => $id,
            'form_type' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @param ActivityTypeRepository $activityTypeRepository
     * @param ObjectManager $manager
     * @Route("/admin/typeActivity/{id}/delete", name="delete_type_activity")
     */
    public function deleteTypeActivity($id, ActivityTypeRepository $activityTypeRepository, ObjectManager $manager){
        $activityType = $activityTypeRepository->findOneBy(['id' => $id]);
        $manager->remove($activityType);
        $manager->flush();
        return $this->redirectToRoute('admin_list_type');
    }
}
