<?php

namespace MainBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use MainBundle\Entity\Langage;
use MainBundle\Services\SshConnectionHandler;

class DockerSubscriber implements EventSubscriber
{
    public function __construct()
    {
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Langage) {
            $entityManager = $args->getEntityManager();

            // Récupération du dockerfile + de son nom
            $docker = "";
            $nameDocker = "";

            /**
             * Connexion au serveur
             * lancement de la création de l'image docker avec son nom
             */
        }
    }
}

?>