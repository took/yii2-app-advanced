<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Fnord $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fnord-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'baz')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
