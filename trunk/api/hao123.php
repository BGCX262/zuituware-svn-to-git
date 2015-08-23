<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');
header('Content-Type: application/xml; charset=UTF-8');
$daytime = strtotime(date('Y-m-d'));
$si = array(
	'sitename' => $INI['system']['sitename'],
	'wwwprefix' => $INI['system']['wwwprefix'],
	'imgprefix' => $INI['system']['imgprefix'],
);

//查询数据库
$dbroot = str_replace("\\", '/', dirname(dirname(__FILE__)) . '/include/configure/db.php');
include($dbroot);
$conn = mysql_connect($value['host'], $value['user'], $value['pass']); 
mysql_select_db($value['name'], $conn); //选择数据库 

/* 筛选where条件完 */
$sql = "select t.id, t.title, t.cate_api, t.team_price, t.market_price, t.product, t.now_number, 
		t.image, t.begin_time, t.end_time, t.expire_time, t.notice, 
		c.name as city_name, c.ename as city_ename, 
		p.title as partner_title, p.address as partner_address, p.phone as partner_phone, p.longlat as partner_longlat, p.open_time as partner_open_time, p.traffic_info as partner_traffic_info  
		from `team` as t 
		left join `category` as c on t.city_id = c.id 
		left join `partner` as p on t.partner_id = p.id 
		where t.team_type = 'normal' and t.begin_time <= " . $daytime . " and t.end_time > " . $daytime . "  
		order by id DESC ";

$result = @mysql_query($sql, $conn);
while ($row=mysql_fetch_assoc($result)) {
	 $teams[] = $row;
}
//print_r($teams);exit;
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?> \t\n";
$xml .= "<urlset>";
foreach($teams AS $team){
	$xml .= "<url>";
		$xml .= "<loc>" . $si['wwwprefix'] . "/team.php?id=" . $team['id'] . "</loc>";
		//$xml .= "<waploc>http://g.51uutuan.com/team.php?id=" . $team['id'] . "</waploc>";
		$xml .= "<waploc></waploc>";
		$xml .= "<data>";
			$xml .= "<display>";
				$xml .= "<website>" . $si['sitename'] . "</website>";
				$xml .= "<siteurl>" . $si['wwwprefix'] . "/" . $team['city_ename'] . "</siteurl>";
				$city_name = $team['city_name'] ? $team['city_name'] : '全国'; //处理全国
				$xml .= "<city>" . $city_name . "</city>";
				
				/* 处理分类 */
				//（分别对应——1代表"餐饮美食"，2代表"休闲娱乐"，3代表"生活服务"，4代表"网上购物"，5代表"酒店旅游"）
				$cate_api_arr = unserialize($team['cate_api']);
				$cate_baidu_arr = explode('>', $cate_api_arr['baidu']);
				$cate_baidu_var = $cate_baidu_arr[0];//已选择的百度API一级分类值
				switch ($cate_baidu_var) {
					case '餐饮美食':
						$cate_baidu_var = '1';
					break;
					case '休闲娱乐':
						$cate_baidu_var = '2';
					break;
					case '生活服务':
						$cate_baidu_var = '3';
					break;
					case '网上购物':
						$cate_baidu_var = '4';
					break;
					case '酒店旅游':
						$cate_baidu_var = '5';
					break;
					case '丽人':
						$cate_baidu_var = '6';
					break;
					default:
						$cate_baidu_var = '3';
				}
				$category = $cate_baidu_var;
				$xml .= "<category>" . $cate_baidu_var . "</category>";
				$xml .= "<subcategory>" . $cate_baidu_arr[1] . "</subcategory>";
				
				/* 三级分类 */
				if($category == 1){ //餐饮美食 三级分类为“餐饮特色”
					$xml .= "<characteristic>" . $cate_baidu_arr[2] . "</characteristic>";
					$xml .= "<destination></destination>";
					$xml .= "<thrcategory></thrcategory>";
				}
				if($category == 4){ //网上购物 三级分类为“网购商品三级分类”
					$xml .= "<characteristic></characteristic>";
					$xml .= "<destination></destination>";
					$xml .= "<thrcategory>" . $cate_baidu_arr[2] . "</thrcategory>";
				}
				if($category == 5){ //酒店旅游 三级分类为“旅游目的地”
					$xml .= "<characteristic></characteristic>";
					$xml .= "<destination>" . $cate_baidu_arr[2] . "</destination>";
					$xml .= "<thrcategory></thrcategory>";
				}
				/* 判断三级分类 end */
				$xml .= "<dpshopid></dpshopid>";
				$xml .= "<range>" . $team['qu_name'] . "</range>";
				$xml .= "<address><![CDATA[" . $team['partner_address'] . "]]></address>";
				if($team['partner_longlat']){
					$longlat = explode(',', $team['partner_longlat']);
					$longlat = $longlat[1] . ',' . $longlat[0];
					$xml .= "<coords>" . $longlat . "</coords>";
				}else {
					$xml .= "<coords></coords>";
				}
				/* 处理分类 end */
				$dex++;
				if($dex==1){
					$xml .= "<major>" . $dex . "</major>";
				}else{
					$xml .= "<major>0</major>";
				}
				
				$xml .= "<title><![CDATA[" . $team['title'] . "]]></title>";
				$xml .= "<shortTitle><![CDATA[" . $team['product'] . "]]></shortTitle>";
				$xml .= "<image>" . team_image($team['image']) . "</image>";
				$xml .= "<startTime>" . $team['begin_time'] . "</startTime>";
				$xml .= "<endTime>" . $team['end_time'] . "</endTime>";
				$xml .= "<value>" . $team['market_price'] . "</value>";
				$xml .= "<price>" . $team['team_price'] . "</price>";
				$rebate = round($team['team_price'] / $team['market_price'] * 10, 2);
				$xml .= "<rebate>" . $rebate . "</rebate>";
				$xml .= "<bought>" . $team['now_number'] . "</bought>";
				
				$xml .= "<name><![CDATA[" . $team['product'] . "]]></name>";
				$xml .= "<spendEndTime><![CDATA[" . $team['expire_time'] . "]]></spendEndTime>";
				$xml .= "<reservation>1</reservation>";
				$xml .= "<tips><![CDATA[" . $team['notice'] . "]]></tips>";
				$xml .= "<seller><![CDATA[" . $team['partner_title'] . "]]></seller>";
				$xml .= "<phone><![CDATA[" . $team['partner_phone'] . "]]></phone>";
				$xml .= "<shops>";
				$xml .= "<shop>";
				$xml .= "<shopSeller><![CDATA[" . $team['partner_title'] . "]]></shopSeller>";
				$xml .= "<shopAddress><![CDATA[" . $team['partner_address'] . "]]></shopAddress>";
				$xml .= "<shopPhone><![CDATA[" . $team['partner_phone'] . "]]></shopPhone>";
				$xml .= "<openTime><![CDATA[" . $team['partner_open_time'] . "]]></openTime>";
				$xml .= "<trafficInfo><![CDATA[" . $team['partner_traffic_info'] . "]]></trafficInfo>";
				$xml .= "<shopRange><![CDATA[" . $team['qu_name'] . "]]></shopRange>";
				$xml .= "<shopCoords>" . $longlat . "</shopCoords>";
				$xml .= "<shopDpshopid></shopDpshopid>";
				$xml .= "</shop>";
				$xml .= "</shops>";
			$xml .= "</display>";
		$xml .= "</data>";
	$xml .= "</url>";
}
$xml .= "</urlset>";
echo $xml;