<?php
/**
 *  供货商文章页
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$smarty->assign('ur_here', "通知文章");

/* 供货商文章 */
$article_id = intval($_REQUEST['id']);
$sql = "select * from ". $ecs->table('article') ." where article_id = '$article_id' ";
$article = $db->getRow($sql);

$smarty->assign('article',  $article);
$smarty->display('start_article.htm');

?>
