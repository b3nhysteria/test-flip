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

    public function getBankAccount($id)
    {
        $sql = 'select * from merchant_bank_account where merchant_id = ?';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}
