<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Oplog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Oplogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oplog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'creation_date',
            'transaction_id',
            'ip',
            'agent',
            'delta_time:datetime',
            'src',
            'descr:ntext',
            'agent_time',
            'agent_language',
        ],
    ]) ?>

</div>
