<?php

interface IAccount
{
    public function withdraw($id, $amount);

    public function addDeposit($id, $amount);
}
