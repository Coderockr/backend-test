<?php

namespace App\Controllers;

use App\Helpers\Utils;
use App\Helpers\Validator;
use App\Models\Entities\City;
use App\Models\Entities\Country;
use App\Models\Entities\Investment;
use App\Models\Entities\Member;
use App\Models\Entities\State;
use App\Models\Entities\TalentInterest;
use App\Models\Entities\TalentInterestOther;
use App\Models\Entities\User;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Entities\Talent;
use App\Models\Entities\AddressTalent;
use App\Models\Entities\Address;
use App\Helpers\RequireValidator;

class ApiController extends Controller
{

    public function save(Request $request, Response $response)
    {
        try {
            $this->em->beginTransaction();
            $data = (array)$request->getParsedBody();
            $fields = [
                'created' => 'Data de criação',
                'owner' => 'Proprietário',
                'initialValue' => 'Valor',
            ];
            Validator::requireValidator($fields, $data);
            Validator::validDate($data['created']);
            $created = \DateTime::createFromFormat('d/m/Y', $data['created']);
            if ($created->format('Y-m-d') > date('Y-m-d')) throw new \Exception('A data não pode ser maior que o dia atual');
            if ((float)$data['initialValue'] < 0) throw new \Exception('Valor minimo do investimento é zero');
            $investment = new Investment();
            $investment->setCreated($created)
                ->setInitialValue((float)$data['initialValue'])
                ->setOwner($data['owner']);
            $investment = $this->em->getRepository(Investment::class)->save($investment);
            $this->em->commit();
            return $response->withJson([
                'status' => 'ok',
                'id' => $investment->getId(),
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
