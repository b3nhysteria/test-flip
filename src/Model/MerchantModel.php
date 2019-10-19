<?php

include_once(__DIR__ . "/../Base/IAccount.php");
include_once(__DIR__ . "/../Base/BaseModel.php");
include_once(__DIR__ . "/../Utils/flip_helper.php");
include_once(__DIR__ . "/../Repositories/Merchant.php");
include_once(__DIR__ . "/../Repositories/MerchantBankAccount.php");

class MerchantModel extends BaseModel implements IAccount
{

    function __construct()
    { }

    function setUpNewMerchant($data)
    {
        try {
            $merchantRepo = new MerchantRepo();
            $merchantAccount = new MerchantBankAccount();
            $merchant = new stdClass();

            $this->begin_transaction();

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
            $this->rollback()();
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

    public function withdraw($id, $amount)
    {
        try {
            $merchantRepo = new MerchantRepo();
            $checking = $merchantRepo->checkingBalance($id, $amount);
            if ($checking) {
                $result = $merchantRepo->withdrawDeposit($id, $amount);
                return $result;
            } else {
                throw new \Exception('not enought balance or merchant is not found');
            }
        } catch (\Throwable $ex) {
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
}
