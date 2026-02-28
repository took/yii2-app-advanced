<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User|common\models\BackofficeUser $user */
/** @var string $valid_until */
/** @var string $resetLink */
/** @var string $requestPasswordResetLink */

?>
<div class="verify-email">
    <p>Hello <?= Html::encode($user->username) ?>!</p>

    <p>Great news: You have been invited to our awesome project!</p>

    <p>Follow the link below to set your password:</p>

    <p>
        <?= Html::a(Html::encode($resetLink), $resetLink) ?><br>
        This link is valid until <b><?= Html::encode($valid_until) ?></b>. After that date you may use your
        email address <b><?= Html::encode($user->email) ?></b> to request a new password reset.
    </p>

    <hr>

    <p>
        Your username is:<br>
        <b><?= Html::encode($user->username) ?></b>
    </p>

    <p>
        If you need to reset your password, use this email adresse to receive a new reset link: <br>
        <b><?= Html::encode($user->email) ?></b>
    </p>

    <p>
        Request new password reset: <br>
        <b><?= Html::encode($requestPasswordResetLink) ?></b>
    </p>

</div>
