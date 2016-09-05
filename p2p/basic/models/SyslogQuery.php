<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Syslog]].
 *
 * @see Syslog
 */
class SyslogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Syslog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Syslog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
