<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Fnord $model */

$this->title = 'Update Fnord: ' . $model->id_fnord;
$this->params['breadcrumbs'][] = ['label' => 'Fnords', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Fnord #' . $model->id_fnord, 'url' => ['view', 'id_fnord' => $model->id_fnord]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fnord-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
