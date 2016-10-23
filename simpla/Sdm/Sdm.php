<?php

require_once('api/Simpla.php');

class Sdm extends Simpla
{
	public function checkout_form($order_id, $button_text = null)
	{

		if(empty($button_text))
			$button_text = 'Перейти к оплате';
		
		$order = $this->orders->get_order((int)$order_id);
		$payment_method = $this->payment->get_payment_method($order->payment_method_id);
		$payment_currency = $this->money->get_currency(intval($payment_method->currency_id));
		$settings = $this->payment->get_payment_settings($payment_method->id);
		
		$out_summ  = number_format(floatval($this->money->convert($order->total_price, $payment_method->currency_id, false)), 2, ".", "");
		$currency = "643";
		$order_id = (int)$order_id;
		$backref = $this->config->root_url.'/order/'.$order->url;
		$m_name = $_SERVER['SERVER_NAME'];
		$m_url = $_SERVER['SERVER_NAME'];
		$merchant = $settings['sdm_shopid'];
		$terminal = $settings['sdm_terminal'];
		$desc = "Order " . $order_id . " " . $m_name;
		$email = htmlspecialchars($order->email,ENT_QUOTES);
		$phone = $order->phone;
		$trtype = 1;  
		
		if($settings['sdm_testmode']) {
			$formUrl = 'https://3dst.sdm.ru/cgi-bin/cgi_link/';
			$order_id += 77777777;
		}else {
			$formUrl = 'https://3ds.sdm.ru/cgi-bin/cgi_link/';
		}


		$var = unpack("H*r", strtoupper(substr(md5(uniqid(30)), 0, 8))); 
		$nonce = $var[r];
		$key = pack("H*", $settings['sdm_key']);   
		$time = gmdate("YmdHis", time());

		$data = 	(strlen($out_summ) > 0 ? strlen($out_summ).$out_summ : "-").
		(strlen($currency) > 0 ? strlen($currency).$currency : "-").
		(strlen($order_id) > 0 ? strlen($order_id).$order_id : "-").
		(strlen($desc) > 0 ? strlen($desc).$desc : "-").
		(strlen($m_name) > 0 ? strlen($m_name).$m_name : "-").
		(strlen($m_url) > 0 ? strlen($m_url).$m_url : "-").
		(strlen($merchant) > 0 ? strlen($merchant).$merchant : "-").
		(strlen($terminal) > 0 ? strlen($terminal).$terminal : "-").
		(strlen($email) > 0 ? strlen($email).$email : "-").
		(strlen($trtype) > 0 ? strlen($trtype).$trtype : "-").
		(strlen($time) > 0 ? strlen($time).$time : "-").
		(strlen($nonce) > 0 ? strlen($nonce).$nonce : "-").
		(strlen($backref) > 0 ? strlen($backref).$backref : "-");
		
		$sign = Sdm::bx_hmac("sha1", $data, $key);

		ob_start();
		?>
		<form method="POST" action="<?=$formUrl;?>">  
			<input type="hidden" name="TRTYPE" VALUE="<?=$trtype?>">
			<input type="hidden" name="AMOUNT" value="<?=$out_summ?>"> 
			<input type="hidden" name="CURRENCY" value="<?=$currency?>"> 
			<input type="hidden" name="ORDER" value="<?=$order_id?>">  
			<input type="hidden" name="DESC" value="<?=$desc?>"> 
			<input type="hidden" name="MERCH_NAME" value="<?=$m_name?>"> 
			<input type="hidden" name="MERCH_URL" value="<?=$m_url?>"> 
			<input type="hidden" name="MERCHANT" value="<?=$merchant?>"> 
			<input type="hidden" name="TERMINAL" value="<?=$terminal?>"> 
			<input type="hidden" name="EMAIL" value="<?=$email?>"> 
			<input type="hidden" name="LANG" value=""> 
			<input type="hidden" name="BACKREF" value="<?=$backref?>"> 
			<input type="hidden" name="NONCE" value="<?=$nonce?>">
			<input type="hidden" name="P_SIGN" value="<?=$sign?>">
			<input type="hidden" name="TIMESTAMP" value="<?=$time?>">
			<input type="submit" value="<?=$button_text?>">
		</form>
	<?php
		$button = ob_get_contents();
		ob_end_clean();
		return $button;
	}

	public static function bx_hmac($algo, $data, $key, $raw_output = false) 
	{ 
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


}
