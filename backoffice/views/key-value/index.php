<?php

use common\models\KeyValue;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var KeyValue $model */

$this->title = 'Key-Value Configuration';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .kv-edit-input {
        width: 100%;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    .kv-edit-input:focus {
        border-color: #66afe9;
        outline: none;
    }
    .kv-row-editing {
        background-color: #fffacd;
    }
    .kv-success {
        background-color: #dff0d8 !important;
        transition: background-color 2s;
    }
    .kv-error {
        background-color: #f2dede !important;
    }
    .add-form-container {
        background: #f5f5f5;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
');
?>

<div class="key-value-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="add-form-container">
        <h3>Add New Key-Value Pair</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'add-key-value-form',
            'action' => ['create'],
            'enableAjaxValidation' => false,
            'options' => ['class' => 'form-inline'],
        ]); ?>

        <div class="form-group" style="margin-right: 10px;">
            <?= $form->field($model, 'key')->textInput([
                'placeholder' => 'Enter key',
                'style' => 'width: 300px;',
            ])->label('Key:') ?>
        </div>

        <div class="form-group" style="margin-right: 10px;">
            <?= $form->field($model, 'value')->textInput([
                'placeholder' => 'Enter value',
                'style' => 'width: 400px;',
            ])->label('Value:') ?>
        </div>

        <?= Html::submitButton('Add', ['class' => 'btn btn-success', 'id' => 'add-btn']) ?>

        <?php ActiveForm::end(); ?>

        <div id="add-message" style="margin-top: 10px;"></div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => [
            [
                'attribute' => 'key',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::encode($model->key);
                },
            ],
            [
                'attribute' => 'value',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('div',
                        Html::textInput('value', $model->value, [
                            'class' => 'kv-edit-input',
                            'data-id' => $model->id_key_value,
                            'data-original' => $model->value,
                        ]),
                        ['class' => 'kv-value-container']
                    );
                },
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'headerOptions' => ['style' => 'width: 180px;'],
            ],
            [
                'label' => 'Updated By',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->updater ? Html::encode($model->updater->username) : '';
                },
                'headerOptions' => ['style' => 'width: 150px;'],
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'headerOptions' => ['style' => 'width: 50px;'],
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
                            ['delete', 'id' => $model->id_key_value],
                            ['class' => 'kv-delete-btn']
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>

<?php
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$updateUrl = Url::to(['update']);
$deleteUrl = Url::to(['delete']);
$createUrl = Url::to(['create']);

$js = <<<JS
(function($) {
    var updateTimeout = null;
    var originalValue = null;

    // Handle value input changes with debouncing
    $(document).on('focus', '.kv-edit-input', function() {
        originalValue = $(this).val();
        $(this).closest('tr').addClass('kv-row-editing');
    });

    $(document).on('blur', '.kv-edit-input', function() {
        var input = $(this);
        var newValue = input.val();
        var id = input.data('id');
        var row = input.closest('tr');

        if (originalValue !== newValue) {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(function() {
                updateValue(id, newValue, input, row);
            }, 300);
        } else {
            row.removeClass('kv-row-editing');
        }
    });

    $(document).on('keypress', '.kv-edit-input', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $(this).blur();
        }
    });

    // Update value via Ajax
    function updateValue(id, value, input, row) {
        $.ajax({
            url: '{$updateUrl}?id=' + id,
            type: 'POST',
            data: {
                'KeyValue[id]': id,
                'KeyValue[value]': value,
                '$csrfParam': '$csrfToken'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    input.data('original', value);
                    originalValue = value;
                    row.removeClass('kv-row-editing').addClass('kv-success');
                    
                    // Update the updated_at and updater columns
                    row.find('td:eq(2)').text(response.model.updated_at);
                    row.find('td:eq(3)').text(response.model.updater_username);
                    
                    setTimeout(function() {
                        row.removeClass('kv-success');
                    }, 2000);
                } else {
                    row.addClass('kv-error');
                    alert('Error updating value: ' + (response.message || 'Unknown error'));
                    input.val(input.data('original')); // Revert to original
                    setTimeout(function() {
                        row.removeClass('kv-error kv-row-editing');
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                row.addClass('kv-error');
                var message = 'Error communicating with server';
                
                // Try to parse JSON error response
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    message = 'Permission denied. You do not have access to perform this action.';
                }
                
                alert(message);
                input.val(input.data('original')); // Revert to original
                setTimeout(function() {
                    row.removeClass('kv-error kv-row-editing');
                }, 2000);
            }
        });
    }

    // Handle delete button clicks via Ajax
    $(document).on('click', 'a[href*="delete"]', function(e) {
        e.preventDefault();
        var link = $(this);
        var href = link.attr('href');
        var row = link.closest('tr');
        
        // Extract key name from the row for confirmation
        var keyName = row.find('td:first').text().trim();

        if (confirm('Are you sure you want to delete the key "' + keyName + '"?')) {
            $.ajax({
                url: href,
                type: 'POST',
                data: {
                    '$csrfParam': '$csrfToken'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message || 'Key-value pair deleted successfully.');
                        row.fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        alert('Error: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    var message = 'Error communicating with server';
                    
                    // Try to parse JSON error response
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        message = 'Permission denied. You do not have access to perform this action.';
                    }
                    
                    alert(message);
                }
            });
        }
    });

    // Handle add form submission
    $('#add-key-value-form').on('beforeSubmit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = $('#add-btn');
        var message = $('#add-message');

        btn.prop('disabled', true);
        message.html('');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    message.html('<div class="alert alert-success">' + response.message + '</div>');
                    form[0].reset();
                    
                    // Reload the page to show the new entry
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    var errorMsg = response.message;
                    if (response.errors) {
                        errorMsg += '<ul>';
                        $.each(response.errors, function(field, errors) {
                            $.each(errors, function(index, error) {
                                errorMsg += '<li>' + error + '</li>';
                            });
                        });
                        errorMsg += '</ul>';
                    }
                    message.html('<div class="alert alert-danger">' + errorMsg + '</div>');
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Error communicating with server';
                
                // Try to parse JSON error response
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    errorMsg = 'Permission denied. You do not have access to perform this action.';
                }
                
                message.html('<div class="alert alert-danger">' + errorMsg + '</div>');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });

        return false;
    });
})(jQuery);
JS;

$this->registerJs($js);
?>
