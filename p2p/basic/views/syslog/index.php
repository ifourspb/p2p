<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SyslogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Syslogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="syslog-index">

<p>
        <a class="btn btn-success" href="/transactions/">Операции</a>    
        <a class="btn btn-success" href="/syslog/">Системный лог</a>    
        <a class="btn btn-success" href="/oplog/">Операционный лог</a>    
	</p>
	<hr>

    <h1>Системный лог</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
           'src',
			 [
				'label' => 'Описание',
				'format' => 'raw',
				'value' => function($data){
					return substr($data->descr, 0, 100) . '...';
				}
			],

           [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Действия', 
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {link}',
		   ],
        ],
    ]); ?>
</div>
