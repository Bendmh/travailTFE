<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class MDPController
 * @package App\Controller
 *
 * Cette classe permet la gestion de MDP oublié.
 */
class MDPController extends AbstractController
{
    /**
     * Route informant l'utilisateur que sa demande a bien fonctionné
     *
     * @param Request $request
     * @Route("/mdpOublie", name="mdpOublie")
     */
    public function mdpOublie(Request $request, UserRepository $repository, ObjectManager $manager){
        $userName = $request->request->get('_username');
        $user = $repository->findOneBy(['pseudo' => $userName]);
        if($user){
            $user->setMdpOublie(true);
            $this->addFlash('success', 'Demande de changement de MDP envoyé');
            $manager->persist($user);
            $manager->flush();
        }
        else {
            $this->addFlash('error', 'Ce pseudo n\'est pas dans la base de données');
        }
        return $this->redirectToRoute('security_login');
    }

    /**
     * Route permettant de récupérer tous les utilisateurs ayant oublié son mot de passe
     *
     * @param UserRepository $repository
     * @Route("/list/MDPoublie", name="MDP_oublie")
     */
    public function listMDPoublie(UserRepository $repository){
        $userList = $repository->findBy(['mdpOublie' => true]);
        return $this->render('mdp/listMDPoublie.html.twig', [
            'userList' => $userList,
            'current_menu' => 'reglage'
        ]);
    }


    /**
     * Route permettant de changer le MDP de l'utilisateur selectionné dans la liste reçue précédemment
     *
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
        return $this->render('mdp/listMDPoublie.html.twig', [
            'userList' => $userList,
            'current_menu' => 'reglage'
        ]);
    }

    /**
     * Route permettant à l'utilisateur de modifier son MDP après avoir fait la demande
     *
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
}
