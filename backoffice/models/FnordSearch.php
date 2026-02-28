<?php

namespace backoffice\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fnord;

/**
 * FnordSearch represents the model behind the search form of `common\models\Fnord`.
 */
class FnordSearch extends Fnord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id_fnord'], 'integer'],
            [['bar', 'baz'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
    public function search(array $params): ActiveDataProvider
    {
        $query = Fnord::find();

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
            'id_fnord' => $this->id_fnord,
        ]);

        $query->andFilterWhere(['like', 'bar', $this->bar])
            ->andFilterWhere(['like', 'baz', $this->baz]);

        return $dataProvider;
    }
}
