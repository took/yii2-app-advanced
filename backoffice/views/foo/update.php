<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Foo $model */

$this->title = 'Update Fnord #' . ($model->fnord?->bar ?? 'N/A') . ' | Foo: ' . $model->id_foo;
$this->params['breadcrumbs'][] = ['label' => 'Foos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_foo, 'url' => ['view', 'id_foo' => $model->id_foo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="foo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
