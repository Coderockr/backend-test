<?php

namespace App\Jobs;

use App\Service\InvestmentsService;
use App\Service\RateService;

class ApplyRateInvestment extends Job
{

    private InvestmentsService $investmentsService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InvestmentsService $investmentsService)
    {
        $this->investmentsService = $investmentsService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->investmentsService->applyRateToInvestment();
    }
}
