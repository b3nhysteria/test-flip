<?php
include_once("Databases/index.php");
class FlipService
{
    function __construct()
    { }

    public function updateRequest($res, $id)
    {
        $sql = 'update flip_service set response = ? , status = ?, receipt = ?, time_served = ? where request_id = ?';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param(
            "sdssd",
            $res->response,
            $res->status,
            $res->receipt,
            $res->time_served,
            $id
        );
        $stmt->execute();
        $stmt->close();
    }

    public function savingRequest($info)
    {
        $sql = 'insert into flip_service (id, request, response, status, merchant_id, request_id, receipt, fee) values
        (?,?,?,?,?,?,?,?)';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new \Exception($conn->error);
        }
        $stmt->bind_param(
            "sssdsdsd",
            $info->id,
            $info->request,
            $info->response,
            $info->status,
            $info->merchant_id,
            $info->request_id,
            $info->sreceipt,
            $info->fee
        );
        $stmt->execute();
        $stmt->close();
    }
}
