<?php

include 'src/Base/Route.php';
include 'src/Controller/Disburse.php';
include 'src/Controller/Bank.php';
include 'src/Controller/Merchant.php';
include 'configuration/env.php';
header("Access-Control-Allow-Origin: *");
Route::register('/status/([0-9a-zA-Z]*)', function ($id) {
    echo $id;
}, 'get');

Route::register('/bank_list', function () {
    $bank = new Bank();
    try {
        echo $bank->list();
    } catch (\Throwable $err) {
        throw $err;
    }
}, 'get');

Route::register('/merchant_list', function () {
    $merchant = new Merchant();
    try {
        echo $merchant->list();
    } catch (\Throwable $err) {
        throw $err;
    }
}, 'get');

Route::register('/add_balance', function () {
    $merchant = new Merchant();
    try {
        echo $merchant->addBalance();
    } catch (\Throwable $err) {
        throw $err;
    }
}, 'post');

Route::register('/withdraw', function () {
    $disburse = new Disburse();
    try {
        echo $disburse->withdraw($_POST);
    } catch (\Throwable $err) {
        throw $err;
    }
}, 'post');

Route::register('/addmerchant', function () {
    $merchant = new Merchant();
    $merchant->add();
}, 'post');

Route::register('/getmerchant', function () {
    $merchant = new Merchant();
    $merchant->list();
}, 'post');

Route::pathNotFound(function ($path) {
    echo 'The requested path "' . $path . '" was not found!';
});

Route::methodNotAllowed(function ($path, $method) {
    echo 'The requested path "' . $path . '" exists. But the request method "' . $method . '" is not allowed on this path!';
});

Route::run('/');
