<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(__FILE__) . '/current.php');

need_manager();
need_auth('team');
//if(is_post()){print_r($_POST);exit;}

$id = abs(intval($_GET['id']));
$team = $eteam = Table::Fetch('team', $id);


//百度一级分类已选状态
$cate_api_arr = unserialize($team['cate_api']);
$cate_baidu_arr = explode('>', $cate_api_arr['baidu']);
$cate_baidu_var = $cate_baidu_arr[0];//已选择的百度API一级分类值

//360一级分类已选状态
$cate_360_arr = explode('>', $cate_api_arr['tuan360']);
$cate_360_var = $cate_360_arr[0];//已选择的360API一级分类值

//团800一级分类已选状态
$cate_tuan800_arr = explode('>', $cate_api_arr['tuan800']);

//搜狗一级分类已选状态
$cate_sogou_arr = explode('>', $cate_api_arr['sogou']);
$cate_sogou_var = $cate_sogou_arr[0];//已选择的搜故API一级分类值

//金山一级分类已选状态
$cate_jinshan_arr = explode('>', $cate_api_arr['jinshan']);
$cate_jinshan_var = $cate_jinshan_arr[0];//已选择的金山API一级分类值

//print_r($cate_tuan800_arr[0]);
//判断管理员所在城市的权限
if($id){
	
	if(0 != $login_user['city_id'] && $login_user['city_id']!=$team['city_id']){
		redirect( WEB_ROOT . "/manage/team/index.php");
	}
}

if ( is_get() && empty($team) ) {
	redirect( WEB_ROOT . '/manage/team/edit.php' );
}
else if ( is_post() ) {
	$_POST['cate_api_360_second'] = implode(' ', $_POST['cate_api_360_second']);
	$_POST['cate_api_tuan800'] = implode(',', $_POST['cate_api_tuan800']);
    $cabt = $_POST['cate_api_baidu_third'];
    if($_POST['cate_api_baidu_third'] == '无'){$cabt = '';}
    $jinshan_cabt = $_POST['cate_api_jinshan_third'];
    if($_POST['cate_api_jinshan_third'] == '无'){$jinshan_cabt = '';}
	$cate_api = serialize(array(
		'baidu' =>$_POST['cate_api_baidu_first'] . '>' . $_POST['cate_api_baidu_second'] . '>' . $cabt,	
		'tuan360' =>$_POST['cate_api_360_first'] . '>' . $_POST['cate_api_360_second'],	
		'tuan800' =>$_POST['cate_api_tuan800'],	
		'sogou' =>$_POST['cate_api_sogou_first'] . '>' . $_POST['cate_api_sogou_second'] . '>' . $_POST['cate_api_sogou_third'],	
		'jinshan' =>$_POST['cate_api_jinshan_first'] . '>' . $_POST['cate_api_jinshan_second'] . '>' . $jinshan_cabt,
	));
	if ( $team['id'] && $team['id'] == $id ) {
		Table::UpdateCache('team', $team['id'],
			array( 'cate_api' => $cate_api ));
		Session::Set('notice', '编辑项目杂项信息成功');
		redirect( WEB_ROOT . "/manage/team/editapi.php?id={$id}");
	} 
	else {
		Session::Set('error', '编辑项目杂项信息失败');
		redirect(null);
	}
}

$selector = 'edit';
include template('manage_team_editapi');
