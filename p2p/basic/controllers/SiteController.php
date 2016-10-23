<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Oplog;
use app\models\Syslog;
use app\models\CaptchaForm;
use app\models\Transactions;
use yii\helpers\Html; 
use yii\captcha\Captcha;

class SiteController extends Controller
{
	private $oplog;
	public $verifyCodeError = 0;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays step 1.
     *
     * @return string
     */
    public function actionIndex()
    {
		$messages = array();		
		
		$data = array();
		$data['card_number'] = Html::encode(SDM_CARD_FROM);
		$data['card_number2'] = Html::encode(SDM_CARD_TO);
		$data['messages'] = false;
		$data['month'] = false;
		$data['year'] = false;
		$data['cvv'] = false;
		$data['placeholder'] = false;
		$data['amount'] = false;

		if (Yii::$app->request->get('action') == 'backref') {
			return $this->backRef();
		}
		if (Yii::$app->request->get('action') == 'callback') {
			return $this->callback();
		}
		
		
		if (Yii::$app->request->post()){
			if (Yii::$app->request->post('step') == '1') {
				//step one
				
				$data['month'] = Html::encode(Yii::$app->request->post('month'));
				$data['year'] = Html::encode(Yii::$app->request->post('year'));
				$data['cvv'] = Html::encode(Yii::$app->request->post('cvv'));
				$data['placeholder'] = Html::encode(Yii::$app->request->post('placeholder'));
				$data['amount'] = Html::encode(Yii::$app->request->post('amount'));

				$messages = $this->checkStepOne();
				//check card data
				$data['messages'] = $messages;
				if (count($messages) == 0) {
					//render step two
					return $this->stepTwo( $data );
				}
			}

			if (Yii::$app->request->post('step') == '2') {
				//step two
				$messages = $this->checkStepTwo();
				//has user gone step1 ?
				$data['messages'] = $messages;
				if (count($messages) == 0) {
					//render step three
					return $this->stepThree( $data );
				}else {
					return $this->stepTwo( $data );
				}
			}
		}
		$data['verifyCodeError'] = $this->verifyCodeError;
		//index page
		return $this->render('index', $data);
    }
	
	public function backRef() {
		$orders = Yii::$app->session->get( 'orders');
		if (empty($orders) || !$orders || !is_array($orders)) {
			return $this->redirect('/');
		}
		$order_id = (int)Yii::$app->request->get('id');
		
		if (in_array($order_id, $orders)) {
			if (SDM_TEST == 1)  {
				$order_id -= 77777;
			}
			$data['transaction'] = Transactions::find() ->where(['id' => $order_id])->one();
		}else {
			return $this->redirect('/');
		}
	
		if (!$data['transaction']) {
			return $this->redirect('/');
		}
		
		$delta = 5 - strlen($data['transaction']->id);
		$data['transaction_id'] = $data['transaction']->id;
		if ($delta > 0) {
			for ($i = 0; $i<$delta; $i++) {
				$data['transaction_id'] = '0' . $data['transaction_id'];
			}
		}

		$data['payment_from'] = $data['transaction']->payment_from;
		$needle = substr($data['payment_from'], 4, 8);
		$data['payment_from'] = str_replace($needle, 'XXXXXXXX', $data['payment_from']);

		$data['payment_to'] = $data['transaction']->payment_to;
		$needle = substr($data['payment_to'], 4, 8);
		$data['payment_to'] = str_replace($needle, 'XXXXXXXX',$data['payment_to']);

		//backref page
		return $this->render('backref', $data);
	}

	public function stepTwo( $data )
	{
		$transaction = new Transactions();
		$transaction->creation_date = date("Y-m-d H:i:s");
		$transaction->payment_from = $data['card_number'];
		$transaction->payment_to = $data['card_number2'];
		$transaction->placeholder = $data['placeholder'];
		$transaction->amount = str_replace(',', '.', $data['amount']);
		$transaction->currency = '643';
		$transaction->save();

		$this->oplog->transaction_id = $transaction->id;
		$this->oplog->save();

		$data['transaction_id'] = $transaction->id;
		Yii::$app->session->set( 'step1', $data );

		$delta = 5 - strlen($data['transaction_id']);
		if ($delta > 0) {
			for ($i = 0; $i<$delta; $i++) {
				$data['transaction_id'] = '0' . $data['transaction_id'];
			}
		}

		return $this->render('two', $data);
	}

