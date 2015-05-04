<?php
/**
 * 抽奖函数
 * @param $prize //要抽奖的数组 rate为概率字段名
 * @param $type 1返回抽中的$rand_id(如'01', '02'); 2返回抽中的奖品数组array('name'=>'xx', 'rate'=>'22%')并添加字段array('prize_id'=>$rand_id);
 * @param $rate_sum  //概率之和
 * @param $gcdrs
 * @return $result
 */
function getRandResult($prize, $type = 1){
    $prize = array(
        '01' => array('name'  =>'礼品1', 'rate' => '12.04%'),
        '02' => array('name'  =>'礼品2', 'rate' => '26.8%'),
        '03' => array('name'  =>'礼品3', 'rate' => '37.2%'),
        '04' => array('name'  =>'礼品4', 'rate' => '23.96%')
    );

    $rate_sum = 0;   //概率之和
    $max = 0;        //$rate去掉%后最长小数位数
    foreach ($prize as $v) {
        $rate_sum += $v['rate'];
        $float_rate = (float)str_replace('%', '', $v['rate']);
        $length = strlen(substr(strrchr($float_rate, "."), 1));
        $max = ($max > $length )? $max : $length;
    }

    if($rate_sum != 100){
        $lan = 'The rate sum is '.$rate_sum.'%, Winning rate should upto 100%';
        echo $lan;
        exit;
    }

    $arr = array();
    $rand_sum = 0;
    foreach ($prize as $k => & $v) {
        $arr[$k] = $v['rate'] * pow(10, $max);
        $v['option'] = $v['rate'] * pow(10, $max);
        $rand_sum += $v['option'];
    }

    $gcdrs = gcd_array($arr);
    if($gcdrs > 1){
        $rand_sum = $rand_sum/$gcdrs;
        foreach($prize as & $v){
           $v['option'] /= $gcdrs;
        }
    }

    $rand_id = getRandId($prize, $rand_sum);
    $result = '';
    switch($type){
        case 1:
            $result = $rand_id;
            break;
        case 2:
            $prize[$rand_id]['prize_id']  = $rand_id;
            $result = $prize[$rand_id];
            break;
        default:
            break;
    }
    return $result;
    //var_dump($prize, $gres, $result);exit;
}

function getRandId($prize, $rand_sum)
{
    $rand_num = mt_rand(1, $rand_sum);
    $count = 0;
    $rand_id = '';
    foreach($prize as $k => $v)
    {
        $count += $v['option'];
        if($count >= $rand_num)
        {
            $rand_id = $k;
            break;
        }
    }
    return $rand_id;
}


/*
* function gcd()
*
* returns greatest common divisor
* between two numbers
* tested against gmp_gcd()
*/
function gcd($a, $b)
{
    if ($a == 0 || $b == 0)
        return abs( max(abs($a), abs($b)) );

    $r = $a % $b;
    return ($r != 0) ?
        gcd($b, $r) :
        abs($b);
}

/*
* function gcd_array()
*
* gets greatest common divisor among
* an array of numbers
*/
function gcd_array($array, $a = 0)
{
    $b = array_pop($array);
    return ($b === null) ?
        (int)$a :
        gcd_array($array, gcd($a, $b));
}





