<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

	<link rel="stylesheet" type="text/css" href="/css/site.css" />
<link rel="stylesheet" type="text/css" href="/css/base.css" />
<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/js/jquery.cardcheck.js" type="text/javascript"></script>
  <style type="text/css">
    .card .card_icon,
    .card .status_icon {
      /* For a more robust cross-browser implementation, see http://bit.ly/aqZnl3 */
      display: inline-block;
      vertical-align: bottom;
      height: 23px;
      width: 27px;
    }

    /* --- Card Icon --- */
    .card .card_icon {
      background: transparent url('/images/credit_card_sprites.png') no-repeat 30px 0;
    }
    
    /* Need to support IE6? These four rules won't work, so rewrite 'em. */
    .card .card_icon.visa       { background-position:   0   0 !important; }
    .card .card_icon.mastercard { background-position: -30px 0 !important; }
    .card .card_icon.amex       { background-position: -60px 0 !important; }
    .card .card_icon.discover   { background-position: -90px 0 !important; }

    /* --- Card Status --- */
    .card .status_icon {
      background: transparent url('img/status_sprites.png') no-repeat 33px 0;
    }
    .card_invalid              { color: #AD3333; background: #f8e7e7; font-size: 70%;}
    .card_valid                { color: #33AD33; background: #e7f8e7;font-size: 70%;}
    .card .invalid .status_icon { background-position: 3px 0 !important; }
    .card .valid .status_icon   { background-position: -27px 0 !important; }
    .button{
      text-decoration:none; 
      text-align:center; 
      padding:8px 65px;  
      -webkit-border-radius:4px;
      -moz-border-radius:4px; 
      border-radius: 4px; 
      font:18px Arial, Helvetica, sans-serif; 
      font-weight:bold; 
      color:white!important; 
      background-color:#d9dee0; 
      background-image: -moz-linear-gradient(top, #d9dee0 0%, #545657 100%); 
      background-image: -webkit-linear-gradient(top, #d9dee0 0%, #545657 100%); 
      background-image: -o-linear-gradient(top, #d9dee0 0%, #545657 100%); 
      background-image: -ms-linear-gradient(top, #d9dee0 0% ,#545657 100%); 
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#545657', endColorstr='#545657',GradientType=0 ); 
      background-image: linear-gradient(top, #d9dee0 0% ,#545657 100%);   
      -webkit-box-shadow:0px 0px 2px #bababa, inset 0px 0px 1px #ffffff; 
      -moz-box-shadow: 0px 0px 2px #bababa,  inset 0px 0px 1px #ffffff;  
      box-shadow:0px 0px 2px #bababa, inset 0px 0px 1px #ffffff;  
    }
    .button2{
      text-decoration:none; 
      text-align:center; 
      padding:8px 65px;  
      -webkit-border-radius:4px;
      -moz-border-radius:4px; 
      border-radius: 4px; 
      font:18px Arial, Helvetica, sans-serif; 
      font-weight:bold; 
      color:white; 
      background-color:#6f9ed1; 
      background-image: -moz-linear-gradient(top, #6f9ed1 0%, #073a91 100%); 
      background-image: -webkit-linear-gradient(top, #6f9ed1 0%, #073a91 100%); 
      background-image: -o-linear-gradient(top, #6f9ed1 0%, #073a91 100%); 
      background-image: -ms-linear-gradient(top, #6f9ed1 0% ,#073a91 100%); 
      filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#073a91', endColorstr='#073a91',GradientType=0 ); 
      background-image: linear-gradient(top, #6f9ed1 0% ,#073a91 100%);   
      -webkit-box-shadow:0px 0px 2px #bababa, inset 0px 0px 1px #ffffff; 
      -moz-box-shadow: 0px 0px 2px #bababa,  inset 0px 0px 1px #ffffff;  
      box-shadow:0px 0px 2px #bababa, inset 0px 0px 1px #ffffff; 
    }
	
	  .btn_blue {
		border:none;
		
		text-decoration:none;
		text-align:center;
		padding:8px 65px;
		-webkit-border-radius:4px;
		  -moz-border-radius:4px;
		  border-radius: 4px;
		  font:18px Arial, Helvetica, sans-serif; 
		  font-weight:bold; 
		  color:white; 
		  background-color:#6f9ed1; 
		  background-image: -moz-linear-gradient(top, #6f9ed1 0%, #073a91 100%); 
		  background-image: -webkit-linear-gradient(top, #6f9ed1 0%, #073a91 100%); 
		  background-image: -o-linear-gradient(top, #6f9ed1 0%, #073a91 100%); 
		  background-image: -ms-linear-gradient(top, #6f9ed1 0% ,#073a91 100%); 
		  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#073a91', endColorstr='#073a91',GradientType=0 ); 
		  background-image: linear-gradient(top, #6f9ed1 0% ,#073a91 100%);   
		  -webkit-box-shadow:0px 0px 2px #bababa, inset 0px 0px 1px #ffffff; 
		  -moz-box-shadow: 0px 0px 2px #bababa,  inset 0px 0px 1px #ffffff;  
		  box-shadow:0px 0px 2px #bababa, inset 0px 0px 1px #ffffff;cursor:pointer;
	  }

  </style>
<script type="text/javascript">
// проверка карты
    jQuery(function($) {
        
        // If JavaScript is enabled, hide fallback select field
        $('.no-js').removeClass('no-js').addClass('js');
        
        // When the user focuses on the credit card input field, hide the status
        $('.card input').bind('focus', function() {
            $('.card .status').hide();
        });
        
        // When the user tabs or clicks away from the credit card input field, show the status
        $('.card input').bind('blur', function() {
            $('.card .status').show();
        });
        
        // Run jQuery.cardcheck on the input
        $('#check1').cardcheck({
            callback: function(result) {
               
                var status = (result.validLen && result.validLuhn) ? 'valid' : 'invalid',
                    message = '',
                    types = '';
                
                // Get the names of all accepted card types to use in the status message.
                for (i in result.opts.types) {
                    types += result.opts.types[i].name + ", ";
                }
                types = types.substring(0, types.length-2);
               
                // Set status message
				var ok = 0;
                if (result.len < 1) {
                    message = 'Пожалуйста, введите номер карты';
                } else if (!result.cardClass) {
                    message = 'Мы принимаем карты следующих видов: ' + types + '.';
                } else if (!result.validLen) {
                    message = 'Недостаточное количиство символов';
                } else if (!result.validLuhn) {
                    message = 'Некорректный номер карты ' + result.cardName + '';
                } else {
					ok = 1;
                    message = '' + result.cardName + '';
                }
                $("#card1_info").text(message);
				if (ok == 0)
				{
					//$("#submit_btn").removeClass("btn_blue").addClass("button");
					//$("#submit_btn").attr("disabled", true);
					$("#card1_info").addClass('card_invalid');
				}else {
					//$("#submit_btn").removeClass("button").addClass("btn_blue");
					//$("#submit_btn").attr("disabled", false);
					$("#card1_info").addClass('card_valid');
				}
            }
        });

			 // Run jQuery.cardcheck on the input
        $('#check9').cardcheck({
            callback: function(result) {
               
                var status = (result.validLen && result.validLuhn) ? 'valid' : 'invalid',
                    message = '',
                    types = '';
                
                // Get the names of all accepted card types to use in the status message.
                for (i in result.opts.types) {
                    types += result.opts.types[i].name + ", ";
                }
                types = types.substring(0, types.length-2);
               
                // Set status message
				var ok = 0;
                if (result.len < 1) {
                    message = 'Пожалуйста, введите номер карты';
                } else if (!result.cardClass) {
                    message = 'Мы принимаем карты следующих видов: ' + types + '.';
                } else if (!result.validLen) {
                    message = 'Недостаточное количиство символов';
                } else if (!result.validLuhn) {
                    message = 'Некорректный номер карты ' + result.cardName + '';
                } else {
					ok = 1;
                    message = '' + result.cardName + '';
                }
                $("#card9_info").text(message);
				if (ok == 0)
				{
					//$("#submit_btn").removeClass("btn_blue").addClass("button");
					//$("#submit_btn").attr("disabled", true);
					$("#card9_info").addClass('card_invalid');
				}else {
					//$("#submit_btn").removeClass("button").addClass("btn_blue");
					//$("#submit_btn").attr("disabled", false);
					$("#card9_info").addClass('card_valid');
				}
            }
        });


    });
// разрешаем только цифры
$(document).ready(function() {
	var now = new Date();

	$("#agent_time").val( new Date().toJSON() );
	//alert(new Date().toJSON());
    $("#check1,#check5,#check9").keydown(function(event) {
        // Разрешаем: backspace, delete, tab и escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
             // Разрешаем: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Разрешаем: home, end, влево, вправо
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // Ничего не делаем
                 return;
        }
        else {
            // Обеждаемся, что это цифра, и останавливаем событие keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });

	$('#agree').on('change', function() { 
		
        if  (this.checked) {
			$("#confirm2").attr('disabled', false);
			$("#confirm2").removeClass('button').addClass('btn_blue');
        }else {
				$("#confirm2").attr('disabled', true);
				$("#confirm2").addClass('button').removeClass('btn_blue');
		}
    });

	
	$("#amount").keydown(function(event) {
        // Разрешаем: backspace, delete, tab и escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
             // Разрешаем: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Разрешаем: home, end, влево, вправо
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // Ничего не делаем
                 return;
        }
        else {
			if (event.keyCode == 188 || event.keyCode == 190)
			{
			}else {
				// Обеждаемся, что это цифра, и останавливаем событие keypress
				 if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
					event.preventDefault(); 
				}   
			}
        }
    });
	getNumeration ();
	getNumeration2 ()

});
// разбиение поля номер карты на разряды
function getNumeration ()
{
	var R = 4; // разрядность
	var obj = document.getElementById ('check1');
	obj.onpropertychange = null;
	for (var arr = obj.value.replace (/\s/g, '').split ('').reverse (),
				rez = [], j = 0, lj = arr.length; j < lj; j++) {
						rez [j] = (((j + 1) % R) ? '' : ' ') + arr [j];
	}
	if (rez [j - 1].length == 2) {
		rez [j - 1] = rez [j - 1].substr (1);
	}
	obj.value = rez.reverse ().join ('');
	
	obj.onpropertychange = getNumeration;
}

// разбиение поля номер карты на разряды
function getNumeration2 ()
{
var R = 4; // разрядность
var obj = document.getElementById ('check9');
obj.onpropertychange = null;

for (var arr = obj.value.replace (/\s/g, '').split ('').reverse (),
rez = [], j = 0, lj = arr.length; j < lj; j++)
   rez [j] = (((j + 1) % R) ? '' : ' ') + arr [j];
if (rez [j - 1].length == 2) rez [j - 1] = rez [j - 1].substr (1);
obj.value = rez.reverse ().join ('');
obj.onpropertychange = getNumeration2;
}



// проверка на латинские буквы
function LoginCheck(input) {
    var value = input.value;
    var re = /[0-9а-я./,]+/gi;
    if (re.test(value)) {
        value = value.replace(re, '');
        input.value = value;
    }
}

function submitCheck() {
	var fail = 0;
	if (!lunaCheck($("#check1").val())) {
		fail = 1;
		$("#card1_info").text('Некорректный номер карты');
		$("#card1_info").addClass('card_invalid');
	}else {
		$("#card1_info").text('');
		$("#card1_info").addClass('card_valid');
	}

	if (!lunaCheck($("#check9").val())) {
		fail = 1;
		$("#card9_info").text('Некорректный номер карты');
		$("#card9_info").addClass('card_invalid');
	}else {
		$("#card9_info").text('');
		$("#card9_info").addClass('card_valid');
	}
	if ( !$("#check2").val() ||  !$("#check3").val())
	{
		fail = 1;
		$("#card2_info").text('Заполните данное поле');
		$("#card2_info").addClass('card_invalid');
	}else {
		$("#card2_info").text('');
		$("#card2_info").addClass('card_valid');
	}


	if ( $("#check5").val() .length < 3)
	{
		fail = 1;
		$("#card3_info").text('Заполните данное поле');
		$("#card3_info").addClass('card_invalid');
	}else {
		$("#card3_info").text('');
		$("#card3_info").addClass('card_valid');
	}

	if ( $("#check4").val() .length == 0)
	{
		fail = 1;
		$("#card4_info").text('Заполните данное поле');
		$("#card4_info").addClass('card_invalid');
	}else {
		$("#card4_info").text('');
		$("#card4_info").addClass('card_valid');
	}

	if ( $("#amount").val() .length == 0)
	{
		fail = 1;
		$("#card5_info").text('Заполните данное поле');
		$("#card5_info").addClass('card_invalid');
	}else {
		$("#card5_info").text('');
		$("#card5_info").addClass('card_valid');
	}

	if (fail == 0) {
		$("#payForm").submit();
	}else{
		return false;
	}
}


function lunaCheck (num2) {
	var num = num2.replace(/\s+/g, '');
    var len = num.length;
	
	if (!num || !len) {
		return false;
    }
			
     num = num.split('').reverse();
     var total = 0,i;
     for (i = 0; i < len; i++) {
		num[i] = window.parseInt(num[i], 10);
        total += i % 2 ? 2 * num[i] - (num[i] > 4 ? 9 : 0) : num[i];
     }

	 return ((total % 10 === 0) === true);
}


</script>

</head>
<body>
<?php $this->beginBody() ?>


<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
