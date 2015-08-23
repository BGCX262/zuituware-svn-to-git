<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');
class ZhongyuModel{
	
	
	/**
    * 修改团购下线时间 
    */
	function edit_product_end_time($product_num, $end_time){
		$team_id = DB::Exist('team', array('serv_code' => $product_num));
		if(!$team_id){
			return $this->response('edit_product_end_time', '1006', '合作方无此产品的信息');
		}
		DB::Query("update team set end_time = " . $end_time . " where serv_code = " . $product_num);
		return $this->response('edit_product_end_time', '0000', '修改团购下线时间成功');
	}
	
	/**
	* 同步项目
	*/
	function sync_team($data){
		$city_id = DB::Exist('category', array('name' => $data['city']));
		if(!$city_id){
			return $this->response('sync_team', '1002', '合作方没有开通该城市！');
		}
		$team_id = DB::Exist('team', array('serv_code' => $data['zhongyu_id']));
		if($team_id){
			return $this->response('sync_team', '1003', '已同步过，第三方ID为：' . $team_id);
		}
		
		/* 此处特别注意，分类ID请对应 */
		switch ($data['group']){
			case '餐饮美食':
				$group_id = 2;
				break;
			case '休闲娱乐':
				$group_id = 37;
				break;
			case '生活服务':
				$group_id = 39;
				break;
			//…………
			default:
				$group_id = 42;
				break;
		}
		
		$partners = $data['partners']['partner'];
		if(isset($partners['title'])) { //这里表示只有一个商家分店信息
			$partner = array(
				'username' => $partners['title'],
				'password' => '123456',
				'title' => $partners['title'],
				'phone' => $partners['tel'],
				'address' => $partners['address'],
				'route' => $partners['route'],
			);
		} else {
			$partner = array( //如果不支持商家多店模式，则取第一个即可
				'username' => $partners[0]['title'],
				'password' => '123456',
				'title' => $partners[0]['title'],
				'phone' => $partners[0]['tel'],
				'address' => $partners[0]['address'],
				'route' => $partners[0]['route'],
			);
		}
		$partner_id = DB::Exist('partner', array('title' => $partner['title']));
		if(!$partner_id){
			$item['business']['password'] = '123456';
			
			$table = new Table('partner', $partner);
			$table->SetStrip('location', 'other');
			$table->username = $partner['title'];
			$table->title = $partner['title'];
			$table->location="客服预约：".$partner['phone']."<br/>"."地址：".$partner['address']."<br/>交通：".$partner['route'];
			$table->create_time = time();
			$table->user_id = 1;
			$table->password = ZPartner::GenPassword($table->password);
			$table->group_id = 0;
			$table->city_id = 0;
			$table->open = 'N';
			$table->display = 'N';
			$table->insert(array(
				'username', 'user_id', 'city_id', 'title', 'group_id',
				'create_time',
				'location', 'other', 'homepage', 'contact', 'mobile', 'phone',
				'password', 'address', 'open', 'display',
			));
			$partner_id = DB::GetInsertId();
		}
		
		$team = array(
			'title' => $data['title'], //团购标题
			'product' => $data['product'], // 产品名称
			'group_id' => intval($group_id), //团购一级分类名称
			'city_id' => intval($city_id), //团购城市名称
			'notice' => $data['notice'], //特别提示
			'summary' => $data['summary'],
			'begin_time' => intval($data['begin_time']), //团购开始时间
			'end_time' => intval($data['end_time']), //团购结束时间
			'expire_time' => intval($data['expire_time']), //团购券过期时间
			'min_number' => intval($data['min_number']), //成团数量
			'max_number' => intval($data['max_number']), //团购总数量，库存
			'permin_number' => intval($data['user_min_number']), //最低购买数量，必须大于1
			'per_number' => intval($data['user_per_number']), //每人限购，0为不限制
			'market_price' => floatval(sprintf("%0.2f", $data['market_price'])), //市场价
			'team_price' => floatval(sprintf("%0.2f", $data['team_price'])), //团购价
			'partner_id' => intval($partner_id),
			'user_id' => 1,
			'system' => 'Y',
			'now_number' => 0,
			'pre_number' => 0,
			'credit' => 0,
			'card' => 0,
			'fare' => 0,
			'farefree' => 0,
			'bonus' => 0,
			'state' => 'none',
			'conduser' => 'N',
			'buyonce' => 'N',
			'sort_order' => 0,
			'team_type' => 'normal',
			'delivery' => 'thirdpart', //发码方式为第三方
			'codeform' => 'zhongyu', //发码公司为中娱
			'serv_code' => $data['zhongyu_id'], //中娱产品ID
		);
		$regex = '%<img[^>]*?src="(http://.*?)"[^>]*?>%ie';
		$replace = "'<a target=\"_blank\" href=\"'.(\$src=downremote_image('$1')).'\"><img src=\"'.\$src.'\" /></a>'";
		$team['detail'] = preg_replace($regex, $replace, $data['detail']);
		$insert = array(
			'title', 'market_price', 'team_price', 'end_time', 
			'begin_time', 'expire_time', 'min_number', 'max_number', 
			'summary', 'notice', 'per_number', 'product',
			'flv', 'now_number',
			'detail', 'userreview', 'card', 'systemreview', 
			'conduser', 'buyonce', 'bonus', 'sort_order',
			'delivery', 'mobile', 'address', 'fare', 
			'express', 'credit', 'farefree', 'pre_number',
			'user_id', 'city_id', 'group_id', 'partner_id',
			'team_type', 'sort_order', 'farefree', 'state',
			'condbuy', 'codeform', 'serv_code'
		);
		$insert = array_unique($insert);
		$table = new Table('team', $team);
		$table->SetStrip('detail', 'systemreview', 'notice');
		$team_id = $table->insert($insert);
		if(!$team_id){
			return $this->response('sync_team', '1004', '插入团购失败！');
		}
		
		
		return $this->response('sync_team', '0000', '同步成功！');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	* 返回信息给中娱的封装方法
	*/
	public function response($response_type, $id, $comment){
		$xml .= '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<business_trans>';
		$xml .= '<response_type>' . $response_type . '</response_type>';
		$xml .= '<result>';
		$xml .= '<id>' . $id . '</id>';
		$xml .= '<comment>' . $comment . '</comment>';
		$xml .= '</result>';
		$xml .= '</business_trans>';
		return $xml;
	}
}




function geturl($url, $save_to = false)
{
	if(!strpos($url, '://')) return 'Invalid URI';
	$content = '';
	if(function_exists('curl_init'))
	{
		$handle = curl_init();
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		curl_setopt($handle, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		$content = curl_exec($handle);
		curl_close($handle);
	}
	elseif(function_exists('fsockopen'))
	{
		$urlinfo = parse_url($url);
		$host = $urlinfo['host'];
		$str = explode($host, $url);
		$uri = $str[1];
		unset($urlinfo, $str);
		$content = '';
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		if(!$fp)
		{
			$content = 'Can Not Open Socket...';
		}
		else
		{
			stream_set_timeout( $fp , 10 ) ; 

			$out = "GET ".$uri."/  HTTP/1.1\r\n";
			$out.= "Host: $host \r\n";
			$out.= "Accept: */*\r\n";
			$out.= "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; InfoPath.1)\r\n)";
			$out.= "Connection: Keep-Alive\r\n\r\n";
			fputs($fp, $out);
			while (!feof($fp))
			{
				$status = stream_get_meta_data( $fp ) ;
				
				if( $status['timed_out'] ) break;
				$content .= fgets($fp, 4069);
			}
			fclose($fp);
		}
	}
	if($save_to) 
	{
		file_put_contents($save_to, $content);
		return true;
	}
	else
		return $content;
}

function downremote_image($url) {
	$pathinfo = pathinfo ( $url );
	if ($pathinfo ['dirname'] == '.') {
		return $url;
	}
	
	$dir = 'team/' . date ( 'Y' ). '/';
	
	$upload_dir = dirname ( __FILE__ ) . '/../static/' . $dir ;
	if (! file_exists ( $upload_dir )) {
		mkdir ( $upload_dir);
    }
        
    $dir = 'team/' . date ( 'Y' ). '/' . date('md') . '/';
	
	$upload_dir = dirname ( __FILE__ ) . '/../static/' . $dir ;
	if (! file_exists ( $upload_dir )) {
		mkdir ( $upload_dir);
    }
			
	$basename = $pathinfo ['basename'];

	if (strpos ( $basename, '?' ) !== false) {
		$basename = str_replace ( array ('?', '=', '.' ), '_', $basename ) . '.jpg';
	}
	
	if (! preg_match ( '/(.jpg)|(.gif)|(.png)|(.jpeg)|(.bmp)/si', $basename )) {
		return $url;
	}
	$time = md5($url);
	$save_to = $upload_dir . $time . $basename;
	$fullpath = '/static/'. $dir . $time . $basename;
	if (file_exists ( $save_to ))
		return $fullpath; 
	if (geturl ( $url, $save_to ))
		return $fullpath;
	else
		return $url;
}