	public function stepThree( $data )
	{
		$saved = Yii::$app->session->get( 'step1');
		
		$transaction = Transactions::find() ->where(['id' => (int)$saved['transaction_id']])->one();
		$vars = $transaction->prepareRequest( $saved );
		$transaction->user_confirmation_date = date("Y-m-d H:i:s");
		$transaction->save();	

		$orders = Yii::$app->session->get( 'orders');
		if (empty($orders)) {
			$orders = array();
		}
		$orders[] = $vars['order'];
		Yii::$app->session->set( 'orders', $orders );


		$post = array();
		$post['CARD'] = $saved['card_number'];
		$post['NAME'] = $vars['name'];
		$post['EXP'] = $vars['month'];
		$post['EXP_YEAR'] = $vars['year'];
		$post['CVC2'] = $saved['cvv'];
		$post['CVC2_RC'] = $vars['cvc2_rc'];
		$post['PAYMENT_TO'] = $saved['card_number2'];
		$post['AMOUNT'] = $vars['amount'];
		$post['CURRENCY'] = '643';
		$post['ORDER'] = $vars['order'];
		$post['DESC'] = $vars['desc'];
		$post['TERMINAL'] = SDM_TERMINAL;
		$post['TRTYPE'] = 8;
		$post['MERCH_NAME'] = SDM_NAME;
		$post['MERCH_URL'] = SDM_MERCH_URL;
		$post['MERCHANT'] = SDM_MERCHANT; 
		$post['EMAIL'] = SDM_EMAIL;
		$post['TIMESTAMP'] = $vars['time'];
		$post['MERCH_GMT'] = $vars['gmt'];
		$post['NONCE'] = $vars['nonce'];
		$post['BACKREF'] = $vars['backref'];
		$post['P_SIGN'] = $vars['sign'];
		$post['KEY'] = $vars['key'];
		$post['MAC_DATA'] = $vars['datasign'];
		$post['MAC'] = $vars['sign'];
		
			
		$data['post'] = $post;
		
		if (SDM_TEST == 1)  {
			$data['url'] = SDM_FORMTEST_URL;
		}else {
			$data['url'] = SDM_FORM_URL;
		}
		
		return $this->render('three', $data);
		
	}

