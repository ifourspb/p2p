<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Oplog]].
 *
 * @see Oplog
 */
class OplogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Oplog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Oplog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
