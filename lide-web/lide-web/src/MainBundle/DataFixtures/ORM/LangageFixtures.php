<?php

namespace AppBundle\DataFixtures\ORM;
use MainBundle\Entity\Langage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of Fixtures
 *
 * @author etudiant
 */

class LangageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
               
            $langage = new Langage();
            $langage->setNom("c++");
            $langage->setCompilateur("g++");
            $langage->setDockerfile("toto");
            $langage->setDockerName("gcc");
            $content = file_get_contents("src/MainBundle/Resources/script/exec_cpp.sh");
            $langage->setScript($content);
            $langage->setActif(1);
            $manager->persist($langage);

            $langage1 = new Langage();
            $langage1->setNom("c");
            $langage1->setCompilateur("g++");
            $langage1->setDockerfile("titi");
            $langage1->setDockerName("gcc");
            $content1 = file_get_contents("src/MainBundle/Resources/script/exec_c.sh");
            $langage1->setScript($content1);
            $langage1->setActif(1);
            $manager->persist($langage1);

            $langage2 = new Langage();
            $langage2->setNom("java");
            $langage2->setCompilateur("javac");
            $langage2->setDockerfile("tata");
            $langage2->setDockerName("java");
            $content2 = file_get_contents("src/MainBundle/Resources/script/exec_java.sh");
            $langage2->setScript($content2);
            $langage2->setActif(0);
            $manager->persist($langage2);

            $manager->flush();
            
			$this->addReference('language-cpp', $langage);
			$this->addReference('language-c', $langage1);
			$this->addReference('language-java', $langage2);
    }
}
