<?php

namespace App\Controller;


use App\Entity\Projet;
use Symfony\Component\Filesystem\Filesystem;

class ProjetService {

    private $filesystem;
    private $filesystemPath;

    function __construct(Filesystem $filesystemPath) {
        $this->filesystem = new Filesystem();
        $this->filesystemPath = $filesystemPath;
        if (!$this->filesystem->exists($this->filesystemPath)) {
            $this->filesystem->mkdir($this->filesystemPath);
        }
    }

    /**
     * @param Projet $projet
     */
    public function createProjectFileSystem($projet) {
        $projectPath = $this->filesystemPath. '/'. $projet->getUserId().'/'.$projet->getId();
        $this->filesystem->mkdir($projectPath);
    }

    /**
     * @param Projet $projet
     */
    public function  deleteProjectFileSystem($projet) {
        $projectPath = $this->filesystemPath. '/'. $projet->getUserId().'/'.$projet->getId();
        $this->filesystem->remove($projectPath);
    }
}