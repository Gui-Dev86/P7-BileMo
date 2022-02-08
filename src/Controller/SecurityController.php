<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use FOS\RestBundle\Controller\Annotations as Rest;
use OpenApi\Annotations as OA;

class SecurityController extends AbstractController
{


    /**
     * @Rest\Post(path="/api/login_check", name="api_login")
     * @Rest\View(statusCode= 200)
     * @OA\Post(
     *     path="/login_check",
     *     @OA\Response(
     *          response="200",
     *          description="Authenticate token JWT",
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=404, description="Page not found")
     * )
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
       
    }

}
