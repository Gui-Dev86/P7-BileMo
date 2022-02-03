<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="api-bilemo", version="1.0")
 * @OA\Server(url="https://bilemo.fr/api", description="Api BileMo")
 * @OA\SecurityScheme(
 *     bearerFormat="JWT",
 *  securityScheme="bearer",
 *  type="apiKey",
 *  in="header",
 *  name="bearer",
 * )
 */