	public function checkStepTwo() {
		
		$messages = array();
		$time = (int)Yii::$app->request->post('time');
		$agent_time = date("Y-m-d H:i:s", strtotime(Yii::$app->request->post('agent_time')));	

		$data = Yii::$app->session->get( 'step1');
		if (!$data) {
			$messages[] = 'Ошибка передачи данных #001';
		}
		if (!isset($data['transaction_id'])) {
			$messages[] = 'Ошибка передачи данных #002';
		}
		
		if ($messages) {
			$descr = join("\n", $messages);
		}else {
			$descr = 'OK';
		}

		$oplog = new Oplog();
		$oplog->creation_date = date("Y-m-d H:i:s");
		$oplog->ip = $_SERVER['REMOTE_ADDR'];
		$oplog->agent = $_SERVER['HTTP_USER_AGENT'];
		$oplog->delta_time = (time() - $time);
		$oplog->src = 'site/step2';
		$oplog->agent_language = $oplog->Get_Client_Prefered_Language( $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
		$oplog->agent_time = $agent_time;
		$oplog->transaction_id = $data['transaction_id'];
		$oplog->descr = $descr;
		$z = $oplog->save();		

		$this->oplog = &$oplog;
		return $messages;
	}

	public function checkStepOne() {
		$messages = $this->checkCardPost();
		
		$time = (int)Yii::$app->request->post('time');
		$agent_time = date("Y-m-d H:i:s", strtotime(Yii::$app->request->post('agent_time')));	

		if ($messages) {
			$descr = join("\n", $messages);
		}else {
			$descr = 'OK';
		}

		$oplog = new Oplog();
		$oplog->creation_date = date("Y-m-d H:i:s");
		$oplog->ip = $_SERVER['REMOTE_ADDR'];
		$oplog->agent = $_SERVER['HTTP_USER_AGENT'];
		$oplog->delta_time = (time() - $time);
		$oplog->src = 'site/step1';
		$oplog->descr = $descr;
		$oplog->agent_language = $oplog->Get_Client_Prefered_Language( $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
		$oplog->agent_time = $agent_time;
		$z = $oplog->save();		

		$this->oplog = &$oplog;

		return $messages;
	}

	public function checkCardPost() {
		$card_number = str_replace(' ', '', Yii::$app->request->post('card_number'));
		$month = Yii::$app->request->post('month');
		$year = Yii::$app->request->post('year');
		$cvv = Yii::$app->request->post('cvv');
		$captcha_code = Yii::$app->request->post('captcha_code');
		$placeholder = Yii::$app->request->post('placeholder');
		$amount = Yii::$app->request->post('amount');
		$card_number2 = str_replace(' ', '', Yii::$app->request->post('card_number2'));
		
		$messages = array();
		if (!$this->checkCreditcard_number($card_number)) {
			$messages[] = 'Некорректно заполнено поле "Номер карты отправителя"';
		}
		if (!$this->checkCreditcard_number($card_number2)) {
			$messages[] = 'Некорректно заполнено поле "Номер карты получателя"';
		}
		if (!$this->checkCreditCardExpirationDate($month, $year)) {
			$messages[] = 'Некорректно заполнено поле "Срок действия карты"';
		}
		if (!$this->checkCVV($card_number, $cvv)) {
			$messages[] = 'Некорректно заполнено поле "CVV2/CVC2"';
		}
		if (floatval($amount) <= 0) {
			$messages[] = 'Некорректно заполнено поле "Сумма перевода"';
		}
		$z = include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
		
		$securimage = new \Securimage();

		if ($securimage->check($_POST['captcha_code']) == false) {
			$this->verifyCodeError = true;
			$messages[] = 'Неверно указан проверочный код';
		}

		return $messages;		
	}

	public function checkCreditcard_number($credit_card_number) {
		$firstnumber = substr($credit_card_number, 0, 1);
		switch ($firstnumber) {
		case 3:
			if (!preg_match('/^3\d{3}[ \-]?\d{6}[ \-]?\d{5}$/', $credit_card_number)) {return false;}
			break;
		case 4:
			if (!preg_match('/^4\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number)) {return false;}
			break;
		case 5:
			if (!preg_match('/^5\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number)) {return false;}
			break;
		case 6:
			if (!preg_match('/^6011[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number)) {return false;}
			break;
		default:
			return false; }
		$credit_card_number = str_replace('-', '', $credit_card_number);
		$map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
		$sum = 0;
		$last = strlen($credit_card_number) - 1;
		for ($i = 0; $i <= $last; $i++) {$sum += $map[$credit_card_number[$last - $i] + ($i & 1) * 10];}
		if ($sum % 10 != 0) {return false;}
		return true;
	}
		 
	public function checkCreditCardExpirationDate($month, $year) {
		if (!preg_match('/^\d{1,2}$/', $month)) {return false;}
		else if (!preg_match('/^\d{4}$/', $year)) {return false;}
		else if ($year < date("Y")) {return false;}
		else if ($month < date("m") && $year == date("Y")) {return false;}
		return true;
	}
		 
	public function checkCVV($cardNumber, $cvv) {
		$firstnumber = (int) substr($cardNumber, 0, 1);
		if ($firstnumber === 3) {if (!preg_match("/^\d{4}$/", $cvv)){return false;}}
		else if (!preg_match("/^\d{3}$/", $cvv)) {return false;}
		return true;
	}

	public function callback() {
		
		$p_amount = Yii::$app->request->post('Amount');
		$p_currency = Yii::$app->request->post('Currency');
		$p_order = Yii::$app->request->post('Order');
		$order_id = $p_order;
		if (SDM_TEST == 1)  {
			$order_id -= 77777;
		}
		$p_trtype = Yii::$app->request->post('TRType');
		$p_result = Yii::$app->request->post('Result');
		$p_rc = Yii::$app->request->post('RC');
		$p_auth = Yii::$app->request->post('AuthCode');
		$p_rrn = Yii::$app->request->post('RRN');
		$p_int_ref = Yii::$app->request->post('IntRef');
		$p_sign = Yii::$app->request->post('P_Sign');


		$dataSign = (strlen($p_amount) > 0 ? strlen($p_amount).$p_amount : "-").
					(strlen($p_currency) > 0 ? strlen($p_currency).$p_currency : "-").
					(strlen($p_order) > 0 ? strlen($p_order).$p_order : "-").
					(strlen($p_trtype) > 0 ? strlen($p_trtype).$p_trtype : "-").
					(strlen($p_result) > 0 ? strlen($p_result).$p_result : "-").
					(strlen($p_rc) > 0 ? strlen($p_rc).$p_rc : "-").
					(strlen($p_auth) > 0 ? strlen($p_auth).$p_auth : "-").		
					(strlen($p_rrn) > 0 ? strlen($p_rrn).$p_rrn : "-").
					(strlen($p_int_ref) > 0 ? strlen($p_int_ref).$p_int_ref : "-");

		$key = SDM_SHOPKEY;   
		
		$sign = hash_hmac('sha1', $dataSign,  hex2bin($key));
		
		if($sign != $p_sign && false) {

			$syslog = new Syslog();
			$syslog->date = date("Y-m-d H:i:s");
			$syslog->src = 'callback_bad_sign';
			$syslog->descr = serialize( $_POST );
			$z = $syslog->save();
			die();
		}
		$transaction = Transactions::find() ->where(['id' => $order_id])->one();
		$transaction->success = 1;
		$transaction->answer_date = date("Y-m-d H:i:s");
		$transaction->answer_data = serialize( $_POST );
		$transaction->rrn = $p_rrn;
		$transaction->int_ref = $p_int_ref;
		$transaction->save();
		
		die();
		
		
	}

    
}
