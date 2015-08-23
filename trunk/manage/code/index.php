<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager();
$daytime = strtotime(date('Y-m-d'));
$condition = array(
	'status' => 0,
	'expire_time >= ' . $daytime,
);
/* fiter */
$id = strval($_GET['id']);
$team_id = strval($_GET['team_id']);
$order_id = strval($_GET['order_id']);
$uname = strval($_GET['uname']);
if ($team_id) { $condition['team_id'] = $team_id; } else { $team_id = null; }
if ($order_id) { $condition['order_id'] = $order_id; } else { $order_id = null; }
if ($id) {
	$condition[] = "id like '%".mysql_escape_string($id)."%'";
}
if ($uname) {
	$ucon = array( "email like '%".mysql_escape_string($uname)."%' OR username like '%".$uname."%'");
	$uhave = DB::LimitQuery('user', array( 'condition' => $ucon,));
	if ($uhave) $condition['user_id'] = Utility::GetColumn($uhave, 'id');
}
/* end */

$count = Table::Count('code', $condition);
list($pagesize, $offset, $pagestring) = pagestring($count, 20);

$codes = DB::LimitQuery('code', array(
	'condition' => $condition,
	'order' => 'ORDER BY team_id DESC, id ASC',
	'size' => $pagesize,
	'offset' => $offset,
));

$users = Table::Fetch('user', Utility::GetColumn($codes, 'user_id'));
$teams = Table::Fetch('team', Utility::GetColumn($codes, 'team_id'));
$orders = Table::Fetch('order', Utility::GetColumn($codes, 'order_id'));
$selector = 'index';
$status = '未消费';
include template('manage_code_index');
