<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BackofficeUserSearch provides search and filtering functionality for BackofficeUser listings.
 *
 * This model extends BackofficeUser to provide search capabilities for the backoffice
 * admin interface when managing backoffice administrator accounts.
 *
 * Features:
 * - Text search on username, email, and roles (LIKE queries)
 * - Status filtering (active, inactive, deleted)
 * - ID-based filtering
 * - Timestamp filtering for created_at and updated_at
 * - Used in backoffice backofficeUsersController
 * - Role filtering to find admins with specific permissions
 */
class BackofficeUserSearch extends BackofficeUser
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id_backoffice_user', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'roles'], 'safe'],
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
     * Builds a query for backoffice admin user management interface with support
     * for searching by role (useful for finding users with specific permissions).
     *
     * Supported filters:
     * - id_backoffice_user: Exact match by admin user ID
     * - username: Partial match search (LIKE)
     * - email: Partial match search (LIKE)
     * - roles: Partial match search on comma-separated roles (LIKE)
     * - status: Exact match (0=deleted, 9=inactive, 10=active)
     * - created_at: Exact match by timestamp
     * - updated_at: Exact match by timestamp
     *
     * @param array|null $params Request parameters containing BackofficeUserSearch values
     * @return ActiveDataProvider Configured data provider with sorting and pagination
     */
    public function search(?array $params): ActiveDataProvider
    {
        $query = BackofficeUser::find();

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
            'id_backoffice_user' => $this->id_backoffice_user,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'roles', $this->roles]);

        return $dataProvider;
    }
}
