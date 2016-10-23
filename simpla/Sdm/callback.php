<?php
 
// Работаем в корневой директории
chdir ('../../');
require_once('api/Simpla.php');

file_put_contents('/tmp/simpla', serialize($_POST) . "\n\n");

$simpla = new Simpla();

$p_rrn = $_POST["RRN"];
$p_int_ref = $_POST["IntRef"];
$p_trtype = $_POST["TRType"];
$p_order = $_POST["Order"];
$p_amount = $_POST["Amount"];
$p_currency = $_POST["Currency"];
$p_auth = $_POST['AuthCode'];
$p_result = $_POST['Result'];
$p_rc = $_POST['RC'];
$p_sign = $_POST["P_Sign"];

// Выберем заказ из базы
$order = $simpla->orders->get_order(intval($p_order));
if(empty($order)) {
	$p_order2 = intval($p_order) - 77777777;
	$order = $simpla->orders->get_order(intval($p_order2));
}
if(empty($order)) {
	print_error('Оплачиваемый заказ не найден');
}

// Выбираем из базы соответствующий метод оплаты
$method = $simpla->payment->get_payment_method(intval($order->payment_method_id));
if(empty($method)) {
	print_error("Неизвестный метод оплаты");
}
 
$settings = unserialize($method->settings);
$mac = $settings['sdm_key'];
$p_terminal = $settings['sdm_terminal'];

$p_order2 = (int)$_POST["Order"];
if($settings['sdm_testmode']) {
	$p_order2  -= 77777777;
}
       
// Нельзя оплатить уже оплаченный заказ  
if($order->paid)
	print_error('Этот заказ уже оплачен');
       
// Проверка контрольной подписи

$key = pack("H*", $mac);   
$data = (strlen($p_amount) > 0 ? strlen($p_amount).$p_amount : "-").
		(strlen($p_currency) > 0 ? strlen($p_currency).$p_currency : "-").
		(strlen($p_order) > 0 ? strlen($p_order).$p_order : "-").
		(strlen($p_trtype) > 0 ? strlen($p_trtype).$p_trtype : "-").
		(strlen($p_result) > 0 ? strlen($p_result).$p_result : "-").
		(strlen($p_rc) > 0 ? strlen($p_rc).$p_rc : "-").
		(strlen($p_auth) > 0 ? strlen($p_auth).$p_auth : "-").		
		(strlen($p_rrn) > 0 ? strlen($p_rrn).$p_rrn : "-").
		(strlen($p_int_ref) > 0 ? strlen($p_int_ref).$p_int_ref : "-");

$sign = strtoupper(bx_hmac("sha1", $data, $key));
	   
// Запишем
if($sign == $p_sign) {
	// Установим статус оплачен
	$simpla->orders->update_order(intval($order->id), array('paid'=>1));

	// Спишем товары  
	$simpla->orders->close(intval($order->id));
	$simpla->notify->email_order_user(intval($order->id));
	$simpla->notify->email_order_admin(intval($order->id));
	
	echo "ok";
	
} elseif($_POST['action'] == 'checkOrder') {
	echo "fail sign";
}

function print_error($text) {
	echo $test;
	exit();
}

function bx_hmac($algo, $data, $key, $raw_output = false)  { 
		$algo = strtolower($algo); 
		$pack = "H".strlen($algo("test")); 
		$size = 64; 
		$opad = str_repeat(chr(0x5C), $size); 
		$ipad = str_repeat(chr(0x36), $size); 

		if (strlen($key) > $size) { 
			$key = str_pad(pack($pack, $algo($key)), $size, chr(0x00)); 
		} else { 
			$key = str_pad($key, $size, chr(0x00)); 
		} 

		$lenKey = strlen($key) - 1;
		for ($i = 0; $i < $lenKey; $i++) { 
			$opad[$i] = $opad[$i] ^ $key[$i]; 
			$ipad[$i] = $ipad[$i] ^ $key[$i]; 
		} 

		$output = $algo($opad.pack($pack, $algo($ipad.$data))); 
		return ($raw_output) ? pack($pack, $output) : $output; 
	} 