<?php

namespace Src\Trader\Contracts;

interface TraderContract
{
    public function index();

    public function store(array $data);

    public function update($id, array $data);
}
