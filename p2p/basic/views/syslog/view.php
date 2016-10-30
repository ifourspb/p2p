<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Syslog */

$this->title = "Системный лог - " .$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Syslogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="syslog-view">


<p>
        <a class="btn btn-success" href="/transactions/">Операции</a>    
        <a class="btn btn-success" href="/syslog/">Системный лог</a>    
        <a class="btn btn-success" href="/oplog/">Операционный лог</a>    
	</p>
	<hr>

    <h1><?= Html::encode($this->title) ?></h1>

	<?php
	function arr_print($src, $a ) {
		//var_dump(unserialize($a)); die();
		if ($a) {
			$s = '<pre>';
			ob_start();
			if ($src == 'callback_bad_sign' || $src == 'NO ANSWER') {
				var_dump(unserialize($a));
			}else {
				echo(($a));
			}
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
            'date',
            'src',
             [
				'label' => 'Описание',
				'format' => 'raw',
				'value' => arr_print($model->src, $model->descr)
			]
        ],
    ]) ?>

</div>
