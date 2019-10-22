<?php

interface IAccount
{
    public function withdraw($id, $amount, $account);

    public function addDeposit($id, $amount);
}
