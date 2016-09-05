<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'creation_date')->textInput() ?>

    <?= $form->field($model, 'payment_to')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

   

    <?= $form->field($model, 'answer_date')->textInput() ?>

    <?= $form->field($model, 'answer_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'confirmed')->textInput() ?>

    <?= $form->field($model, 'debug')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
