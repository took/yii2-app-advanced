<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Fnord;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\Foo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="foo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_fnord')->dropDownList(
        ArrayHelper::map(Fnord::find()->all(), 'id_fnord', function($model) {
            return $model->id_fnord . ' - ' . ($model->bar ?: 'N/A');
        }),
        ['prompt' => 'Select Fnord']
    ) ?>

    <?= $form->field($model, 'foo_value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
