<?php
include_once("Databases/index.php");
class BankRepo
{
    function __construct()
    { }

    public function getBanks()
    {
        $sql = 'select * from bank';
        $conn = ConnectionFactory::getFactory()->getConnection();
        $result = $conn->query($sql);
        if ($result->num_rows === 0) return [];
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
}
