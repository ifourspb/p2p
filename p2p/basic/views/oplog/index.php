<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OplogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oplogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oplog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Oplog', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
            // 'src',
            // 'descr:ntext',
            // 'agent_time',
            // 'agent_language',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
