<?php

use common\models\BackofficeUser;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\BackofficeUser $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([
        BackofficeUser::STATUS_DELETED => 'Deleted',
        // BackofficeUser::STATUS_INACTIVE => 'Inactive',
        BackofficeUser::STATUS_ACTIVE => 'Active',
    ]) ?>

    <?php
    $roles = explode(',', $model->roles);
    $selection = [];
    $items = [];
    foreach (Yii::$app->params['backofficeRoles'] as $role => $description) {
        if (in_array($role, $roles)) {
            $selection[] = $role;
        }
        $items[$role] = $role . ': ' . $description;
    }
    echo Html::checkboxList('roles', $selection, $items, ['separator' => '<br/>']);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
