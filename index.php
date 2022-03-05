<?php
include 'config.php';

use App\Controllers\CreateUsers;
use Steampixel\Route;
use App\Controllers\InvestController;

header("Access-Control-Allow-Origin: *"); # Only in development

Route::add('/createUser',  function () {
    $createUsers = new CreateUsers();
    echo json_encode($createUsers->Create($_POST['name'], $_POST['cpf']));
},'post');

Route::add('/investment/create',  function () {
    $createInvestment = new InvestController();
    $date = $_POST['date'] ?? date("Y-m-d H:i:s");
    echo json_encode($createInvestment->Create($_POST['user_id'], $_POST['value'], $date));
},'post');

// Run the router
Route::run('/');