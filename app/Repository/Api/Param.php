<?php
namespace App\Repository\Api;

use App\Model\Param as ParamModel;
use App\Repository\Lib\Repository;

class Param extends Repository
{
    protected $model;
    public function __construct(ParamModel $param)
    {
        $this->model = $param;
    }


}