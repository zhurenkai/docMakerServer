<?php

if (! function_exists('array_separate')) {

    /**
     * 用回调函数将数组分成两种
     * @param $array
     * @param Closure $callback
     * @return array
     */
    function array_separate($array, Closure $callback)
    {
        $classify1 = $classify2 = [];
        foreach($array as $v){
            $result = call_user_func($callback,$v);
            if($result){
                $classify1[] = $v;
            }else{
                $classify2[] = $v;
            }
        }
        return [$classify1,$classify2];
    }

}
