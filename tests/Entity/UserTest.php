<?php

namespace App\Tests\Entity;

require('vendor/autoload.php');

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserIsValid()
    {
        $user = (new User())
            ->setFirstname('Maxime')
            ->setLastname('Huet')
            ->setEmail('mhuet1@myges.fr')
            ->setPassword('password123')
            ->setBirthday(new \DateTime('11-11-1998'));

        $this->assertEquals(true, $user->isValid());
    }

    public function testUserNotAge()
    {
        $user = (new User())
            ->setFirstname('Maxime')
            ->setLastname('Huet')
            ->setEmail('mhuet1@myges.fr')
            ->setPassword('password123')
            ->setBirthday(new \DateTime('11-11-2019'));

        $this->assertEquals(false, $user->isValid());
    }

    public function testUserNotEmail()
    {
        $user = (new User())
            ->setFirstname('Maxime')
            ->setLastname('Huet')
            ->setEmail('wesxcdrftvgbyhnuji')
            ->setPassword('password123')
            ->setBirthday(new \DateTime('11-11-1998'));

        $this->assertEquals(false, $user->isValid());
    }

    public function testUserNotFirstname()
    {
        $user = (new User())
            ->setFirstname('')
            ->setLastname('Huet')
            ->setEmail('mhuet1@myges.fr')
            ->setPassword('password123')
            ->setBirthday(new \DateTime('11-11-1998'));

        $this->assertEquals(false, $user->isValid());
    }

    public function testUserNotLastname()
    {
        $user = (new User())
            ->setFirstname('Maxime')
            ->setLastname('')
            ->setEmail('mhuet1@myges.fr')
            ->setPassword('password123')
            ->setBirthday(new \DateTime('11-11-1998'));

        $this->assertEquals(false, $user->isValid());
    }

    public function testUserNotPassword1()
    {
        $user = (new User())
            ->setFirstname('Maxime')
            ->setLastname('Huet')
            ->setEmail('mhuet1@myges.fr')
            ->setPassword('123')
            ->setBirthday(new \DateTime('11-11-1998'));

        $this->assertEquals(false, $user->isValid());
    }

    public function testUserNotPassword2()
    {
        $user = (new User())
            ->setFirstname('Maxime')
            ->setLastname('Huet')
            ->setEmail('mhuet1@myges.fr')
            ->setPassword('azertyuiopqsdfghjklmwxcvbnazertyuiopqsdfghjklmwxcvbn')
            ->setBirthday(new \DateTime('11-11-1998'));

        $this->assertEquals(false, $user->isValid());
    }
}
