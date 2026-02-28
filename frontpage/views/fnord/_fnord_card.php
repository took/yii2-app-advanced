<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Fnord $model */

$fooCount = count($model->foos);
?>

<div class="card h-100 shadow-sm fnord-card">
    <div class="card-header bg-primary bg-gradient text-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-box"></i> Fnord #<?= Html::encode($model->id_fnord) ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <?php if ($model->bar): ?>
                <div class="mb-2">
                    <span class="badge bg-info text-dark me-2">Bar</span>
                    <span class="text-muted"><?= Html::encode($model->bar) ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($model->baz): ?>
                <div class="mb-2">
                    <span class="badge bg-warning text-dark me-2">Baz</span>
                    <span class="text-muted"><?= Html::encode($model->baz) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <hr>

        <div class="d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted d-block">Related Foos</small>
                <span class="foo-count text-primary"><?= $fooCount ?></span>
            </div>
            <?php if ($fooCount > 0): ?>
                <div class="d-flex gap-1 flex-wrap">
                    <?php foreach ($model->foos as $index => $foo): ?>
                        <?php if ($index < 3): ?>
                            <span class="badge bg-success badge-foo" title="Foo Value: <?= $foo->foo_value ?>">
                                <?= $foo->foo_value ?>
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($fooCount > 3): ?>
                        <span class="badge bg-secondary badge-foo">+<?= $fooCount - 3 ?></span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <span class="text-muted small">No foos</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-footer bg-light">
        <?= Html::a(
            '<i class="bi bi-eye"></i> View Details',
            ['view', 'id_fnord' => $model->id_fnord],
            ['class' => 'btn btn-sm btn-outline-primary w-100']
        ) ?>
    </div>
</div>
