<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

/*$_POST = unserialize('a:13:{s:8:"Function";s:4:"TR_1";s:6:"Result";s:1:"3";s:2:"RC";s:3:"-21";s:6:"Amount";s:7:"2499.00";s:8:"Currency";s:3:"643";s:5:"Order";s:8:"77777799";s:6:"TRType";s:2:"14";s:3:"RRN";s:12:"623285082016";s:6:"IntRef";s:16:"A41768956B39B787";s:8:"AuthCode";s:0:"";s:3:"Fee";s:0:"";s:4:"Time";s:0:"";s:6:"P_Sign";s:40:"6FFFEE29579A829E3D9468682ED0F1373B5A12C7";}');
*/

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
