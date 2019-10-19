<?php

include_once(__DIR__ . "/../Model/MerchantModel.php");
include_once(__DIR__ . "/../Base/BaseController.php");

class Disburse extends BaseController
{
    public function withdraw()
    {
        try {
            $params = $this->getData();
            $merchant = new MerchantModel();
            $result = $merchant->withdraw($params->id, $params->amount);
            $this->result($result);
        } catch (\Exception $e) {
            $this->internalServerError($e->getMessage());
        }
    }
}
