<?php

/* @var $this yii\web\View */

$this->title = 'Статус перевода';
use yii\bootstrap\ActiveForm;

if ($print) {
?>
<pre>
*** СДМ-БАНК” (ПАО) ***

ВОЛОКОЛАМСКОЕ Ш. 73

ТЕЛЕФОНЫ ЦЕНТРА ОБСЛУЖИВАНИЯ:

8-800-200-02-23 - ЗВОНОК ПО РОССИИ БЕСПЛАТНЫЙ

ДАТА <?=date("d/m/Y")?> ВРЕМЯ <?=date("H:i:s")?>

ДАТА ПЛАТЕЖА <?=date("d.m.Y", strtotime($transaction->creation_date))?> ВРЕМЯ ПЛАТЕЖА <?=date("H:i:s", strtotime($transaction->creation_date))?>

НОМЕР КАРТЫ ПЛАТЕЛЬЩИКА: <?=$payment_from?>


-- -- -- -- -- -- -- ПЕРЕВОД С КАРТЫ НА КАРТУ-- -- -- -- -- -- --

НОМЕР КАРТЫ ПОЛУЧАТЕЛЯ: <?=$payment_to?>

ТЕРМИНАЛ : <?=SDM_TERMINAL?>

КОД АВТОРИЗАЦИИ : <?=$transaction->authcode?>

RRN : <?=$transaction->rrn?>

СУММА : <?=$transaction->amount?> RUR

КОМИССИЯ : 0.00 RUR

С НАИЛУЧШИМИ ПОЖЕЛАНИЯМИ, СДМ -БАНК
</pre>
<script>
window.print();
</script>

<?php }else {

?>
<div class="header">
    <div class="down_header" style="border-bottom: 6px solid #033C7B;border-top: 15px solid #033C7B;">
        <div class="logodiv">
            <a href="/"><img src="/images/logo.png" alt="СДМ Банк" class="logo" /></a>
        </div>
    </div>
	

	<div class="menu_main" style="height:0px;width:990px;margin-left:0px;">
    <br />
    <div class="breadcrumb"><span>Ввод реквизитов для оплаты</span>&nbsp;-&nbsp;<span>Подтверждение для проведения платежа</span>&nbsp;-&nbsp;<span class="active">Подтверждение оплаты</span></div>
    <div style="width: 100%; display: inline-block;">
		<div style="float: left; width: 50%;">
			<h1 style="margin: 10px 0px 12px 25px;">Успешная оплата</h1>
		</div>
		<div style="margin: 10px 0px 12px 25px; float: right;">
			<a href="<?php echo $print_url; ?>"><img src="images/1_b.png"> </a>
			
		</div>
	</div>
<form method="post" action="/" name="MyForm9" enctype="multipart/form-data">	
	<input type="hidden" id="i_t" value="true" />
	<div style="width:100%; height: 404px; margin-bottom: 25px; border: 2px solid #D6D6D6;">
		<div style="width:100%; height: 390px; padding: 14px 0px 0px 0px;">
			<div style="width:60%; height: 375px; margin: 0 auto;">
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
							<td><?=$payment_from?></td>
						</tr>
						<tr>
							<td>Номер карты получателя</td>
							<td><?=$payment_to?></td>
						</tr>
						<tr>
							<td>Сумма платежа</td>
							<td><?=$transaction->amount?> RUR</td>
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
							<td>Код авторизации</td>
							<td><?=$transaction->authcode?></td>
						</tr>
						<tr>
							<td>RRN</td>
							<td><?=$transaction->rrn?></td>
						</tr>
	
						<tr>
							<td>Дата платежа</td>
							<td><?=date("d.m.Y", strtotime($transaction->creation_date))?></td>
						</tr>
						<tr>
							<td>Время платежа</td>
							<td><?=date("H:i:s", strtotime($transaction->creation_date))?></td>
						</tr>
						
						<?php if ($transaction->success == 1) {?>
							<tr>
								<td>Результат</td>
								<td style="color: #023467;">УСПЕШНО</td>
							</tr>
						<?php }else {?>
							<tr>
							<td>Результат</td>
							<td style="color: #9C3F3F; font-weight: bold;">ОТКАЗАНО</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				
			</div>
			<div style='width: 100%; margin-top: -50px;;'>	
					<div style="width:50%; margin: 0 auto;">	
							<a class="button btn_blue" type="submit" value="Вернуться к форме" href='<?php echo BASE_URL; ?>'>Вернуться к форме</a>				  
					</div>	
				</div>
		</div>
	</div>
	

	
    </form>


	

	<div style="height:0px;width:990px;margin-left:0px;margin-top:110px;color:black;font-size: 14px;">

		<div style="width:100%;height:300px;">

			
		
			<div style="width:50%;float:left;">
				<p style="margin-left: 50px;"><img src="/images/cards.png"></p>
			</div>
			
		</div>	


	</div>

    </div>

<?php } ?>