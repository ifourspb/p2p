<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "syslog".
 *
 * @property integer $id
 * @property string $date
 * @property string $src
 * @property string $tags
 * @property string $descr
 */
class Syslog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'syslog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'src', 'descr'], 'required'],
            [['date'], 'safe'],
            [['tags', 'descr'], 'string'],
            [['src'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'src' => 'Источник',
            'descr' => 'Содержание'
		];
	}
       
    /**
     * @inheritdoc
     * @return SyslogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SyslogQuery(get_called_class());
    }
}
