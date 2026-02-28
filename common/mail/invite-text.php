<?php

/** @var yii\web\View $this */
/** @var common\models\User|common\models\BackofficeUser $user */
/** @var string $valid_until */
/** @var string $resetLink */
/** @var string $requestPasswordResetLink */

?>
Hello <?= $user->username ?>!

Great news: You have been invited to our awesome project!

Follow the link below to set your password:

<?= $resetLink ?>

This link is valid until <?= $valid_until ?>. After that date you may use your email address <?= $user->email ?> to request a new password reset.


Your username is:
<?= $user->username ?>

If you need to reset your password, use this email adresse to receive a new reset link:
<?= $user->email ?>

Request new password reset:
<?= $requestPasswordResetLink ?>
