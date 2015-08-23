<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

$action = trim($_GET['action']);
require dirname(__FILE__) . '/Zhongyu.class.php';
$zhongyu = new Zhongyu();

//重发中娱码
if('resend' == $action){
	$id = strval($_GET['id']);
    $code = Table::Fetch('code', $id);
    if($code['mms'] >= 5){
        json('发送失败！重发数量已超过5次！', 'alert');
    }
	$info = array(
		'req_seq' => $code['id'],
	);
    $result = $zhongyu->repeat($info);
	$doc = new DOMDocument();
	$doc->loadXML($result);
	$response_id = $doc->getElementsByTagName("id")->item(0)->nodeValue;
	$comment = $doc->getElementsByTagName("comment")->item(0)->nodeValue;
	if('0000' == $response_id){
		json($comment, 'alert');
	}else{
		json('重发失败 ' . $comment, 'alert');
	}
}


//重新请求中娱条码
elseif('recreate' == $action){
	$id = strval($_GET['id']);
    $code = Table::Fetch('code', $id);
	$team = Table::Fetch('team', $code['team_id']);
	$order = Table::Fetch('order', $code['order_id']);
	$info = array(
		'req_seq' => $code['id'],
		'serv_code' => $team['serv_code'],
		'phone_rece' => $order['mobile'],
		'notes' => '',
	);
	$result = $zhongyu->send($info);
	$doc = new DOMDocument();
	$doc->loadXML($result);
	$response_id = $doc->getElementsByTagName("id")->item(0)->nodeValue;
	if('0000' == $response_id || '1101' == $response_id){
		$sys_seq = $doc->getElementsByTagName("order_num")->item(0)->nodeValue;
		$msg = $doc->getElementsByTagName("comment")->item(0)->nodeValue;
		Table::UpdateCache('code', $code['id'], array(
                'sys_seq' => $sys_seq,
                'mms' => array('`mms` + 1'),
                'msg' => $msg
			));
		json(array(
			array('data' => "发送成功！", 'type'=>'alert'),
			array('data' => null, 'type'=>'refresh'),
		  ), 'mix');
	}else{
		$msg = $doc->getElementsByTagName("comment")->item(0)->nodeValue;
		json($msg, 'alert');
	}
}


//撤销中娱码暂未提供……