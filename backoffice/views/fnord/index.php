<?php

use common\models\Fnord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var backoffice\models\FnordSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fnords';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fnord-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fnord', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_fnord',
            'bar',
            'baz',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Fnord $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_fnord' => $model->id_fnord]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
