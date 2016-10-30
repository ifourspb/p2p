<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OplogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Операционный лог';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oplog-index">

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
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'creation_date',
            'transaction_id',
            'ip',
            'agent',
            // 'delta_time:datetime',
             'src',
             [
				'label' => 'Описание',
				'format' => 'raw',
				'value' => function($data){
					return substr($data->descr, 0, 100) . '...';
				}
			],
            // 'agent_time',
            // 'agent_language',

            [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия', 
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {link}',
		   ],
        ],
    ]); ?>
</div>
