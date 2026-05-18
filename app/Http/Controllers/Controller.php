<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
/**
 * @OA\Info(
 *     title="API Customers",
 *     version="1.0.0",
 *     description="Documentation de l'API REST du webservice Customers du projet PayeTonKawa pour la MSPR TPRE814.",
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur principal"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="InternalApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-Internal-Api-Key"
 * )
 */
abstract class Controller
{
    //
}
