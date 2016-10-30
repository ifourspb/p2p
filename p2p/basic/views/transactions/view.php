<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */

$this->title = 'Операция ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-view">

<p>
        <a class="btn btn-success" href="/transactions/">Операции</a>    
        <a class="btn btn-success" href="/syslog/">Системный лог</a>    
        <a class="btn btn-success" href="/oplog/">Операционный лог</a>    
	</p>
	<hr>

    <h1><?= Html::encode($this->title) ?></h1>

	<?php
	function arr_print( $a ) {
		//var_dump(unserialize($a)); die();
		if ($a) {
			$s = '<pre>';
			ob_start();
				var_dump(unserialize($a));
			$s .= ob_get_contents();
			ob_end_clean();
			$s .= '</pre>';
			return $s;
		}
	}
	?>

   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_confirmation_date',
            'creation_date',
            'placeholder',
            'payment_from',
            'payment_to',
            'amount',
            'currency',
            'answer_date',
             [
				'label' => 'Ответ банка',
				'format' => 'raw',
				'value' => arr_print($model->answer_data)
			],
            'rrn',
            'authcode',
            'int_ref',
            'success'
        ],
    ]) ?>

</div>
