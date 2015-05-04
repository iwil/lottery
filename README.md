# lottery
/**
 * 抽奖函数
 * @param $prize //要抽奖的数组 rate为概率字段名
 * array example
 * $prize = array(
        '01' => array('name'  =>'礼品1', 'rate' => '12.04%'),
        '02' => array('name'  =>'礼品2', 'rate' => '26.8%'),
        '03' => array('name'  =>'礼品3', 'rate' => '37.2%'),
        '04' => array('name'  =>'礼品4', 'rate' => '23.96%')
    );
 * @param $type 
 * $type = 1 返回抽中的 $rand_id(如'01', '02');  
 * $type = 2 返回抽中的奖品数组 array('name'=>'xx', 'rate'=>'22%')并添加字段array('prize_id'=>$rand_id);
 * @param $rate_sum  //概率之和
 * @param $gcdrs
 * @return $result
 */
