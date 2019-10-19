<?php
include_once("Databases/index.php");
class MerchantBankAccount
{
    function __construct()
    { }

    public function savingBankAccount($info)
    {
        $sql = 'insert into merchant_bank_account (id, merchant_id, bank_account, bank_id) values (?,?,?,?)';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("sssd", $info->id, $info->merchant_id, $info->account_number, $info->bank_id);
        $stmt->execute();
        $stmt->close();
    }
}
