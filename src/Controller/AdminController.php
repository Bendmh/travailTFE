<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Entity\Classes;
use App\Form\ActivityTypeType;
use App\Form\ClasseType;
use App\Repository\ActivityTypeRepository;
use App\Repository\ClassesRepository;
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

    /**
     * @param null $id
     * @param Request $request
     * @param ObjectManager $manager
     * @param Classes|null $classe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/classe/new", name="new_classe")
     * @Route("/admin/classe/{id}/edit", name="edit_classe")
     */
    public function addClasses($id = null, Request $request, ObjectManager $manager, Classes $classe = null){
        if(!$classe){
            $classe = new Classes();
        }

        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($classe);
            $manager->flush();

            return $this->redirectToRoute('admin_list_classes');
        }

        return $this->render('admin/classe.html.twig', [
            'current_menu' => 'reglage',
            'id' => $id,
            'form_type' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/classes", name="admin_list_classes")
     */
    public function listClasses(ClassesRepository $classesRepository)
    {
        $classes_type = $classesRepository->findAll();

        return $this->render('admin/listClasses.html.twig', [
            'current_menu' => 'reglage',
            'classes_types' => $classes_type
        ]);
    }

    /**
     * @param $id
     * @param ActivityTypeRepository $activityTypeRepository
     * @param ObjectManager $manager
     * @Route("/admin/classe/{id}/delete", name="delete_classe")
     */
    public function deleteClasse($id, ClassesRepository $classesRepository, ObjectManager $manager){
        $classeType = $classesRepository->findOneBy(['id' => $id]);
        $manager->remove($classeType);
        $manager->flush();
        return $this->redirectToRoute('admin_list_classes');
    }
}
