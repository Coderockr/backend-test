<?php

namespace App\Controllers;

use App\Helpers\Utils;
use App\Helpers\Validator;
use App\Models\Entities\City;
use App\Models\Entities\Client;
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

class InvestimentController extends Controller
{

    private function calculateProfitValue(Investment $investment): float
    {
        $expectedValue = $investment->getInitialValue();
        $end = $investment->getWithdrawalDate() ?? new \DateTime();
        $dateInterval = $investment->getCreated()->diff($end);
        $months = $dateInterval->m + ($dateInterval->y * 12);
        for ($i = 0; $i < $months; $i++) {
            $expectedValue += $expectedValue * 0.52 / 100;
        }
        return $expectedValue - $investment->getInitialValue();
    }

    private function calculateTaxValue(Investment $investment): float
    {
        $profit = $investment->getProfit() ?? $this->calculateProfitValue($investment);
        $end = $investment->getWithdrawalDate() ?? new \DateTime();
        $dateInterval = $investment->getCreated()->diff($end);
        if ($dateInterval->y < 1) return $profit * 22.5 / 100;
        elseif ($dateInterval->y < 2) return $profit * 18.5 / 100;
        else return $profit * 15 / 100;
    }

    public function createInvestiment(Request $request, Response $response)
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
            $owner = $this->em->getRepository(Client::class)->find($data['owner']);
            if (!$owner) throw new \Exception('Cliente inválido');
            $investment = new Investment();
            $investment->setCreated($created)
                ->setInitialValue((float)$data['initialValue'])
                ->setClient($owner);
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
            ])->withStatus(400);
        }
    }

    public function viewInvestiment(Request $request, Response $response)
    {
        try {
            $id = $request->getAttribute('route')->getArgument('id');
            $investment = $this->em->getRepository(Investment::class)->find($id);
            if (!$investment) throw new \Exception('Cliente inválido');
            return $response->withJson([
                'status' => 'ok',
                'initialValue' => Utils::formatMoney($investment->getInitialValue()),
                'profitValue' => Utils::formatMoney($investment->getProfit() ?? $this->calculateProfitValue($investment)),
                'taxValue' => Utils::formatMoney($investment->getTax() ?? 0),
                'expectedValue' => Utils::formatMoney($investment->getInitialValue()
                    + ($investment->getProfit() ?? $this->calculateProfitValue($investment))
                    - ($investment->getTax() ?? 0)
                ),
            ], 200)
                ->withHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->em->rollback();
            return $response->withJson([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->withStatus(400);
        }
    }

    public function withdraw(Request $request, Response $response)
    {
        try {
            $this->em->beginTransaction();
            $data = (array)$request->getParsedBody();
            $fields = [
                'withdrawDate' => 'Data de saque',
            ];
            Validator::requireValidator($fields, $data);
            Validator::validDate($data['withdrawDate']);
            $withdrawDate = \DateTime::createFromFormat('d/m/Y', $data['withdrawDate']);
            $id = $request->getAttribute('route')->getArgument('id');
            $investment = $this->em->getRepository(Investment::class)->find($id);
            if ($investment->getWithdrawalDate() != null) throw new \Exception('Esse investimento já foi sacado');
            if ($withdrawDate->format('Y-m-d') > date('Y-m-d')) throw new \Exception('A data não pode ser maior que o dia atual');
            if ($withdrawDate->format('Y-m-d') < $investment->getCreated()->format('Y-m-d')) throw new \Exception("Data minima {$investment->getCreated()->format('d/m/Y')}");
            $investment->setWithdrawalDate($withdrawDate)
                ->setTax($this->calculateTaxValue($investment))
                ->setProfit($this->calculateProfitValue($investment));
            $this->em->getRepository(Investment::class)->save($investment);
            $this->em->commit();
            return $response->withJson([
                'status' => 'ok',
                'initialValue' => Utils::formatMoney($investment->getInitialValue()),
                'profitValue' => Utils::formatMoney($investment->getProfit()),
                'taxValue' => Utils::formatMoney($investment->getTax()),
                'withdrawValue' => Utils::formatMoney($investment->getWithdrawValue()),
            ], 200)
                ->withHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->em->rollback();
            return $response->withJson([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->withStatus(400);
        }
    }

    public function investimentByClient(Request $request, Response $response)
    {
        try {
            $owner = $request->getAttribute('route')->getArgument('owner');
            $client = $this->em->getRepository(Client::class)->find($owner);
            if (!$client) throw new \Exception('Cliente inválido');
            $page = $request->getAttribute('route')->getArgument('page');
            $investments = $this->em->getRepository(Investment::class)->getByClient($client, $page);
            $investmentsArray = [];
            foreach ($investments as $investment) {
                $investmentsArray[] = [
                    'id' => $investment->getId(),
                    'created' => $investment->getCreated()->format('d/m/Y'),
                    'withdrawalDate' => $investment->getWithdrawalDate() ? $investment->getWithdrawalDate()->format('d/m/Y') : '',
                    'initialValue' => $investment->getInitialValue(),
                    'profitValue' => $investment->getProfit() ?? $this->calculateProfitValue($investment),
                    'taxValue' => $investment->getTax() ?? '',
                ];
            }
            return $response->withJson([
                'status' => 'ok',
                'investments' => $investmentsArray
            ], 200)
                ->withHeader('Content-type', 'application/json');
        } catch (Exception $e) {
            $this->em->rollback();
            return $response->withJson([
                'status' => 'error',
                'message' => $e->getMessage(),
            ])->withStatus(400);
        }
    }


}
