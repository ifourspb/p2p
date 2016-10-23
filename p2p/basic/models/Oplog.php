<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oplog".
 *
 * @property integer $id
 * @property string $creation_d
 * @property string $src
 * @property string $descr
 * @property string $agent_time
 */
class Oplog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oplog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           /* [['creation_date', 'order_number', 'ip', 'agent', 'delta_time', 'src', 'descr', 'agent_time'], 'required'], */
            [['creation_date', 'agent_time'], 'safe'],
            [['transaction_id'], 'integer'],
            [['descr'], 'string'],
            [['ip', 'agent', 'src'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creation_date' => 'Дата создания',
            'transaction_id' => 'Номер операции',
            'ip' => 'Ip',
            'agent' => 'Браузер клиента',
            'agent_languages' => 'Языки клиента',
            'delta_time' => 'Дельта времени',
            'src' => 'Источник',
            'descr' => 'Описание',
            'agent_time' => 'Клиентское время',
        ];
    }

	public function Get_Client_Prefered_Language ($acceptedLanguages = false)
	{

		if (empty($acceptedLanguages))
			$acceptedLanguages = $_SERVER["HTTP_ACCEPT_LANGUAGE"];

			// regex inspired from @GabrielAnderson on http://stackoverflow.com/questions/6038236/http-accept-language
		preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})*)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptedLanguages, $lang_parse);
		$langs = $lang_parse[1];
		$ranks = $lang_parse[4];


			// (create an associative array 'language' => 'preference')
		$lang2pref = array();
		for($i=0; $i<count($langs); $i++)
			$lang2pref[$langs[$i]] = (float) (!empty($ranks[$i]) ? $ranks[$i] : 1);

			// (comparison function for uksort)
		$cmpLangs = function ($a, $b) use ($lang2pref) {
			if ($lang2pref[$a] > $lang2pref[$b])
				return -1;
			elseif ($lang2pref[$a] < $lang2pref[$b])
				return 1;
			elseif (strlen($a) > strlen($b))
				return -1;
			elseif (strlen($a) < strlen($b))
				return 1;
			else
				return 0;
		};

			// sort the languages by prefered language and by the most specific region
		uksort($lang2pref, $cmpLangs);
		
		ob_start();
		print_r($lang2pref);
		$buf = ob_get_contents();
		ob_end_clean();
		return $buf;
	}
}
