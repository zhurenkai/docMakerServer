<?php
namespace App\Repository\Param;
use App\Model\Param;
use App\Repository\Lib\Repository;
class ParamRepo extends Repository
{
    public function __construct()
    {
        $this->model = new Param();
    }
}