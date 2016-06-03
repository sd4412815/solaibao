<?php

if (!defined('IN_ECS'))
{
die('Hacking attempt');
}
function get_pailie_zuhe($auto_attr_temp)
{
if (empty($auto_attr_temp))
{
return array();
}
foreach ($auto_attr_temp as $attribute_value)
{
$CombinList[$attribute_value['attr_name']][] = array('goods_attr_id'=>$attribute_value['goods_attr_id'],'attr_value'=>$attribute_value['attr_value']);
}
$CombineCount = 1;
foreach($CombinList as $Key =>$Value)
{
$CombineCount *= count($Value);
}
$RepeatTime = $CombineCount;
foreach($CombinList as $ClassNo =>$StudentList)
{
$RepeatTime = $RepeatTime / count($StudentList);
$StartPosition = 1;
foreach($StudentList as $Student)
{
$TempStartPosition = $StartPosition;
$SpaceCount = $CombineCount / count($StudentList) / $RepeatTime;
for($J = 1;$J <= $SpaceCount;$J ++)
{
for($I = 0;$I <$RepeatTime;$I ++)
{
$Result[$TempStartPosition +$I][$ClassNo] = $Student;
}
$TempStartPosition += $RepeatTime * count($StudentList);
}
$StartPosition += $RepeatTime;
}
}
return $Result;
}
?>