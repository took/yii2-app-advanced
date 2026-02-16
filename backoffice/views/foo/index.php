<?php

use common\models\Foo;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backoffice\models\FooSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Foos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Foo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_foo',
            [
                'attribute' => 'id_fnord',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->fnord) {
                        return Html::a($model->fnord->id_fnord, ['/fnord/view', 'id_fnord' => $model->fnord->id_fnord]);
                    }
                    return null;
                },
            ],
            'foo_value',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Foo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_foo' => $model->id_foo]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
