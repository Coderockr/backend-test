<?php

namespace App\Controllers;

use App\Helpers\Utils;
use App\Models\Entities\Address;
use App\Models\Entities\ChangeEmail;
use App\Models\Entities\City;
use App\Models\Entities\Country;
use App\Models\Entities\Course;
use App\Models\Entities\EadBoxUsers;
use App\Models\Entities\Gateway;
use App\Models\Entities\Member;
use App\Models\Entities\Purchase;
use App\Models\Entities\State;
use App\Models\Entities\Transaction;
use App\Models\Entities\User;
use App\Services\EadBox;
use App\Services\Email;
use App\Services\IuguService;
use Doctrine\ORM\EntityManager;
use App\Models\Entities\Candidate;
use App\Models\Entities\Transactions;
use App\Models\Entities\PersonCreditCard;
use App\Models\Entities\PersonSignature;
use App\Models\Entities\Directory;
use App\Models\Entities\Communicated;
use App\Services\NovoService;
use App\Helpers\Session;
use Slim\Views\PhpRenderer;

abstract class Controller
{
    protected EntityManager $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    protected function getConfigs()
    {
        $config = parse_ini_file('configs.ini', true);
        return $config[$config['environment']];
    }

}
