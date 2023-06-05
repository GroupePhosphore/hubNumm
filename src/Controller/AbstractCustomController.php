<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use OpenApi\Annotations as OA;



/**
 * @OA\Info(
 * 	title="HubNumm",
 * 	version="0.1",
 *  description="Micro service permettant d'interragir avec Numm (Logiciel de Comptabilité SASS)"
 * 	)
 * @OA\Server(
 * 	url="https://p.hubnumm.pprv.eu",
 * 	description="Prod"
 * 	)
 * 
 * @OA\Server(
 * 	url="https://pp.hubnumm.pprv.eu",
 * 	description="Pré Prod"
 * 	)
 * 
 * 
 * @OA\SecurityScheme(
 *  bearerFormat="JWT",
 *  type="http",
 *  securityScheme="bearer"
 * )
 * 
 * 
 * @OA\Post(
 *  path="/api/login_check",
 *  tags={"Login"},
 *  description="Route de connexion",
 * @OA\RequestBody(
 *  required=true,
 *  @OA\JsonContent(
 *      @OA\Property(
 *          property="username",
 *          example="TitouLeLapinou",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="password",
 *          example="1234",
 *          type="string"
 *      )
 *  )
 * ),
 *  @OA\Response(
 *      response="200",
 *      description="L'utilisateur est authentifie",
 *      @OA\JsonContent(
 *          description="Réponse",
 *          @OA\Property(
 *              property="token",
 *              type="string",
 *              example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2ODU1Mzk5NjEsImV4cCI6MTY4NTU0MzU2MSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiRGV2VXNlciJ9.QCRFlc85TPa-JdV9qYduyWLDLMVduj_BhA8utv1C72Qg7AqDDyBLeAzZgelghTwi3Fel4mtYn-5AgO8axQxMTENOQiXtdV5ztpV0nuLXyrhMJek8N5EAMA2ZsHBEDgwDJsPDQLihMfksfC2lTTA73CoeWe69gOYSi32048mYLgdq8awEBuA1JorDnY_c4hw3wLAGkiePU98ZZJMD60hrFIwT2gu5p04ryg2Sjp76bd0J4zoeMCbJ4eHGOazzAzoDtD3g9a9paNbZvkbUaGr_44dGWYaMCXu5VNWH42IFGFYU6GJfKpbdIa72ybwH5ht5uP0lrL6oKK7Ifzn9CNVSLQ"
 *          )
 *      )
 *  )
 * )
 * 
 */
abstract class AbstractCustomController extends AbstractController
{
    public function __construct(private LoggerInterface $logger)
    {
    }
}