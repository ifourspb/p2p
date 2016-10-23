<?php

/* @var $this yii\web\View */

$this->title = 'Loading...';
use yii\bootstrap\ActiveForm;

echo 'Loading...<form method="POST" action="' . $url . '" id="payForm">';

foreach ($post as $key => $value) {
	echo '<input type="hidden" name="' . $key . '" value="' . ($value) . '">';
}
echo '</form>';
echo '<script>$("#payForm").submit();</script>';

?>

