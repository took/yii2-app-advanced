<?php

use common\models\BackofficeUser;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\BackofficeUser $model */

$this->title = 'Backoffice User ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Backoffice Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="admin-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_backoffice_user' => $model->id_backoffice_user], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_backoffice_user' => $model->id_backoffice_user], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Resend Invite Link', ['resend-invite-link', 'id_backoffice_user' => $model->id_backoffice_user], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Send invite email to "' . $model->email . '" now?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id_backoffice_user',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (BackofficeUser $user) {
                    $status = [
                        BackofficeUser::STATUS_DELETED => 'Deleted',
                        BackofficeUser::STATUS_INACTIVE => 'Inactive',
                        BackofficeUser::STATUS_ACTIVE => 'Active',
                    ];
                    return Html::encode($status[$user->status] ?? $user->status);
                },
            ],
            [
                'attribute' => 'roles',
                'format' => 'raw',
                'value' => function (BackofficeUser $user) {
                    $user_roles = explode(',', $user->roles);
                    $roles = [];
                    foreach (Yii::$app->params['backofficeRoles'] as $role => $description) {
                        if (in_array($role, $user_roles)) {
                            $roles[] = Html::tag('li', Html::encode($role));
                        }
                    }
                    return Html::tag('ul', implode(PHP_EOL, $roles));
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function (BackofficeUser $user) {
                    return Html::encode(date('Y-m-d H:i:s', strtotime($user->created_at)));
                },
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => function (BackofficeUser $user) {
                    return Html::encode(date('Y-m-d H:i:s', strtotime($user->updated_at)));
                },
            ],
            /*[
                'attribute' => 'date_last_login',
                'format' => 'raw',
                'value' => function (BackofficeUser $user) {
                    return $user->date_last_login
                        ? Html::encode(date('Y-m-d H:i:s', strtotime($user->date_last_login)))
                        : Html::tag('span', 'Never', ['class' => 'text-muted']);
                },
            ],*/
        ],
    ]) ?>

</div>
