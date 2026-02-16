<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;

/**
 * KeyValue model
 *
 * @property int $id_key_value
 * @property string $key
 * @property string $value
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BackofficeUser $creator
 * @property BackofficeUser $updater
 */
class KeyValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%key_value}}';
    }

    /**
     * Retrieves a value by its key
     *
     * @param string $key The key to search for
     * @param string|null $default The default value to return if key is not found
     * @return string|null The value associated with the key, or default if not found
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        $model = static::findOne(['key' => $key]);
        return $model !== null ? $model->value : $default;
    }

    /**
     * Sets a value for a key (creates or updates)
     *
     * @param string $key The key to set
     * @param string $value The value to set
     * @return bool True if successful, false otherwise
     * @throws Exception
     */
    public static function setValue(string $key, string $value): bool
    {
        $model = static::findOne(['key' => $key]);

        if ($model === null) {
            $model = new static();
            $model->key = $key;
        }

        $model->value = $value;
        return $model->save();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['key', 'value'], 'required'],
            ['key', 'string', 'max' => 250],
            ['key', 'unique'],
            ['value', 'string', 'max' => 2500]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id_key_value' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets the creator relation
     *
     * @return ActiveQuery
     */
    public function getCreator(): ActiveQuery
    {
        return $this->hasOne(BackofficeUser::class, ['id_backoffice_user' => 'created_by']);
    }

    /**
     * Gets the updater relation
     *
     * @return ActiveQuery
     */
    public function getUpdater(): ActiveQuery
    {
        return $this->hasOne(BackofficeUser::class, ['id_backoffice_user' => 'updated_by']);
    }
}
