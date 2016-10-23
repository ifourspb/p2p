<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-index">

<p>
        <a class="btn btn-success" href="/transactions/">Операции</a>    
        <a class="btn btn-success" href="/syslog/">Системный лог</a>    
        <a class="btn btn-success" href="/oplog/">Операционный лог</a>    
	</p>
	<hr>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['width' => '40']],

            'id',
            'creation_date',
            'payment_from',
            'payment_to',
            'amount',
            'rrn',
            'success',
            // 'answer_date',
            // 'answer_data:ntext',
            // 'confirmed',
            // 'debug:ntext',

            [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия', 
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {link}',
		   ],
        ],
    ]); ?>
</div>
