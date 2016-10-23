<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property string $creation_date
 * @property integer $payment_to
 * @property string $amount
 * @property string $currency
 * @property string $answer_date
 * @property string $answer_data
 * @property integer $success
 * @property string $debug
 * @property integer $user_confirmed
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creation_date', 'answer_date', 'user_confirmation_date'], 'safe'],
            [['success'], 'integer'],
            [['payment_to','payment_from', 'answer_data', 'debug', 'placeholder', 'rrn', 'int_ref'], 'string'],
             [['currency'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creation_date' => 'Дата создания',
            'payment_from' => 'Отправитель',
            'payment_to' => 'Получатель',
            'placeholder' => 'ФИО',
            'amount' => 'Сумма',
            'currency' => 'Валюта',
            'answer_date' => 'Дата ответа банка',
            'answer_data' => 'Ответ банка',
            'success' => 'Успех',
            'debug' => 'Доп.инфо',
            'user_confirmation_date' => 'Подтверждение юзером',
        ];
    }

    /**
     * @inheritdoc
     * @return TransactionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionsQuery(get_called_class());
    }

	public function BxHmac($algo, $data, $key, $raw_output = false) 
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
		//svar_dump($algo, $opad, $pack, $data); die('key');

		$output = $algo($opad.pack($pack, $algo($ipad.$data))); 

		return ($raw_output) ? pack($pack, $output) : $output; 
	} 

	public function prepareRequest( $saved, $amount=false, $order_id=false ) {
		$currency = "643";
		$var = unpack("H*r", strtoupper(substr(md5(uniqid(30)), 0, 8))); 
		$nonce = $var["r"];
		$key = SDM_SHOPKEY;   
		$time = gmdate("YmdHis", time());
		$gmt = '';
		$order_id = $this->id;

		if (SDM_TEST == 1) {
			$order_id += 77777;
		}
		$amount = str_replace(",", ".", $this->amount);
		$amount = number_format(floatval($amount), 2, ".", "");
		$trtype = 8;
		$desc = SDM_DESC;
		$month = $saved['month'];
		if (strlen($month) == 1) {
			$month = '0' . $month;
		}
		$year = substr($saved['year'], 2, 2);
		$name = $saved['placeholder'];
		$to = $saved['card_number2'];
		$backref = SDM_BACKREF . '&id=' . $order_id;
		
	
		$dataSign = 	(strlen($amount) > 0 ? strlen($amount).$amount : "-").
										(strlen($currency) > 0 ? strlen($currency).$currency : "-").
										(strlen($order_id) > 0 ? strlen($order_id).$order_id : "-").
										(strlen(SDM_MERCHANT) > 0 ? strlen(SDM_MERCHANT).SDM_MERCHANT : "-").
										(strlen(SDM_TERMINAL) > 0 ? strlen(SDM_TERMINAL).SDM_TERMINAL : "-").
										(strlen($trtype) > 0 ? strlen($trtype).$trtype : "-").
										(strlen($time) > 0 ? strlen($time).$time : "-").
										(strlen($nonce) > 0 ? strlen($nonce).$nonce : "-").
										(strlen($backref) > 0 ? strlen($backref).$backref : "-").
										(strlen($saved['card_number']) > 0 ? strlen($saved['card_number']).$saved['card_number'] : "-").
										(strlen($name) > 0 ? strlen($name).$name : "-").
										(strlen($month) > 0 ? strlen($month).$month : "-").
										(strlen($year) > 0 ? strlen($year).$year : "-").
										(strlen($saved['cvv']) > 0 ? strlen($saved['cvv']).$saved['cvv'] : "-").
										(strlen($to) > 0 ? strlen($to).$to : "-");
											
		$sign = hash_hmac('sha1', $dataSign,  hex2bin($key));		

		$vars = array();
		$vars['time'] = $time;
		$vars['gmt'] = '+3';
		$vars['nonce'] = $nonce;
		$vars['backref'] = $backref;
		$vars['datasign'] = $dataSign;
		$vars['sign'] = $sign;
		$vars['key'] = SDM_SHOPKEY;
		$vars['desc'] = $desc;
		$vars['month']  = $month;
		$vars['name'] = $name;
		$vars['cvc2_rc'] = 1;
		$vars['year'] = $year;
		$vars['order'] = $order_id;
		$vars['amount'] = $amount;

		return $vars;
	}
}
