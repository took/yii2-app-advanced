<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Fnord $model */

$this->title = 'Fnord #' . $model->id_fnord;
$this->params['breadcrumbs'][] = ['label' => 'Fnords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fnord-view">

    <!-- Hero Section with Fnord Details -->
    <div class="bg-primary bg-gradient text-white rounded-3 p-5 mb-4 shadow">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="display-4 fw-bold mb-3">
                    <i class="bi bi-box"></i> <?= Html::encode($this->title) ?>
                </h1>
                <div class="lead">
                    <?php if ($model->bar): ?>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark me-2">Bar</span>
                            <span><?= Html::encode($model->bar) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($model->baz): ?>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark me-2">Baz</span>
                            <span><?= Html::encode($model->baz) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <?= Html::a(
                    '<i class="bi bi-arrow-left"></i> Back to List',
                    ['index'],
                    ['class' => 'btn btn-light']
                ) ?>
            </div>
        </div>
    </div>

    <!-- Related Foo Records Section -->
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">
                <i class="bi bi-list-ul"></i> Related Foo Records
                <span class="badge bg-primary ms-2"><?= count($model->foos) ?></span>
            </h2>
        </div>

        <?php if (empty($model->foos)): ?>
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle me-3 fs-4"></i>
                <div>
                    <strong>No foo records found.</strong> This fnord doesn't have any associated foo records yet.
                </div>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($model->foos as $foo): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm foo-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-circle-fill text-success"></i>
                                        Foo #<?= Html::encode($foo->id_foo) ?>
                                    </h5>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block mb-1">Foo Value</small>
                                        <h3 class="mb-0 text-primary fw-bold">
                                            <?= Html::encode($foo->foo_value ?? 0) ?>
                                        </h3>
                                    </div>
                                    <div class="text-end">
                                        <?php
                                        $value = $foo->foo_value ?? 0;
                                        if ($value >= 100) {
                                            $badgeClass = 'bg-success';
                                            $label = 'High';
                                        } elseif ($value >= 50) {
                                            $badgeClass = 'bg-warning text-dark';
                                            $label = 'Medium';
                                        } else {
                                            $badgeClass = 'bg-secondary';
                                            $label = 'Low';
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?> fs-6">
                                            <?= $label ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<style>
.foo-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.foo-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.bi {
    vertical-align: middle;
}
</style>
