<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch provides search and filtering functionality for frontpage User listings.
 *
 * This model extends User to provide search capabilities for the backoffice admin
 * interface when managing frontpage user accounts.
 *
 * Features:
 * - Text search on username and email (LIKE queries)
 * - Status filtering (active, inactive, deleted)
 * - ID-based filtering
 * - Timestamp filtering for created_at and updated_at
 * - Used in backoffice FrontpageUsersController
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id_user', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email'], 'safe'],
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
     * Creates a configured ActiveDataProvider with search filters applied.
     *
     * Builds a query for frontpage user management in the backoffice admin interface.
     *
     * Supported filters:
     * - id: Exact match by user ID
     * - username: Partial match search (LIKE)
     * - email: Partial match search (LIKE)
     * - status: Exact match (0=deleted, 9=inactive, 10=active)
     * - created_at: Exact match by timestamp
     * - updated_at: Exact match by timestamp
     *
     * @param array|null $params Request parameters containing UserSearch values
     * @return ActiveDataProvider Configured data provider with sorting and pagination
     */
    public function search(?array $params): ActiveDataProvider
    {
        $query = User::find();

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
            'id_user' => $this->id_user,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
