<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Oplog;
use app\models\Transactions;
use yii\helpers\Html; 

define('BASE_URL', 'http://p2p.bhteam.ru/');
define('SDM_TEST', 1);
define('SDM_SHOPKEY', '466B3FE46B9D6030B322EEFAB03BE966');
define('SDM_TERMINAL', '10007777');
define('SDM_NAME', 'SDM Bank. Secure Payments.');
define('SDM_MERCHANT', '123456789012345');
define('SDM_EMAIL', 'ifourspb@gmail.com');
define('SDM_BACKREF', BASE_URL . 'backref.php');
define('SDM_DESC', 'Money transfer');
define('SDM_FORM_URL', 'https://3ds.sdm.ru/cgi-bin/cgi_link');
define('SDM_FORMTEST_URL', 'https://3dst.sdm.ru/cgi-bin/cgi_link');

class SiteController extends Controller
{
	private $oplog;

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
		$data['card_number'] = Html::encode('5571060051490102');
		$data['card_number2'] = Html::encode('5571060051490102');
		$data['messages'] = false;
		$data['month'] = false;
		$data['year'] = false;
		$data['cvv'] = false;
		$data['placeholder'] = false;
		$data['amount'] = false;

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
		//index page
		return $this->render('index', $data);
    }

	public function stepTwo( $data )
	{
		$transaction = new Transactions();
		$transaction->creation_date = date("Y-m-d H:i:s");
		$transaction->payment_from = $data['card_number'];
		//var_dump($transaction->payment_from); die();
		$transaction->payment_to = $data['card_number2'];
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
	

		$post = array();
		$post['card'] = $saved['card_number'];
		$post['exp'] = $vars['month'];
		$post['exp_year'] = $vars['year'];
		$post['cvc2'] = $saved['cvv'];
		$post['payment_to'] = $saved['card_number2'];
		$post['amount'] = $vars['amount'];
		$post['currency'] = '643';
		$post['order'] = $vars['order'];
		$post['desc'] = $vars['desc'];
		$post['terminal'] = SDM_TERMINAL;
		$post['trtype'] = 8;
		$post['merch_name'] = SDM_NAME;
		$post['merch_url'] = BASE_URL;
		$post['merchant'] = SDM_MERCHANT; 
		$post['email'] = SDM_EMAIL;
		$post['timestamp'] = $vars['time'];
		$post['merch_gmt'] = $vars['gmt'];
		$post['nonce'] = $vars['nonce'];
		$post['backref'] = $vars['backref'];
		$post['p_sign'] = $vars['sign'];
		$post['key'] = $vars['key'];
		$post['mac'] = $vars['sign'];
		$post['mac_data'] = $vars['datasign'];
		
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

		$oplog = new Oplog();
		$oplog->creation_date = date("Y-m-d H:i:s");
		$oplog->ip = $_SERVER['REMOTE_ADDR'];
		$oplog->agent = $_SERVER['HTTP_USER_AGENT'];
		$oplog->delta_time = (time() - $time);
		$oplog->src = 'site/step2';
		$oplog->agent_language = $oplog->Get_Client_Prefered_Language( $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
		$oplog->agent_time = $agent_time;
		$oplog->transaction_id = $data['transaction_id'];
		$z = $oplog->save();		

		$this->oplog = &$oplog;
		return $messages;
	}

	public function checkStepOne() {
		$messages = $this->checkCardPost();
		
		$time = (int)Yii::$app->request->post('time');
		$agent_time = date("Y-m-d H:i:s", strtotime(Yii::$app->request->post('agent_time')));	

		$oplog = new Oplog();
		$oplog->creation_date = date("Y-m-d H:i:s");
		$oplog->ip = $_SERVER['REMOTE_ADDR'];
		$oplog->agent = $_SERVER['HTTP_USER_AGENT'];
		$oplog->delta_time = (time() - $time);
		$oplog->src = 'site/step1';
		$oplog->descr = join("\n", $messages);
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
    
}
