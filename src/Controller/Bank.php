
<?php

include_once(__DIR__ . "/../Model/BankModel.php");
include_once(__DIR__ . "/../Base/BaseController.php");

class Bank extends BaseController
{
    public function list()
    {
        try {
            $bank = new BankModel();
            $result = $bank->getBanks();
            $this->result($result, "SUCCSS");
        } catch (\Throwable $e) {
            $this->internalServerError($e->getMessage());
        }
    }
}
