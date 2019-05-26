<?php

namespace App\Controller;

use App\Entity\ActivityType;
use App\Entity\Classes;
use App\Form\ActivityTypeType;
use App\Form\ClasseType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Repository\ClassesRepository;
use App\Repository\UserRepository;
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

    /**
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/users", name="list_user")
     */
    public function selectAllUser(UserRepository $userRepository){
        $userProf = $userRepository->findBy(['titre' => 'ROLE_PROFESSEUR']);

        $userEleve = $userRepository->findBy(['titre' => 'ROLE_ELEVE']);

        return $this->render('admin/listUser.html.twig', [
            'current_menu' => 'reglage',
            'listProf' => $userProf,
            'listEleve' => $userEleve
        ]);
    }

    /**
     * @param $id
     * @param UserRepository $userRepository
     * @param ObjectManager $manager
     * @Route("/admin/delete/{id}", name="delete_user")
     */
    public function deleteUser($id, UserRepository $userRepository, ObjectManager $manager, ActivityRepository $activityRepository){
        $admin = $this->getUser();
        $user = $userRepository->findOneBy(['id' => $id]);
        $activities = $activityRepository->findBy(['created_by' => $id]);
        foreach ($activities as $activity){
            $activity->setCreatedBy($admin);
            $manager->persist($activity);
        }
        $manager->flush();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('list_user');
    }

    /**
     * @param ActivityRepository $activityRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/activity/all", name="activity_all")
     */
    public function activityAll(ActivityRepository $activityRepository){
        $activities = $activityRepository->findAll();

        return $this->render('activity/activityList.html.twig', [
            'activites' => $activities,
            'current_menu' => 'activity',
            'perso' => true
        ]);
    }
}
