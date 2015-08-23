<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');
$zhongyu_config = include str_ireplace('\\', '/', dirname(__FILE__)) . '/config.php';
include str_ireplace('\\', '/', dirname(__FILE__)) . '/ZhongyuModel.class.php';
include str_ireplace('\\', '/', dirname(__FILE__)) . '/AES.class.php';
$aes = new AES($zhongyu_config['secret_key']); //初始化aes加密
$zhongyuModel = new ZhongyuModel();

if(isset($_POST['is_encrypt']) && $_POST['is_encrypt'] == 1){ //xml数据位加密后
	$xml_array = xml_to_array($aes->decrypt(base64_decode(trim($_POST['xml']))));
}else{
	$xml_array = xml_to_array(trim($_POST['xml']));
}

//print_r($xml_array);exit;


$request_type = $xml_array['request_type'][0];


/* 同步项目 */
if('sync_team' == $request_type){
	$data = $xml_array['data'];
	die($zhongyuModel -> sync_team($data));
}


/* 修改团购结束时间接口 */
elseif('edit_product_end_time' == $request_type){
	$product_num = $xml_array['product_num'][0]; //中娱平台产品ID
    $end_time =  strtotime($xml_array['end_time'][0]); //接收到的项目结束时间（转化为unix时间戳）
	die($zhongyuModel -> edit_product_end_time($product_num, $end_time));
}




























function xml_to_array($xml)
{
  $array = (array)(simplexml_load_string($xml, null, LIBXML_NOCDATA));
  foreach ($array as $key=>$item){
    $array[$key]  =  struct_to_array((array)$item);
  }
  return $array;
}
function struct_to_array($item) {
  if(!is_string($item)) {
    $item = (array)$item;
    foreach ($item as $key=>$val){
      $item[$key]  =  struct_to_array($val);
    }
  }
  return $item;
}