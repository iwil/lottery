<?php
/**
 * Created by PhpStorm.
 * User: yyc
 * Date: 2015/6/26 9:05
 * 抽奖函数
 * 示例奖品数组
 * $prize = array(
 *           '01' => array('name'  =>'礼品1', 'rate' => '12.03%'),
 *          '02' => array('name'  =>'礼品2', 'rate' => '26.8%'),
 *           '03' => array('name'  =>'礼品3', 'rate' => '37.2%'),
 *           '04' => array('name'  =>'礼品4', 'rate' => '23.96%')
 *       );
 *
 * @param array $prize 抽奖的数组 $prize['rate']为概率
 * @param int $type
 * @param float $rate_sum     概率之和
 * @param string $sum_error   概率之和不为1
 * @param float $len_max   $prize['rate']去掉%后最长小数位数
 * @param int $rand_sum
 * @param int $gcdrs       $prize['option']的最大公约数
 * @return ($type = 1) int
 * @return ($type = 2) array
 */
function getRandResult($prize, $type = 1){
    $rate_sum = 0;
    $len_max = 0;
    foreach ($prize as $v) {
        $rate_sum += $v['rate'];
        $float_rate = (float)str_replace('%', '', $v['rate']);
        $length = strlen(substr(strrchr($float_rate, "."), 1));
        $len_max = ($len_max > $length )? $len_max : $length;
    }
    if($rate_sum != 100){
        $sum_error = 'The rate sum is '.$rate_sum.'%, Winning rate should upto 100%';
        echo $sum_error;
        exit;
    }
    $arr = array();
    $rand_sum = 0;
    foreach ($prize as $k => & $v) {
        $arr[$k] = $v['rate'] * pow(10, $len_max);
        $v['option'] = $v['rate'] * pow(10, $len_max);
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
            //$prize[$rand_id]['prize_id']  = $rand_id;
            $result = $prize[$rand_id];
            break;
        default:
            break;
    }
    return $result;
}
/**
 * 随机抽奖获取奖品id
 * @param array $prize
 * @param int $rand_sum
 * @return int $rand_id
 *
 */
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
/**
* 计算两个数的最大公约数
 * @param int $a
 * @param int $b
 * @return int
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
/**
* 计算一组数的最大公约数
* @param int $a
* @param int $b
* @return int
*/
function gcd_array($array, $a = 0)
{
    $b = array_pop($array);
    return ($b === null) ?
        (int)$a :
        gcd_array($array, gcd($a, $b));
}
