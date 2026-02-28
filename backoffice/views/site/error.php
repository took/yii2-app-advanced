<?php

/** @var yii\web\View $this */
/** @var Exception|null $exception */

use yii\helpers\Html;

if (YII_ENV == 'dev' && $exception !== null) {
    $name = $exception->getName() ?? 'Error';
    $message = $exception->getMessage() ?? 'An error occurred.';
} else {
    $name = 'Error';
    $message = 'An error occurred.';
}

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

</div>
