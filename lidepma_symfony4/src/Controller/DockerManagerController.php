<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DockerManagerController extends Controller
{
    public function index()
    {
        // replace this example code with whatever you need
        dump($this->getUser());
        die();
        return new JsonResponse([
            'user' => $this->getUser()
        ]);
    }
}
