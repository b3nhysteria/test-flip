<?php

include_once(__DIR__ . "/../Base/IAccount.php");
include_once(__DIR__ . "/../Base/BaseModel.php");
include_once(__DIR__ . "/../Utils/flip_helper.php");
include_once(__DIR__ . "/../Repositories/Merchant.php");
include_once(__DIR__ . "/../Repositories/FlipService.php");
include_once(__DIR__ . "/../Repositories/MerchantBankAccount.php");

class MerchantModel extends BaseModel implements IAccount
{

    function __construct()
    { }

    function setUpNewMerchant($data)
    {
        $merchantRepo = new MerchantRepo();
        $merchantAccount = new MerchantBankAccount();
        $merchant = new stdClass();

        $this->begin_transaction();
        try {
            $merchant->id = date('dmYhis') . uniqid();
            $merchant->name = $data->data->name;
            $merchant->balance = $data->data->balance;
            $merchantRepo->saveMerchant($merchant);

            $accountFinance = $data->finance_info;
            foreach ($accountFinance as $value) {
                $merchantAccount->savingBankAccount((object) [
                    'id' => date('dmYhis') . uniqid(),
                    'merchant_id' => $merchant->id,
                    'account_number' => $value->account_number,
                    'bank_id' => $value->bank_id
                ]);
            }
            $this->commit();
            return (object) ['merchant' => $merchant, 'accountFinance' => $accountFinance];
        } catch (\Throwable $ex) {
            $this->rollback();
            throw $ex;
        }
    }

    function getMerchants($offset, $limit)
    {
        try {
            $merchantRepo = new MerchantRepo();
            $result = $merchantRepo->getList($limit, $offset);
            return $result;
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }

    public function withdraw($id, $amount, $account)
    {
        $merchantRepo = new MerchantRepo();
        $flipRepo = new FlipService();
        $this->begin_transaction();
        try {
            $validMerchant = $merchantRepo->checkingBalance($id, $amount, $account);
            if ($validMerchant) {
                $merchantRepo->withdrawDeposit($id, $amount);
                $flipHelper = new FlipHelper();
                $request = ['bank_code' => $validMerchant->bank_id, 'account_number' => $validMerchant->bank_account, 'amount' => $amount, 'remark' => $id];
                $response = $flipHelper->postRequest($request);
                $flipRepo->savingRequest((object) [
                    'request' => json_encode($request),
                    'response' => json_encode($response),
                    'id' => date('dmYhis') . uniqid(),
                    'status' => (($response->status === 'PENDING') ? 1 : 2),
                    'merchant_id' =>  $id,
                    'request_id' => $response->id,
                    'receipt' => $response->receipt,
                    'fee' => $response->fee
                ]);
                $this->commit();
                return $response;
            } else {
                throw new \Exception('not enought balance or merchant is not found');
            }
        } catch (\Throwable $ex) {
            $this->rollback();
            throw $ex;
        }
    }

    public function addDeposit($id, $amount)
    {
        try {
            $merchantRepo = new MerchantRepo();
            $result = $merchantRepo->addDeposit($id, $amount);
            return $result;
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }

    public function getFinanceAccount($id)
    {
        try {
            $merchantRepo = new MerchantBankAccount();
            $result = $merchantRepo->getBankAccount($id);
            return $result;
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }

    public function checkStatus($id)
    {
        $flipRepo = new FlipService();
        $flipHelper = new FlipHelper();
        try {
            $response = $flipHelper->getStatus($id);
            $flipRepo->updateRequest((object)[
                'response' => json_encode($response),
                'status' => (($response->status === 'PENDING') ? 1 : 2),
                'receipt' => $response->receipt,
                'time_served' => $response->time_served
            ], $id);
            return $response;
        } catch (\Throwable $ex) {
            throw $ex;
        }
    }
}
