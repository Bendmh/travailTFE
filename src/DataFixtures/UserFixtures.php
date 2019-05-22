<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setNom('');
        $user->setPrenom('');
        $user->setPseudo('');
        $user->setTitre('ROLE_SUPER_ADMIN');
        $password = $this->encoder->encodePassword($user, '');
        $user->setPassword($password);
        $user->setMdpOublie(0);

        $manager->flush();
    }
}
