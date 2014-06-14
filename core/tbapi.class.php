<?php
function_exists('date_default_timezone_set') && date_default_timezone_set('Asia/Shanghai');
class TBAPI {
    public $_method_name= '';
    public $_json_assoc	= false;
    public $_api_params = array();
    public $_app_key	= '21181857';
    public $_app_Secret	= '4ec6477b9129b78db88d6107b4cbd39f';
    public $_app_set	= false;
    public $_app_keys	= array(
			"21181857"=>array("21181857","4ec6477b9129b78db88d6107b4cbd39f"),
			//"12639521"=>array("12639521","070f4c6fac7de60d9d291afefdeca7d6"),
			"12696119"=>array("12696119","0ce77275ac1a0c6eed4475d0638a850a"),
			"12696073"=>array("12696073","ea5f0b4272eb4e27e76c985eacffc932")
	);
    
    public $_err_code	= 0;
    function appkey(){
    	if($this->_err_code==0) return;
    	
    	if($this->_app_set) return;

		$rand_key			= array_rand($this->_app_keys, 1);
		$this->_app_key 	= $this->_app_keys[$rand_key][0];
		$this->_app_Secret	= $this->_app_keys[$rand_key][1];
    }
    function setapp($_key,$_secret){
		$this->_app_key 	= $_key;
		$this->_app_Secret	= $_secret;
		$this->_app_set		= true;
    }
	function sign($params) {
	    $items = array();
	    foreach($params as $key => $value) $items[$key] = $value;
	    ksort($items);
	    $s = $this->_app_Secret;
	    foreach($items as $key => $value) {
	        $s .= "$key$value";
	    }
	    $s .= $this->_app_Secret;
	    return strtoupper(md5($s));
	}
    function set_method($method_name) {
        $this->method_name = $method_name;
    }
    function set_param($param_name, $param_vaule) {
        $this->api_params[$param_name] = $param_vaule;
    }
    function clean_param($param_name=array()){
    	if(empty($param_name)){
    		$this->api_params	= "";
    	}else{
	    	foreach($param_name as $p => $v) {
	    		unset($this->api_params[$v]);
	    	}
    	}
    }

    function getres($session=""){
        $sys_params = array(
                'timestamp'  => date("Y-m-d H:i:s"),
                'app_key' => $this->_app_key,
                'sign_method'  => 'md5',
                'format'  => 'json',
                'v'       => '2.0',
                'partner_id'  => 'top-apitools',
                'method'  => $this->method_name
		);
        if($session != '') {
            $sys_params['session'] = $session;
        }
        $sys_params['sign'] = $this->sign(array_merge($sys_params, $this->api_params));
        $param_string = '';
        foreach($sys_params as $p => $v) {
            $param_string .= "$p=" . urlencode($v) . "&";
        }
        $url ='http://gw.api.taobao.com/router/rest?' . substr($param_string, 0, -1);
        //echo $url."\n";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->api_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $postResult = curl_exec($ch);
        if (curl_errno($ch)){
            throw new Exception(curl_error($ch), 0);
        }else{
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($postResult, $httpStatusCode);
            }
        }
        curl_close($ch);
        $obj = json_decode($postResult,$this->_json_assoc);
        foreach($obj as $k => $v) {
            $obj = $v;
        }
        
        if($obj->error_response){
        	$this->_err_code	= $obj->error_response->code;
        	unset($this->_app_keys[$this->_app_key]);
        	$this->appkey();
        	return $this->getres($session);
        }
        return $obj;
    }
    function execute($session = '') {
    	if(strstr($this->method_name, '.widget.')){
    		$res	= $this->widget($session);
    	}else{
			$res	= $this->getres($session);
    	}
		$this->clean_param();
        return $res;
    }
    function widget($session){
		$timestamp	= time()."000";
		$message 	= $this->_app_Secret.'app_key'.$this->_app_key.'timestamp'.$timestamp.$this->_app_Secret;
		$mysign		= strtoupper(hash_hmac("md5",$message,$this->_app_Secret));
        $sys_params = array(
                'callback:'  => 'TOP.io.jsonpCbs.t229e16ca',
                'timestamp'  => $timestamp,
                'app_key' => $this->_app_key,
                'sign'  => $mysign,
                '_t_sys'  => 'args=4',
                'partner_id'  => 'top-sdk-js-20120801',
                'method'  => $this->method_name
		);
//		print_r($sys_params);
		$sys_params	= array_merge($sys_params, $this->api_params);
        $param_string = '';
        foreach($sys_params as $p => $v) {
            $param_string .= "$p=" . urlencode($v) . "&";
        }
        $url='http://gw.api.taobao.com/widget/rest';
        $ch = curl_init();
        //print_r($_SERVER);
		$HTTPHEADER = array("Origin:http://".$_SERVER['HTTP_HOST']);
//		$HTTPHEADER = array(
//		"Origin:http://".$_SERVER['HTTP_HOST'],
//		"Access-Control-Allow-Credentials:true",
//		"Access-Control-Allow-Headers:Content-Type",
//		"Access-Control-Allow-Methods:GET,POST,OPTIONS",
//		"Access-Control-Max-Age:1800",
//		"Access-Control-Allow-Origin:http://".$_SERVER['HTTP_HOST']
//		);
//		print_r($HTTPHEADER);
		
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $HTTPHEADER);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_REFERER, "http://".$_SERVER['HTTP_HOST']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sys_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        $postResult = curl_exec($ch);
//        echo "<pre>";
//        print_r($postResult);
        
        if (curl_errno($ch)){
            throw new Exception(curl_error($ch), 0);
        }else{
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($postResult, $httpStatusCode);
            }
        }
        curl_close($ch);
        $obj = json_decode($postResult,$this->_json_assoc);
        
        if($obj->error_response){
        	$this->_err_code	= $obj->error_response->code;
        	unset($this->_app_keys[$this->_app_key]);
        	$this->appkey();
        	return $this->widget($session);
        }
 //       print_r($obj);
        return $obj;
    }
}


//$req = new TopRequest('taobao.poster.channels.get');
//$top_session = "24523150b447abcb617cc1d7b58ce71ad7230";  
//$req->set_param('iid', $iid);  
//$req->set_param('image', '@' . $new_image_path); //�ϴ��ļ������ļ�·��ǰ����AT����  
//$req->set_param('is_major', 'true');  
//$result = $req->execute($top_session); // ���ڲ���Ҫsession��api������Բ���session����  
//print_r($result); 