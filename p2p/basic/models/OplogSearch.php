<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Oplog;

/**
 * OplogSearch represents the model behind the search form about `app\models\Oplog`.
 */
class OplogSearch extends Oplog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'transaction_id', 'delta_time'], 'integer'],
            [['creation_date', 'ip', 'agent', 'src', 'descr', 'agent_time', 'agent_language'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Oplog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'creation_date' => $this->creation_date,
            'transaction_id' => $this->transaction_id,
            'delta_time' => $this->delta_time,
            'agent_time' => $this->agent_time,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'src', $this->src])
            ->andFilterWhere(['like', 'descr', $this->descr])
            ->andFilterWhere(['like', 'agent_language', $this->agent_language]);

        return $dataProvider;
    }
}
