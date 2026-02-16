<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BackofficeUser $model */

$this->title = 'Update Backoffice User ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Backoffice Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id_backoffice_user' => $model->id_backoffice_user]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admin-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
