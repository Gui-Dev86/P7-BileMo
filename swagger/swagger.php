<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   description="This is the Bilemo server.",
 *   version="1.0.0",
 *   title="Swagger Bilemo"
 * )
 * @OA\Server(
 *   url="http://localhost:8000/api/",
 *   description="Api BileMo")
 * )
 * @OA\SecurityScheme(
 *  bearerFormat="JWT",
 *  securityScheme="bearer",
 *  type="http",
 *  in="header",
 *  scheme="bearer",
 * )
 */

 class Bilemo
 {
 }
