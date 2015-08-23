<?php
class ZThirdpart
{

	static public function CheckOrder($order) {
		$coupon_array = array('thirdpart');
		$team = Table::FetchForce('team', $order['team_id']);
		if (!in_array($team['delivery'], $coupon_array)) return;
		if ( $team['now_number'] >= $team['min_number'] ) {
			//init coupon create;
			$last = ($team['conduser']=='Y') ? 1 : $order['quantity'];
			$offset = max(5, $last);
			if ( $team['now_number'] - $team['min_number'] < $last) {
				$orders = DB::LimitQuery('order', array(
							'condition' => array(
								'team_id' => $order['team_id'],
								'state' => 'pay',
								),
							));
				foreach($orders AS $order) {
					self::Create($order);
				}
			}
			else{
				self::Create($order);
			}
		}
	}

	static public function Create($order) {
		$team = Table::Fetch('team', $order['team_id']);
		//$partner = Table::Fetch('partner', $order['partner_id']);
		$ccon = array('order_id' => $order['id']);
		$count = Table::Count('code', $ccon);
		
		require dirname(dirname(dirname(__FILE__))) . "/zhongyu/Zhongyu.class.php";
		$zhongyu = new Zhongyu();
		
		while($count<$order['quantity']) {
			$id = date('YmdHis', time()) . rand(100000, 999999);
			$id = Utility::VerifyCode($id);
			$cv = Table::Fetch('code', $id);
			if ($cv) continue;
			
			/* 这里请求第三方发码接口 */
			if('zhongyu' == $team['codeform']){
				//判断为中娱发码方式
				$codeform = 'zhongyu';
				$info = array(
					'req_seq' => $id, //请求流水号
					'serv_code' => $team['serv_code'], //中娱产品编号
					'phone_rece' => $order['mobile'], //用户手机号码
					'notes' => '',
				);
				$result = $zhongyu->send($info);
				//print_r($result);exit;
				$doc = new DOMDocument();
				$doc->loadXML($result);
				$response_id = $doc->getElementsByTagName("id")->item(0)->nodeValue;
				if('0000' == $response_id){ //成功
					$sys_seq = $doc->getElementsByTagName("order_num")->item(0)->nodeValue;
					$msg = $doc->getElementsByTagName("comment")->item(0)->nodeValue;
					$mms = 1;
				}else{ //失败
					$msg = $doc->getElementsByTagName("comment")->item(0)->nodeValue;
					$mms = 0;
				}
			}
			
            $code = array(
					'id' => $id,
					'sys_seq' => $sys_seq,
					'user_id' => $order['user_id'],
					'partner_id' => $team['partner_id'],
					'city_id' => $team['city_id'],
					'order_id' => $order['id'],
					'team_id' => $order['team_id'],
					'expire_time' => $team['expire_time'],
					'create_time' => time(),
					'msg' => $msg,
					'mms' => $mms,
                    'codeform' => $codeform,
				);
			if(DB::Insert('code', $code))
				//sms_coupon($coupon);
			$count = Table::Count('code', $ccon);
		}
	}
}
