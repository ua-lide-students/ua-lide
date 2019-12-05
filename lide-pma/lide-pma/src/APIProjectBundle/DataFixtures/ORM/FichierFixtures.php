<?php

namespace APIProjectBundle\DataFixtures\ORM;

use APIProjectBundle\Entity\Fichier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FichierFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager) {


    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies() {
        return array(
            ProjetFixtures::class,
        );
    }
}
