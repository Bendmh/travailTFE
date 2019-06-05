<?php

namespace App\Controller;


use App\Entity\ActivityType;
use App\Entity\Reponses;
use App\Entity\User;
use App\Form\AssignClassesType;
use App\Form\RegistrationType;
use App\Form\UserChangeDataType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Repository\ReponseEleveBrainstormingRepository;
use App\Repository\ReponseSondageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 *
 * Cette classe permet quelques routes pour les utilisateurs ou routes n'allant pas dans une autre particulièrement
 */
class IndexController extends AbstractController
{
    /**
     * Route pour la page de garde du site
     *
     * @Route("/", name="index")
     */
    public function index(ActivityTypeRepository $activityTypeRepository)
    {
        $MDPChange = false;
        $activityTypes = $activityTypeRepository->findAll();
        /** @var User $user */
        $user = $this->getUser();
        if($user && $user->getMdpOublie() == true){
            $MDPChange = true;
        }
        return $this->render('index/index.html.twig', [
            'current_menu' => 'home',
            'MDPChange' => $MDPChange,
            'activityTypes' => $activityTypes
        ]);
    }

    /**
     * Route permettant à l'utilisateur de voir ses données personnelles et de les modifier
     * Route permettant à l'admin ou professeurs d'assigner une classe à un prof (admin) ou élève (prof et admin)
     *
     * @Route("/perso", name="perso")
     * @Route("/perso/{id}", name="perso_id")
     */
    public function pagePerso($id = null, Request $request, ObjectManager $manager, UserRepository $userRepository){

        $MDPChange = false;
        if($id == null){
            /** @var User $user */
            $user = $this->getUser();
            $form = $this->createForm(UserChangeDataType::class, $user);
            $currentMenu = 'perso';
        }
        else{
            /** @var User $user */
            $user = $userRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(AssignClassesType::class, $user);
            $currentMenu = 'reglage';
        }
        if($user->getMdpOublie() == true){
            $MDPChange = true;
        }

        /*$form = $this->createForm(UserChangeDataType::class, $user);*/

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);

            $manager->flush();
        }

        return $this->render('index/perso.html.twig', [
            'form_user' =>$form->createView(),
            'user' => $user,
            'MDPChange' => $MDPChange,
            'id' => $id,
            'current_menu' => $currentMenu
        ]);
    }

    /**
     * Route permettant à un professeur de changer son role pour visualiser la plateforme comme un élève
     *
     * @param $id
     * @param UserRepository $userRepository
     * @param ObjectManager $manager
     * @Route("/changeRole", name="change_role")
     */
    public function changeRole(UserRepository $userRepository, ObjectManager $manager){
        /** @var User $user */
        $user = $this->getUser();
        $role = $user->getTitre();

        if($role == 'ROLE_PROFESSEUR'){
            $user->setTitre('ROLE_ELEVE_TEST');
        }
        else{
            $user->setTitre('ROLE_PROFESSEUR');
        }

        $manager->persist($user);
        $manager->flush();



        return $this->redirectToRoute('index');
    }

    /**
     * Route permettant de récupérer tous les utilisateurs mais les profs et les élèves séparément
     *
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list/users", name="list_user")
     */
    public function selectAllUser(UserRepository $userRepository){
        $userProf = $userRepository->findBy(['titre' => 'ROLE_PROFESSEUR']);

        $userEleve = $userRepository->findBy(['titre' => 'ROLE_ELEVE']);

        return $this->render('index/listUser.html.twig', [
            'current_menu' => 'reglage',
            'listProf' => $userProf,
            'listEleve' => $userEleve
        ]);
    }

    /**
     * @param $activityType
     * @param $activityId
     * @Route("/{activityId}/{activityType}/removeAnswer", name="remove_answer")
     */
    public function removeAnswer($activityType, $activityId, ReponseEleveBrainstormingRepository $eleveBrainstormingRepository, ReponseSondageRepository $reponseSondageRepository, ObjectManager $manager, ActivityRepository $activityRepository){
        switch ($activityType){
            case ActivityType::BRAINSTORMING_ACTIVITY:
                $reponsesEleves = $eleveBrainstormingRepository->findBy(['activity' => $activityId]);
                foreach ($reponsesEleves as $reponse){
                    $manager->remove($reponse);
                }
                $manager->flush();
                $this->addFlash('success', 'Les données ont bien été réinitialisées');
                return $this->redirectToRoute('list_brainstorming');
                break;
            case ActivityType::SONDAGE_ACTIVITY:
                $activity = $activityRepository->findOneBy(['id' => $activityId]);
                $reponsesEleves = $reponseSondageRepository->findBy(['questionSondage' => $activity->getQuestionSondage()->getId()]);
                foreach ($reponsesEleves as $reponse){
                    $manager->remove($reponse);
                }
                $manager->flush();
                $this->addFlash('success', 'Les données ont bien été réinitialisées');
                return $this->redirectToRoute('list_sondage');
                break;

        };
        return 0;
    }

}
