<?php
/**
 * Created by PhpStorm.
 * User: etudiant
 * Date: 07/09/18
 * Time: 14:17
 */

namespace MainBundle\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use MainBundle\Entity\User;
use MainBundle\Form\OptionsInterfaceType;
use MainBundle\Form\UserConfigurationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class UserController
 * Controller for User related action in the ide (saving configuration, code...)
 * @package MainBundle\Controller
 */
class UserController extends Controller
{
    
    /**
     * Save the user interface configuration in database
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function updateInterfaceAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $user = $this->getUser();
            $form= $this->createform(OptionsInterfaceType::class, $user);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $rep = "OK";
            } else {
                $rep = "Formulaire non valide";
            }

            return new JsonResponse($rep);
        }
        return new Response('This is not ajax!', 400);
    }

    public function saveUserConfigurationAction(Request $request)
    {
        if(!$request->isMethod('POST')){
            return new JsonResponse([], 405);
        }
        /** @var User $user */
        $user = $this->getUser();

        $form= $this->createform(UserConfigurationType::class, $user);

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $response = [
                "meta" => [
                    "success" => true
                ],
                'data' => $user->getConfiguration()
            ];
            $code = 200;
        }else{
            $errors = [];
            foreach($form->getErrors(true, true) as $er) {
                $errors[] = [
                    'message' => $er->getMessage(),
                ];
            }
            $response = [
                'meta' => [
                    'success' => false
                ],
                'errors' => $errors
            ];
            $code = 422;
        }
        return new JsonResponse($response, $code);
    }

    public function getJwtAction(Request $request){
        if(!$request->isMethod('POST')){
            return new JsonResponse([], 405);
        }


        /** @var User $user */
        $user = $this->getUser();
        if(is_null($user)){
            return new JsonResponse([], 403);
        }

        /** @var JWTManager $jwtManager */
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        $jwt = $jwtManager->create($user);
        return new JsonResponse([
            'jwt' => $jwt
        ], 200);
    }
}