<?php

namespace common\models;

/**
 * This is the model class for table "foo".
 *
 * @property int $id_foo
 * @property int $id_fnord
 * @property int|null $foo_value
 *
 * @property Fnord $fnord
 */
class Foo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'foo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id_fnord'], 'required'],
            [['id_fnord', 'foo_value'], 'integer'],
            [['id_fnord'], 'exist', 'skipOnError' => true, 'targetClass' => Fnord::class, 'targetAttribute' => ['id_fnord' => 'id_fnord']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id_foo' => 'ID Foo',
            'id_fnord' => 'Fnord',
            'foo_value' => 'Foo Value',
        ];
    }

    /**
     * Gets query for [[Fnord]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFnord(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Fnord::class, ['id_fnord' => 'id_fnord']);
    }
}
