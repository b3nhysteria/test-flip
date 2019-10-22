<?php
include_once(__DIR__ . "/../Model/MerchantModel.php");
include_once(__DIR__ . "/../Base/BaseController.php");
class Merchant extends BaseController
{
    public function add()
    {
        try {
            $data = $this->getData();
            $merchant = new MerchantModel();
            $result = $merchant->setUpNewMerchant($data);
            $this->result($result, "SUCCSS");
        } catch (\Throwable $e) {
            $this->internalServerError($e->getMessage());
        }
    }

    public function list()
    {
        try {
            $limit = 10;
            $page = 1;
            $data = $this->getDataQuery();
            if (property_exists($data, "limit")) {
                $limit = $data->limit;
            }
            if (property_exists($data, "page")) {
                $page = $data->page;
            }
            $merchant = new MerchantModel();
            $offset = $limit * ($page - 1);
            $result = $merchant->getMerchants($offset, $limit);
            $this->resultWithMeta(
                $result->data,
                (object) [
                    'total_data' => $result->meta->total_data,
                    'page' => $page,
                    'limit' => $limit
                ]
            );
        } catch (\Throwable $e) {
            $this->internalServerError($e->getMessage());
        }
    }

    public function addBalance()
    {
        try {
            $data = $this->getData();
            $merchant = new MerchantModel();
            $merchant->addDeposit($data->id, $data->balance);
            $this->result($merchant, "SUCCSS");
        } catch (\Throwable $e) {
            $this->internalServerError($e->getMessage());
        }
    }

    public function getFinanceAccount($id)
    {
        try {
            $merchant = new MerchantModel();
            $result = $merchant->getFinanceAccount($id);
            $this->result($result, "SUCCSS");
        } catch (\Throwable $e) {
            $this->internalServerError($e->getMessage());
        }
    }
}
