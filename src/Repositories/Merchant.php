<?php
include_once("Databases/index.php");
class MerchantRepo
{
    function __construct()
    { }

    public function saveMerchant($merchant)
    {
        $sql = 'insert into merchant (id, name, balance) values (?,?,?)';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("ssd", $merchant->id, $merchant->name, $merchant->balance);
        $stmt->execute();
        $stmt->close();
    }

    public function getList($limit, $offset)
    {
        $sqlQuery = 'select * from merchant order by created_at desc limit ?,?';
        $sql = 'select count(1) as total_data from merchant';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $result = $conn->query($sql);
        $meta = $result->fetch_object();
        $stmt = $conn->prepare($sqlQuery);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("dd", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return (object) ['data' => $data, 'meta' => $meta];
    }

    public function addDeposit($id, $balance)
    {
        $sql = 'update merchant set balance = balance + ? where id = ?';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("ds", $balance, $id);
        $stmt->execute();
    }

    public function checkingBalance($id, $amount, $account)
    {
        $sql = 'select * from
        merchant m left join merchant_bank_account mba
        on m.id = mba.merchant_id
        where m.id = ? and m.balance >= ? and mba.id = ?
        FOR UPDATE';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("sds", $id, $amount, $account);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_object();
        return $result;
    }

    public function withdrawDeposit($id, $balance)
    {
        $sql = 'update merchant set balance = balance - ? where id = ?';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param("ds", $balance, $id);
        $stmt->execute();
    }
}
