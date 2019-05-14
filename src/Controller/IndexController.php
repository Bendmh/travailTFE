<?php

namespace App\Controller;


use App\Entity\Reponses;
use App\Entity\User;
use App\Form\UserChangeDataType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
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
     * @Route("/perso", name="perso")
     */
    public function perso(Request $request, ObjectManager $manager){

        $MDPChange = false;
        /** @var User $user */
        $user = $this->getUser();
        if($user->getMdpOublie() == true){
            $MDPChange = true;
        }

        $form = $this->createForm(UserChangeDataType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);

            $manager->flush();
        }

        return $this->render('index/perso.html.twig', [
            'form_user' =>$form->createView(),
            'user' => $user,
            'MDPChange' => $MDPChange,
            'current_menu' => 'perso'
        ]);
    }

    /**
     * @param UserRepository $repository
     * @Route("/list/MDPoublie", name="MDP_oublie")
     */
    public function listMDPoublie(UserRepository $repository){
        $userList = $repository->findBy(['mdpOublie' => true]);
        return $this->render('index/listMDPoublie.html.twig', [
            'userList' => $userList,
            'current_menu' => 'reglage'
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @Route("/nouveauMDP", name="nouveau_MDP")
     */
    public function nouveauMDP(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        /** @var User $user */
        $user = $this->getUser();
        $MDP = $request->request->get('_password');

        $MDPChange = false;
        if($user->getMdpOublie() == true){
            $MDPChange = true;
        }

        $user->setPassword($encoder->encodePassword($user, $MDP));
        $user->setMdpOublie(false);
        $this->addFlash('success', 'Votre mot de passe a bien été changé');
        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('perso');
    }

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/change/MDPoublie", name="change_MDP")
     */
    public function changerMDP(Request $request, UserRepository $repository, UserPasswordEncoderInterface $encoder, ObjectManager $manager){
        $userMDPChanged = $request->request;
        $MDP = $request->request->get('_password');
        foreach ($userMDPChanged as $key => $value){
            if($key != '_password'){
                $user = $repository->findOneBy(['pseudo' => $key]);
                $user->setPassword($encoder->encodePassword($user, $MDP));
                $manager->persist($user);
            }
        }
        $manager->flush();
        $userList = $repository->findBy(['mdpOublie' => true]);
        $this->addFlash('success', 'Les mots de passe ont été changé');
        return $this->render('index/listMDPoublie.html.twig', [
            'userList' => $userList,
            'current_menu' => 'reglage'
        ]);
    }
}
