<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentStoreRequest;
use App\Service\InvestmentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\UrlParam;
use Knuckles\Scribe\Attributes\Response as DocResponse;

/**
 * @group Investment management
 *
 * APIs for managing Investments
 */
class InvestmentController extends Controller
{
    private InvestmentsService $investmentService;

    public function __construct(InvestmentsService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    #[Endpoint("getInvestmentsFromInvestor.", <<<DESC
      Get all investment from an Investor.
     DESC)]
    #[UrlParam(
        "investor_id",
        "Uuid required The ID of the Investor.",
        required: true,
        example: "98a62bf8-60ec-4e14-837e-8d0d9d1aeb79"
    )]
    #[DocResponse([
        [
            "id" => "98a67230-8d08-40f9-a144-890845f509fe",
            "name" => "Travel",
            "status" => "ACTIVE",
            "create_date" => "1985-08-24T03:24:26.000000Z",
            "balance" => ["amount" => "793", "currency" => "BRL"],
            "investment_balance" => ["amount" => "0", "currency" => "BRL"],
            "expected_balance" => ["amount" => "0", "currency" => "BRL"],
            "currency" => "BRL",
            "investor_id" => "98a67230-8baa-426f-b567-c4f95c055028",
            "created_at" => "2023-03-10T00:01:17.000000Z",
            "updated_at" => "2023-03-10T00:01:17.000000Z",
        ]
    ])]
    #[DocResponse([])]
    public function getInvestmentsFromInvestor(string $investor_id): JsonResponse
    {
        try {
            return response()->json(
                $this->investmentService->getInvestmentsFromInvestor($investor_id),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[Endpoint("getAInvestmentFromInvestor.", <<<DESC
      Get a specific investment from an Investor.
     DESC)]
    #[UrlParam(
        "investor_id",
        "Uuid required The ID of the Investor.",
        required: true,
        example: "98a62bf8-60ec-4e14-837e-8d0d9d1aeb79"
    )]
    #[UrlParam(
        "id",
        "Uuid required The ID of the Investment.",
        required: true,
        example: "98a62bf8-6661-4c52-b700-e653da888f43"
    )]
    #[DocResponse([
            "id" => "98a62bf8-6661-4c52-b700-e653da888f43",
            "name" => "Travel",
            "status" => "ACTIVE",
            "create_date" => "1985-08-24T03:24:26.000000Z",
            "balance" => ["amount" => "793", "currency" => "BRL"],
            "investment_balance" => ["amount" => "0", "currency" => "BRL"],
            "expected_balance" => ["amount" => "0", "currency" => "BRL"],
            "currency" => "BRL",
            "investor_id" => "98a67230-8baa-426f-b567-c4f95c055028",
            "created_at" => "2023-03-10T00:01:17.000000Z",
            "updated_at" => "2023-03-10T00:01:17.000000Z",
    ])]
    #[DocResponse([])]
    public function getAInvestmentFromInvestor(string $investor_id, string $id)
    {
        try {
            return response()->json(
                $this->investmentService->getInvestmentFromInvestor($investor_id, $id),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    #[Endpoint("createInvestment.", <<<DESC
      Create a Investment to Investor.
     DESC)]
    #[UrlParam(
        "investor_id",
        "Uuid required The ID of the Investor.",
        required: true,
        example: "98a62bf8-60ec-4e14-837e-8d0d9d1aeb79"
    )]
    #[BodyParam("name", "string", "Name for Investment.", example: "Money for travel")]
    #[BodyParam(
        "create_date",
        "timestamp",
        "Investment creation date. Must be at most the current moment",
        required: true,
        example: 1678409066
    )]
    #[BodyParam("balance", "numeric", "Amount to be deposited", required: true, example: 500)]
    #[DocResponse([
        "id" => "98a62bf8-6661-4c52-b700-e653da888f43",
        "name" => "Money for travel",
        "status" => "ACTIVE",
        "create_date" => "1985-08-24T03:24:26.000000Z",
        "balance" => ["amount" => "500", "currency" => "BRL"],
        "investment_balance" => ["amount" => "0", "currency" => "BRL"],
        "expected_balance" => ["amount" => "0", "currency" => "BRL"],
        "currency" => "BRL",
        "investor_id" => "98a67230-8baa-426f-b567-c4f95c055028",
        "created_at" => "2023-03-10T00:01:17.000000Z",
        "updated_at" => "2023-03-10T00:01:17.000000Z",
    ])]
    #[DocResponse([
        "message" => [
            "balance" => ["The balance must be greater than or equal to 0."],
        ],
        "errors" => [],
    ], 442, "When some input is invalid")]
    public function createInvestment(string $investor_id, InvestmentStoreRequest $request)
    {
        try {
            $request->validate();

            return response()->json(
                $this->investmentService->storeInvestment($investor_id, $request->all()),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), [], $e->getCode());
        }
    }

    #[Endpoint("withdrawInvestment.", <<<DESC
      Withdraw the investment.
     DESC)]
    #[UrlParam(
        "investor_id",
        "Uuid required The ID of the Investor.",
        required: true,
        example: "98a62bf8-60ec-4e14-837e-8d0d9d1aeb79"
    )]
    #[BodyParam(
        "investment_id",
        "string",
        "Uuid required The ID of the Investor.",
        required: true,
        example: "98a62bf8-6661-4c52-b700-e653da888f43"
    )]
    #[DocResponse([
        "id" => "98a685bf-f8a7-4f22-9332-5c432bb82d54",
        "name" => "Jack Watsica",
        "status" => "WITHDRAWN",
        "create_date" => "2023-03-09T00:55:59.000000Z",
        "balance" => ["amount" => "1155", "currency" => "BRL"],
        "investment_balance" => ["amount" => "155", "currency" => "BRL"],
        "expected_balance" => ["amount" => "1200", "currency" => "BRL"],
        "currency" => "BRL",
        "investor_id" => "98a685bf-f7b4-4eef-ae67-6fd3cac1a2ff",
        "created_at" => "2023-03-10T00:55:59.000000Z",
        "updated_at" => "2023-03-10T00:55:59.000000Z",
        "actual_investment_balance" => ["amount" => "200", "currency" => "BRL"],
        "rateApplied" => 0.225,
    ])]
    public function withdrawInvestment(string $investor_id, Request $request)
    {
        try {
            return response()->json(
                $this->investmentService->withdrawInvestment(
                    $investor_id,
                    $request->get('investment_id')
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
