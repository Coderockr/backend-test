<?php

namespace App\Controllers;

use App\Helpers\Validator;
use App\Models\Entities\Client;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClientController extends Controller
{

    public function create(Request $request, Response $response)
    {
        try {
            $this->em->beginTransaction();
            $data = (array)$request->getParsedBody();
            $fields = [
                'name' => 'Nome',
            ];
            Validator::requireValidator($fields, $data);
            $client = new Client();
            $client->setName($data['name']);
            $client = $this->em->getRepository(Client::class)->save($client);
            $this->em->commit();
            return $response->withJson([
                'status' => 'ok',
                'id' => $client->getId(),
            ], 201)
                ->withHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->em->rollback();
            return $response->withJson([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->withStatus(500);
        }
    }

}