<?php

namespace App\DataFixtures;

use App\Entity\Classes;
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
        $classe = new Classes();
        $classe->setNom('2A');
        $manager->persist($classe);

        $user = new User();
        $user->setNom('deMahieu');
        $user->setPrenom('Benoit');
        $user->setPseudo('pajero');
        $user->setTitre('ROLE_SUPER_ADMIN');
        $user->setPassword($this->encoder->encodePassword($user, 'testtest'));
        $user->addClass($classe);

        $manager->persist($user);

        $user = new User();
        $user->setNom('deMahieu');
        $user->setPrenom('Marie');
        $user->setPseudo('peps');
        $user->setTitre('ROLE_PROFESSEUR');
        $user->setPassword($this->encoder->encodePassword($user, 'testtest'));
        $user->addClass($classe);

        $manager->persist($user);

        $user = new User();
        $user->setNom('deMahieu');
        $user->setPrenom('Pierre');
        $user->setPseudo('lynx');
        $user->setTitre('ROLE_ELEVE');
        $user->setPassword($this->encoder->encodePassword($user, 'testtest'));
        $user->addClass($classe);

        $manager->persist($user);

        $manager->flush();
    }
}
