<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Oplog */

$this->title = 'Операционный лог ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Oplogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oplog-view">

<p>
        <a class="btn btn-success" href="/transactions/">Операции</a>    
        <a class="btn btn-success" href="/syslog/">Системный лог</a>    
        <a class="btn btn-success" href="/oplog/">Операционный лог</a>    
	</p>
	<hr>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'creation_date',
            'transaction_id',
            'ip',
            'agent',
            'delta_time',
            'src',
            'descr:ntext',
            'agent_time',
            'agent_language',
        ],
    ]) ?>

</div>
