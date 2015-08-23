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
			
			/* �����������������ӿ� */
			if('zhongyu' == $team['codeform']){
				//�ж�Ϊ���鷢�뷽ʽ
				$codeform = 'zhongyu';
				$info = array(
					'req_seq' => $id, //������ˮ��
					'serv_code' => $team['serv_code'], //�����Ʒ���
					'phone_rece' => $order['mobile'], //�û��ֻ�����
					'notes' => '',
				);
				$result = $zhongyu->send($info);
				//print_r($result);exit;
				$doc = new DOMDocument();
				$doc->loadXML($result);
				$response_id = $doc->getElementsByTagName("id")->item(0)->nodeValue;
				if('0000' == $response_id){ //�ɹ�
					$sys_seq = $doc->getElementsByTagName("order_num")->item(0)->nodeValue;
					$msg = $doc->getElementsByTagName("comment")->item(0)->nodeValue;
					$mms = 1;
				}else{ //ʧ��
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
