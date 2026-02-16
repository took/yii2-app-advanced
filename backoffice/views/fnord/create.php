<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Fnord $model */

$this->title = 'Create Fnord';
$this->params['breadcrumbs'][] = ['label' => 'Fnords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fnord-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
