<?php

namespace common\models;

/**
 * This is the model class for table "fnord".
 *
 * @property int $id_fnord
 * @property string|null $bar
 * @property string|null $baz
 *
 * @property Foo[] $foos
 */
class Fnord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'fnord';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bar', 'baz'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id_fnord' => 'ID Fnord',
            'bar' => 'Bar',
            'baz' => 'Baz',
        ];
    }

    /**
     * Gets query for [[Foos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFoos(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Foo::class, ['id_fnord' => 'id_fnord']);
    }
}
