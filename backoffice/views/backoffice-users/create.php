<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BackofficeUser $model */

$this->title = 'Create Backoffice User';
$this->params['breadcrumbs'][] = ['label' => 'Backoffice Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
