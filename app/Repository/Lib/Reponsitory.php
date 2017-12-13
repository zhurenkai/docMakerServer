<?php

namespace App\Repository\Lib;

use Illuminate\Support\Facades\DB;

class Repository
{
    protected $repo;
    protected $model;
    public function __construct()
    {
    }

    public function updateMany($list,$condition)
    {
       $table = $this->model->getTable();
       $columns = (end($list));
       $in = array_column($list,$condition);
       $in = implode(',',$in);
       $fillable = $this->model->getFillable();
       $columns = array_intersect($fillable,array_keys($columns));
       $sql = "UPDATE $table SET ";
       foreach ($columns as $column){
           $sql .= " $column= CASE $condition ";
           foreach ($list as $item){
               $sql .= " WHEN $item[$condition] THEN  ";
               if(!is_int($item[$column])){
                   $sql .= " '$item[$column]' ";
               }else{
                   $sql .= " $item[$column] ";
               }
           }
           $sql .=" END ,";
       }
       $sql = rtrim($sql,',');
       $sql .= " WHERE $condition IN ($in) ";
       $res = DB::update($sql);
       return $res;
    }
}