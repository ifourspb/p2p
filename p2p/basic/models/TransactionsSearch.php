<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transactions;

/**
 * TransactionsSearch represents the model behind the search form about `app\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'success'], 'integer'],
            [['creation_date', 'payment_to', 'payment_from', 'rrn', 'amount', 'currency', 'answer_date', 'answer_data', 'debug'], 'safe'],
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
        $query = Transactions::find();
		
		
		
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		if (!$params) {
			$dataProvider->setSort([
				'defaultOrder' => ['id'=>SORT_DESC]
			]);
		}

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'payment_to' => $this->payment_to,
            'payment_from' => $this->payment_from,
            'rrn' => $this->rrn,
            'answer_date' => $this->answer_date,
            'success' => $this->success,
        ]);

        $query->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'creation_date', $this->creation_date])
            ->andFilterWhere(['like', 'answer_data', $this->answer_data])
            ->andFilterWhere(['like', 'debug', $this->debug]);

        return $dataProvider;
    }
}
