<?php

namespace App\Tests\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user_adrien = new User();
        $user_adrien->setUsername('adrien.durmier@amediasolutions.fr');
        $user_adrien->setFirstname('Adrien');
        $user_adrien->setLastname('Durmier');
        $user_adrien->setEmail('adrien.durmier@amediasolutions.fr');
        $user_adrien->setEnabled(1);
        $user_adrien->addRole('ROLE_ADMIN');
        $user_adrien->addRole('ROLE_IPTIS_ADMIN');
        $user_adrien->setPassword('test');
        $manager->persist($user_adrien);

        $user_stephanie = new User();
        $user_stephanie->setUsername('stephanie.dumoutier@amediasolutions.fr');
        $user_stephanie->setFirstname('Stéphanie');
        $user_stephanie->setLastname('Dumoutier');
        $user_stephanie->setEmail('stephanie.dumoutier@amediasolutions.fr');
        $user_stephanie->setEnabled(1);
        $user_stephanie->addRole('ROLE_IPTIS_CHEF');
        $user_stephanie->setPassword('test');
        $manager->persist($user_stephanie);

        $user_david = new User();
        $user_david->setUsername('david.delmas@amediasolutions.fr');
        $user_david->setFirstname('Stéphanie');
        $user_david->setLastname('Dumoutier');
        $user_david->setEmail('david.delmas@amediasolutions.fr');
        $user_david->setEnabled(1);
        $user_david->addRole('ROLE_IPTIS_COMMERCIAL');
        $user_david->setPassword('test');
        $manager->persist($user_david);

        $manager->flush();

        $this->addReference('user-adrien', $user_adrien);
        $this->addReference('user-stephanie', $user_stephanie);
        $this->addReference('user-david', $user_david);
    }
}
