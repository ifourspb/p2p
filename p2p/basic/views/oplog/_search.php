<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OplogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="oplog-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'creation_date') ?>

    <?= $form->field($model, 'transaction_id') ?>

    <?= $form->field($model, 'ip') ?>

    <?= $form->field($model, 'agent') ?>

    <?php // echo $form->field($model, 'delta_time') ?>

    <?php // echo $form->field($model, 'src') ?>

    <?php // echo $form->field($model, 'descr') ?>

    <?php // echo $form->field($model, 'agent_time') ?>

    <?php // echo $form->field($model, 'agent_language') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
