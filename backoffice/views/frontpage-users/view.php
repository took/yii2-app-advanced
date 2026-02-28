<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Frontpage User ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Frontpage Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $canInviteFrontpageUser = \Yii::$app->user->can('invite-frontpage-user');
        $canEditFrontpageUser = \Yii::$app->user->can('edit-frontpage-user');
        if ($canEditFrontpageUser) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . PHP_EOL;
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) . PHP_EOL;
        }
        if ($canEditFrontpageUser || $canInviteFrontpageUser) {
            echo Html::a('Resend Invite Link', ['resend-invite-link', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Send invite email to "' . $model->email . '" now?',
                        'method' => 'post',
                    ],
                ]) . PHP_EOL;
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (User $user) {
                    $status = [
                        User::STATUS_DELETED => 'Deleted',
                        User::STATUS_INACTIVE => 'Inactive',
                        User::STATUS_ACTIVE => 'Active',
                    ];
                    return Html::encode($status[$user->status] ?? $user->status);
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function (User $user) {
                    return Html::encode(date('Y-m-d H:i:s', strtotime($user->created_at)));
                },
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => function (User $user) {
                    return Html::encode(date('Y-m-d H:i:s', strtotime($user->updated_at)));
                },
            ],
            // 'verification_token',
        ],
    ]) ?>

</div>
