<?php
include_once(__DIR__ . "/../Repositories/Bank.php");

class BankModel
{

    function __construct()
    { }

    function getBanks()
    {
        try {
            $bankRepo = new BankRepo();
            $result = $bankRepo->getBanks();
            return $result;
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }
}
