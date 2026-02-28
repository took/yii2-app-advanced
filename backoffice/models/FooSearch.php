<?php

namespace backoffice\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Foo;

/**
 * FooSearch represents the model behind the search form of `common\models\Foo`.
 */
class FooSearch extends Foo
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id_foo', 'id_fnord', 'foo_value'], 'integer'],
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
        $query = Foo::find();

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
            'id_foo' => $this->id_foo,
            'id_fnord' => $this->id_fnord,
            'foo_value' => $this->foo_value,
        ]);

        return $dataProvider;
    }
}
