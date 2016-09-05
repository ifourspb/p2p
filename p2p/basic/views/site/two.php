<?php

/* @var $this yii\web\View */

$this->title = 'Подтверждение перевода';
use yii\bootstrap\ActiveForm;

?>

<div class="header">
    <div class="down_header" style="border-bottom: 6px solid #033C7B;border-top: 15px solid #033C7B;">
        <div class="logodiv">
            <img src="/images/logo.png" alt="Логотип" class="logo" />
        </div>
    </div>
	
    <div class="menu_main" style="height:0px;width:990px;margin-left:0px;">
    <br />
    <div class="breadcrumb"><span>Ввод реквизитов для оплаты</span>&nbsp;-&nbsp;<span class="active">Подтверждение для проведения платежа</span>&nbsp;-&nbsp;<span>Подтверждение оплаты</span></div>
    <h1 style="margin: 10px 0px 0px 25px;">Подтверждение для проведения платежа</h1>
	<p style="color: #3F3F3F; font-size: 15px; margin: 0px 0px 0px 25px;">Пожалуйста, проверьте все приведенные ниже данные, и нажмите кнопку "Одобрить", если все правильно.</p>
	
<?php
	if ($messages) {
		echo '<div style="margin-left:50px;">';
		foreach ($messages as $message) {
			echo '<span style="color: red;">' . $message . '</span><br>';
		}
		echo '<br></div>';
	}
	$form = ActiveForm :: begin(['id' => 'payForm']);
	?>
	
	<input type="hidden" id="i_t" value="true" />
	<input type="hidden" name="step" value="2" />

	<input type="hidden" name="time" value="<?=time()?>" />
	<input type="hidden" name="agent_time" id="agent_time" value="" />

	<div style="width:100%; height: 415px; margin-bottom: 25px; border: 2px solid #D6D6D6;">
		<div style="width:100%; height: 201px; padding: 14px 0px 0px 0px;">
			<div style="width:60%; height: 186px; margin: 0 auto;">
				<style>
					td {
					  border-bottom: 1px dashed #ccc;
					  padding: 4px 15px;
					  color: #3F3F3F;
					  width: 50%;
					}
				</style>
				<table align="left" border="1" cellpadding="1" cellspacing="1" style="width:100%;">
					<tbody>
						<tr>
							<td>Номер карты плательщика</td>
							<td><?=$card_number?></td>
						</tr>
						<tr>
							<td>Сумма платежа</td>
							<td><?=$amount?> RUR</td>
						</tr>
						<tr>
							<td>Комиссия эквайрера</td>
							<td>0.00 RUR</td>
						</tr>
						<tr>
							<td>Номер платежа</td>
							<td><?=$transaction_id?></td>
						</tr>
						<tr>
							<td>Описание платежа</td>
							<td>Перевод с карты на карту</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	
	
	<div style="width:100%;">
		
		<div style="color: black; font-size: 18px; font-weight: normal; margin-left: 21%;">
			<input type="checkbox" id="agree" name="agree" style="vertical-align: text-top;"><label for="agree" style="padding-left: 10px; padding-top: 3px; color: black; font-size: 18px; font-weight: normal;">Согласен с <a href="" target="_blank">условиями договора-оферты</a></label>
		</div>
		<br/><br>

	    <div style="width: 800px; margin-left: 21%; float: left;">
				<input class="button" type="submit" value="Отмена" onclick="document.location='<?php echo BASE_URL; ?>';">		
				<input class="button" id="confirm2" disabled type="submit" value="Одобрить" style="margin-left: 50px;">			  
		</div>	
	</div>	

	</div>
    <?php
    ActiveForm :: end();
?>

	<div style="height:0px;width:990px;margin-left:0px;margin-top:110px;color:black;font-size: 14px;">

		<div style="width:100%;height:300px;">

			<div style="width:50%;float:left;">
				<p style="margin-top: 16px;"><span style="color:red;">*</span> отмеченные поля являются обьязательными для заполнения</p>
			</div>	
		
			<div style="width:50%;float:left;">
				<p style="margin-left: 50px;"><img src="/images/cards.png"></p>
			</div>
			
		</div>	


	</div>

    </div>