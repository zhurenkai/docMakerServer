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

    public function updateMany($list, $condition)
    {
        $table = $this->model->getTable();
        $columns = (end($list));
        $in = array_column($list, $condition);
        // 当判断条件不是数字时
        if (!is_numeric(end($in))) {
            $in = implode('","', $in);
            $in = '"' . $in . '"';
        } else {
            $in = implode(',', $in);
        }
        // 过滤非法字段
        $fillable = $this->model->getFillable();
        $columns = array_intersect($fillable, array_keys($columns));

        $sql = "UPDATE $table SET ";
        foreach ($columns as $column) {
            // 跳过判断条件
            if ($column == $condition) {
                continue;
            }

            $sql .= " $column= CASE $condition ";
            foreach ($list as $item) {
                if (!is_numeric($item[$condition])) {
                    $item[$condition] = "'$item[$condition]'";
                }
                $sql .= " WHEN $item[$condition] THEN  ";
                if (!is_numeric($item[$column])) {
                    $sql .= " '$item[$column]' ";
                } else {
                    $sql .= " $item[$column] ";
                }
            }
            $sql .= " END ,";
        }
        $sql .= " updated_at = '" . date('Y-m-d H:i:s') . "' ";
        $sql .= " WHERE $condition IN ($in) ";
        $res = DB::statement($sql);
        return $res;
    }

    public function bindTimeInfo(&$insert)
    {
        $time_now = date('Y-m-d H:i:s');
        array_walk($insert,function(&$item)use($time_now){
            $item['created_at'] = $item['updated_at'] = $time_now;
        });
    }
}