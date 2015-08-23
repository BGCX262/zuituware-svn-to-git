<?php
/**
 * 中娱互动电子凭证系统
 */
class Zhongyu {
    private $organization = ''; //机构号
    private $secret_key = ''; //加密密钥
    private $send_url = '';
    
    public function __construct() {
        $config = include str_replace("\\", '/', dirname(__FILE__)) . '/config.php';
        $this->organization = $config['organization'];
        $this->secret_key = $config['secret_key'];
        $this->send_url = $config['send_url'];
    }
    
    /**
     * 发送二维码
     *
     */
    public function send($info) {
        require_once str_replace("\\", '/', dirname(__FILE__)) . '/AES.class.php';
        
        $xml = "<?xml version='1.0' encoding='utf-8'?>
            <business_trans>
            	<request_type>add_order</request_type>
            	<req_seq>" . $info['req_seq'] . "</req_seq>
            	<order>
            		<product_num>" . $info['serv_code'] . "</product_num>
            		<valid_times>1</valid_times>
            		<mobile>" . $info['phone_rece'] . "</mobile>
                    <notes>" . $info['notes'] . "</notes>
            	</order>
            </business_trans>";

        //xml的aes加密
		$aes = new AES($this->secret_key);
		$xml_aes = $aes->encrypt($xml);
		$xml_aes_str = base64_encode($xml_aes);
		//组织参数
        
        $organization = $this->organization;
		$paramters = array('organization' => $organization, 'xml' => $xml_aes_str);
		$result = $this->simulation_post($this->send_url, $paramters);
		
		$xml_result = $aes->decrypt(base64_decode($result));

		return $xml_result;
    }
    
    /**
     * 重发二维码
     *
     */
    public function repeat($info) {
	
        require_once str_replace("\\", '/', dirname(__FILE__)) . '/AES.class.php';
        
        $xml = "<?xml version='1.0' encoding='utf-8'?>
            <business_trans>
            	<request_type>repeat_order</request_type>
            	<req_seq>" . $info['req_seq'] . "</req_seq>
            </business_trans>";

        //xml的aes加密
		$aes = new AES($this->secret_key);
		$xml_aes = $aes->encrypt($xml);
		$xml_aes_str = base64_encode($xml_aes);
		//组织参数
		$paramters = array('organization' => $this->organization, 'xml' => $xml_aes_str);
		$result = $this->simulation_post($this->send_url, $paramters);
		
		$xml_result = $aes->decrypt(base64_decode($result));

		return $xml_result;
    }
    
    /**
     * 撤销二维码
     *
     */
    public function cancel($info) {
        require_once str_replace("\\", '/', dirname(__FILE__)) . '/AES.class.php';
        
        $xml = "<?xml version='1.0' encoding='utf-8'?>
            <business_trans>
            	<request_type>cancel_order</request_type>
            	<req_seq>" . $info['req_seq'] . "</req_seq>
            	<order>
            		<cancel_num>1</cancel_num>
            	</order>
            </business_trans>";

        //xml的aes加密
		$aes = new AES($this->secret_key);
		$xml_aes = $aes->encrypt($xml);
		$xml_aes_str = base64_encode($xml_aes);
		//组织参数
		$paramters = array('organization' => $this->organization, 'xml' => $xml_aes_str);
		$result = $this->simulation_post($this->send_url, $paramters);
		
		$xml_result = $aes->decrypt(base64_decode($result));

		return $xml_result;
    }
    
	/**
     * 模拟POST
     * 
     * 使用方法：
     * $post = "app=request&version=beta"; 或 $post = array('app'=>'request', 'version' => 'beta');
     * simulation_post('http://facebook.cn/restServer.php',$post_string);
     */
    public function simulation_post($remote_server, $post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, "redutuan.com POST");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
?>