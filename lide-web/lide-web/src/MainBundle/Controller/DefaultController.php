<?php

namespace MainBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use MainBundle\Entity\Langage;
use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Execution;
use MainBundle\Form\ExecutionType;
use MainBundle\Form\OptionsInterfaceType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    public function showIdeAction($projectId){
        /** @var User $user */
        $user = $this->getUser();

        /** @var JWTManager $jwtManager */
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        $webUrl = $this->container->getParameter('base_url');
        if($this->container->getParameter('kernel.environment') === 'prod'){
            $webUrl .= 'app.php/';
        }else {
            $webUrl .= 'app_dev.php/';
        }

        $em = $this->getDoctrine()->getManager();
        /** @var Collection|Langage[] $environments */
        $environments = $em->getRepository('MainBundle:Langage')->findByActif(true);

        return $this->render('MainBundle:Default:idevue.html.twig', [
            'user_config' => $user->getConfiguration(),
            'jwt' => $jwtManager->create($user),
            'projectId' => $projectId,
            'web_url' => $webUrl,
            'pma_url' => $this->container->getParameter('lide_pma_url'),
            'ws_url' => $this->container->getParameter('ws_url'),
            'user' => [
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ],
            'environments' => array_map(function (Langage $env){
                return [
                    'name' => $env->getNom(),
                    'id' => $env->getId(),
                    'image' => $env->getDockerName(),
                    'default_opt' => $env->getOptions()
                ];
            }, $environments),
        ]);

    }

    public function homeVueAction(Request $request){

        /** @var User $user */
        $user = $this->getUser();
        if(is_null($user)){
            throw new ForbiddenActionException();
        }

        /** @var JWTManager $jwtManager */
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        $webUrl = $this->container->getParameter('base_url');
        if($this->container->getParameter('kernel.environment') === 'prod'){
            $webUrl .= 'app.php/';
        }else {
            $webUrl .= 'app_dev.php/';
        }

        return $this->render('MainBundle:Default:home.html.twig', [
            'jwt' => $jwtManager->create($user),
            'user' => [
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ],
            'web_url' => $webUrl,
            'pma_url' => $this->container->getParameter('lide_pma_url')
        ]);
    }
}
