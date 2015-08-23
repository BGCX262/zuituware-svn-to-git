<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager();
need_auth('admin|market');

$action = strval($_GET['action']);
$id = abs(intval($_GET['id']));
$team = Table::Fetch('team', $id);

//处理导航分类下拉框联动效果
if ( 'baidu' == $action ) {
	$first = $_GET['first'];
	require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
	$second = $baidu[$first];
	foreach($second as $key=>$val){
	   if('网上购物' == $first || '餐饮美食' == $first || '旅游住宿' == $first){
	        $v[] = '<option value='.$key.'>'.$key.'</option>'; 
	   }else{
            $v[] = '<option value='.$val.'>'.$val.'</option>';
	   }
	}
	$v = join('<br/>', $v);
	$d = array(
			'html' => "<option value=\"\">---请选择二级分类---</option>".$v,
			'id' => 'cate_api_baidu_second',
			);
	json($d, 'updater');
	//json($second, 'alert');
}
//百度分类二级导航联动效果
elseif ( 'baidu2' == $action ) {
    $first = $_GET['first'];
    $second = $_GET['second'];
    //json($first, 'alert');
    require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
    foreach($baidu[$first][$second] AS $key=>$val){
        $v[] = '<option value='.$val.'>'.$val.'</option>';
    }
    $v = join('', $v);
    //json($v, 'alert');
	$d = array(
			'html' => "<select name=\"cate_api_baidu_third\" class=\"f-input\" style=\"width:120px\">".$v."</select>",
			'id' => 'cate_api_baidu_second_select',
			);
	json($d, 'updater');
}

elseif ( 'tuan360' == $action ) {
	$first = $_GET['first'];
	require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
	$second = $tuan360[$first];
	foreach($second as $key=>$val){
		$v[] = "<input type=\"checkbox\" name=\"cate_api_360_second[]\" id=\"360_$key\" value=\"$val\" /><label style=\"width:40px;float:none\" for=\"360_$key\">$val</label>";
	}
	$v = join('', $v);
	$d = array(
			'html' => $v,
			'id' => 'cate_api_360_second',
			);
	json($d, 'updater');
	//json($second, 'alert');
}

elseif ( 'sogou' == $action ) {
	$first = $_GET['first'];
	require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
	$second = $sogou[$first];
	foreach($second as $key=>$val){
	   if('休闲娱乐' == $first || '网上购物' == $first){
	        $v[] = '<option value='.$key.'>'.$key.'</option>'; 
	   }else{
            $v[] = '<option value='.$val.'>'.$val.'</option>';
	   }
	}
	$v = join('<br/>', $v);
	$d = array(
			'html' => "<option value=\"\">---请选择二级分类---</option>".$v,
			'id' => 'cate_api_sogou_second',
			);
	json($d, 'updater');
	//json($second, 'alert');
}
//搜狗分类二级导航联动效果
elseif ( 'sogou2' == $action ) {
    $first = $_GET['first'];
    $second = $_GET['second'];
    //json($first, 'alert');
    require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
    foreach($sogou[$first][$second] AS $key=>$val){
        $v[] = '<option value='.$val.'>'.$val.'</option>';
    }
    $v = join('', $v);
    //json($v, 'alert');
	$d = array(
			'html' => "<select name=\"cate_api_sogou_third\" class=\"f-input\" style=\"width:120px\">".$v."</select>",
			'id' => 'cate_api_sogou_second_select',
			);
	json($d, 'updater');
}
//处理导航分类下拉框联动效果
elseif ( 'jinshan' == $action ) {
	$first = $_GET['first'];
	require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
	$second = $jinshan[$first];
	foreach($second as $key=>$val){
		if('商品团购' == $first || '餐饮美食' == $first || '旅游酒店' == $first){
			$v[] = '<option value='.$key.'>'.$key.'</option>';
		}else{
			$v[] = '<option value='.$val.'>'.$val.'</option>';
		}
	}
	$v = join('<br/>', $v);
	$d = array(
			'html' => "<option value=\"\">---请选择二级分类---</option>".$v,
			'id' => 'cate_api_jinshan_second',
	);
	json($d, 'updater');
	//json($second, 'alert');
}
//金山分类二级导航联动效果
elseif ( 'jinshan2' == $action ) {
	$first = $_GET['first'];
	$second = $_GET['second'];
	//json($first, 'alert');
	require_once(dirname(dirname(dirname(__FILE__))).'/include/configure/navigation.php');
	foreach($jinshan[$first][$second] AS $key=>$val){
		$v[] = '<option value='.$val.'>'.$val.'</option>';
	}
	$v = join('', $v);
	//json($v, 'alert');
	$d = array(
			'html' => "<select name=\"cate_api_jinshan_third\" class=\"f-input\" style=\"width:120px\">".$v."</select>",
			'id' => 'cate_api_jinshan_second_select',
	);
	json($d, 'updater');
}
