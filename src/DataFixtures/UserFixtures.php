<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 2; $i++) {
            $user = (new User())
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setPassword($faker->password)
                ->setEmail($faker->safeEmail)
                ->setRoles(['ROLE_USER'])
                ->setBirthday(new \DateTime('11-11-1998'))
            ;

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'test'
            ));

            $this->setReference('user'.$i, $user);

            $manager->persist($user);
        }

        $user = (new User())
            ->setLastname($faker->lastName)
            ->setFirstname($faker->firstName)
            ->setPassword($faker->password)
            ->setEmail('user@admin')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setBirthday(new \DateTime('11-11-1998'))
        ;

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test'
        ));
        $manager->persist($user);

        $manager->flush();
    }
}
