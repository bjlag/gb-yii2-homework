<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $price
 * @property int $created_at
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'created_at'], 'required'],

            [['name'], 'string', 'max' => 20 ],
            [['name'], 'trim' ],
            [['name'], 'filter', 'filter' => function( $value ) {
                return strip_tags( $value );
            } ],

            [['price'], 'integer', 'min' => 0, 'max' => 1000],

            [['created_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'price' => 'Цена',
            'created_at' => 'Дата создания',
        ];
    }
}
