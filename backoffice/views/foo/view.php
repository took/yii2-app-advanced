<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Foo $model */

$this->title = 'Fnord #' . ($model->fnord?->bar ?? 'N/A') . ' | Foo #' . $model->foo_value;
$this->params['breadcrumbs'][] = ['label' => 'Foos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="foo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_foo' => $model->id_foo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_foo' => $model->id_foo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_foo',
            [
                'attribute' => 'id_fnord',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->fnord) {
                        $text = $model->fnord->id_fnord . ' - ' . $model->fnord->bar;
                        return Html::a($text, ['/fnord/view', 'id_fnord' => $model->fnord->id_fnord]);
                    }
                    return null;
                },
            ],
            'foo_value',
        ],
    ]) ?>

</div>
