<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fnords';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fnord-index">

    <!-- Hero Section -->
    <div class="bg-primary bg-gradient text-white rounded-3 p-5 mb-5 shadow-sm">
        <h1 class="display-4 fw-bold mb-3"><?= Html::encode($this->title) ?></h1>
        <p class="lead mb-0">Explore our collection of fnords with their associated foo values</p>
    </div>

    <!-- Fnord Cards Grid -->
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_fnord_card',
        'layout' => "{items}\n<div class='d-flex justify-content-center mt-4'>{pager}</div>",
        'itemOptions' => ['class' => 'col-12 col-md-6 col-lg-4 mb-4'],
        'options' => ['class' => 'row g-4'],
    ]); ?>

</div>

<style>
.fnord-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.fnord-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge-foo {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.foo-count {
    font-size: 2rem;
    font-weight: bold;
}
</style>
