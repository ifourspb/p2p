<?php

/* @var $this yii\web\View */

$this->title = 'Ввод реквизитов для перевода';
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

?>
<div class="header">
    <div class="down_header" style="border-bottom: 6px solid #033C7B;border-top: 15px solid #033C7B;">
        <div class="logodiv">
            <img src="/images/logo.png" alt="СДМ Банк" class="logo" />
        </div>
    </div>
	
    <div class="menu_main" style="height:0px;width:990px;margin-left:0px;">
    <br />
    <div class="breadcrumb"><span class="active">Ввод реквизитов для перевода</span>&nbsp;-&nbsp;<span>Подтверждение для проведения перевода</span>&nbsp;-&nbsp;<span>Подтверждение перевода</span></div>
    <h1 style="margin-left: 25px;">Ввод реквизитов для перевода</h1>	
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

	<input type="hidden" name="step" value="1" />
	<input type="hidden" name="time" value="<?=time()?>" />
	<input type="hidden" name="agent_time" id="agent_time" value="" />
	

	<div style="width:100%;height:250px;">

	    <div style="width:48%;float:left;">
			<span style="color: #0c3870; margin-left: 30%;">ОТПРАВИТЕЛЬ</span>
			
		    <div style="margin-left: 50px;border-radius: 10px; margin-top: 10px; border: 3px solid #2266aa;border-style:dotted;height:230px;width:420px;">
				
			    <div class="inside field card" style="margin-left:30px;margin-top:10px;">
				 
				    <label for="check1" style="color:black;font-size:12px;font-weight:bold;margin-bottom: 5px">Номер карты</label><span style="color:red;">*</span>
					<br />
			        <input oninput="getNumeration ()" onpropertychange="getNumeration ()" name="card_number" maxlength="22" required style="width:320px;height:25px;" placeholder="  0000 0000 0000 0000" id="check1" type="text" value="<?=$card_number?>">
					 <br/><span id="card1_info"></span>
				</div>
				
			    <div class="inside" style="margin-left:30px;margin-top:8px; float: left;">
				    <label for="check2" style="color:black;font-size:12px;font-weight:bold;margin-bottom: 10px">Срок действия карты</label><span style="color:red;">*</span><br />
					<select name="month" required id="check2" style="height:32px;">
						<option value="" disabled selected> Месяц</option>					
						<?
							// массивыв годов и месяцев, для их переборов в селектах
							$allMonths = array(1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрель',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябрь',10=>'Октябрь',11=>'Ноябрь',12=>'Декабрь');
							
							$current_month = date('m')+0;
							$current_year = date('Y')+0;
							$cntMtn = count($allMonths);
							
							
							for($i=1;$i<=$cntMtn;$i++){
								
								if(false){								
						
									print('<option disabled value="'.$i.'">'.$allMonths[$i].'</option>');
									
								}else{
									
									echo '<option value="'.$i.'"';
									if ($i == $month) {
										echo ' selected ';
									}
									echo '>' . $allMonths[$i] . '</option>';
									
								}
								
							}					
						?>
                    </select>
					
					<span style="color: black;">&nbsp;/&nbsp;</span>
					<select name="year" required id="check3" style="height:32px;">
					    <option value="" disabled selected> Год</option>
					    <?
						$y1 = (int)date("Y");
                        for($i=$y1;$i<=($y1+20);$i++){
							
							if($i<$y1){								
					
								print('<option disabled value="'.$i.'">'.$i.'</option>');
								
							}else{
								
								echo '<option value="'.$i.'"'; 
								if ($year == $i) {
									echo ' selected ';
								}
								echo '>' . $i . '</option>';
								
							}
							
						}	
						?>
                    </select>
					<br/><span id="card2_info"></span>
			    </div>

				 <div class="inside" style="margin-left:20px;margin-top:8px; float: left;">
				   <label for="check5" style="color:black;font-size:12px;font-weight:bold;margin-bottom: 10px">CVV2/CVC2</label><span style="color:red;">*</span><br />
			        <input maxlength="3" required style="width:50px;height:32px;margin-left:10px;" name="cvv" placeholder=" 000" id="check5" type="text" value="<?=$cvv?>">
					<br/><span id="card3_info" style="margin-left:10px;"></span>
                </div>				

				<div style="clear: both;"></div>
			
			    <div class="inside" style="margin-left:30px;margin-top:8px;">
				    <label for="check4" style="color:black;font-size:12px;font-weight:bold; margin-bottom: 5px;">Держатель карты</label><span style="color:red;">*</span><br />
			        <input onkeyup="return LoginCheck(this);" required style="width:320px;height:25px;" placeholder=" Имя Фамилия (как указано на карте)" id="check4" value="<?=$placeholder?>" type="text" name="placeholder">
					<br/><span id="card4_info"></span>
			    </div>			


			
			</div>
		</div>	

		<div style="width: 20px; float: left; padding-left: 15px;">
			<img src="/images/arrow.png" style="margin-top: 80px;"/>
		</div>
	
	    <div style="width:48%;float:left;">
			<span style="color: #0c3870; margin-left: 30%;">ПОЛУЧАТЕЛЬ</span>
		    <div style="margin-left: 50px; margin-top: 10px; border-radius: 10px;border: 3px solid #2266aa;border-style:dotted;height:230px;width:420px;">
			
			     <div class="inside field card" style="margin-left:30px;margin-top:75px;">
				    <label for="check9" style="color:black;font-size:12px;font-weight:bold;margin-bottom: 5px">Номер карты</label><span style="color:red;">*</span>
					<br />
			        <input oninput="getNumeration2 ()" onpropertychange="getNumeration2 ()" name="card_number2" maxlength="22" required style="width:320px;height:32px;" placeholder="  0000 0000 0000 0000" id="check9" type="text" value="<?=$card_number2?>">
                     <br/>
					 <span id="card9_info"></span>
				</div>
			
			   	

			</div>
		</div>	
	</div>	

	<br/>
	<div style="margin-left: 50px; color: black;">
		<span style="color:red;">*</span> - поля, обязательные к заполнению.
	</div>
	<br>

	<div style="width:100%; height: 353px; margin-bottom: 15px; border: 2px solid #D6D6D6;">

	

		<div style="width:100%; height: 139px; padding: 4px 0px 0px 0px;">
			<div style="width:60%; height: 124px; margin: 0 auto;">
				<div class="inside field card" style="margin-left:120px;margin-top:5px;">
				    <label for="amount" style="color:black;font-size:12px;font-weight:bold;margin-bottom: 5px">Сумма перевода</label><span style="color:red;">*</span>
					<br />
			        <input  name="amount" maxlength="19" required style="width:320px;height:32px;" placeholder="0.00" id="amount" type="text" value="<?php echo $amount; ?>">
                    <br/><span id="card5_info"></span>

						<div style="width: 150px; color: black;  font-size: 12px;  font-weight: bold; ">
							<br/>
						
					 <label for="captcha" style="color:black;font-size:12px;font-weight:bold;margin-bottom: 5px">Проверочный код:</label><span style="color:red;">*</span>
					<img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" />
					<br>
					<a href="#" onclick="document.getElementById('captcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">Обновить</a>
					<br /><br/>
			        <input  name="captcha_code" maxlength="10" required style="<?php if ($verifyCodeError){?>border: 1px red solid;<?php } ?>width:100px;height:32px;" id="captcha_code" type="text" value="">
                    <br/><span id="captcha_info" style="color: red;"><?php if ($verifyCodeError){?> Неверно указан проверочный код<?php } ?></span>
					
					<div style="margin-top: 20px; margin-left: 20px;">	
						<input class="btn_blue" type="submit" value="Перевести" onclick="submitCheck(); return false;">			  
					</div>			  
				</div>
				</div>
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