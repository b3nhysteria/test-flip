<?php

include_once(__DIR__ . "/../Repositories/Databases/index.php");

abstract class BaseModel
{
    private $factoryConnection;

    public function begin_transaction()
    {
        $this->factoryConnection = ConnectionFactory::getFactory()->getConnection();
        $this->factoryConnection->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    }

    public function commit()
    {
        $this->factoryConnection->commit();
    }

    public function rollback()
    {
        $this->factoryConnection->rollback();
    }

    public function getFactory()
    {
        return $this->factoryConnection;
    }
}
