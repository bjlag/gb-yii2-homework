<?php

namespace app\models;

use yii\base\Model;

/**
 * Class Product
 * @package app\models
 */
class Product extends Model
{
    private $id;
    private $name;
    private $category;
    private $price;

    /**
     * Product constructor.
     * @param array $product
     * @param array $config
     */
    public function __construct( array $product, array $config = [] )
    {
        parent::__construct( $config );

        $this->id = $product[ 'id' ];
        $this->name = $product[ 'name' ];
        $this->category = $product[ 'category' ];
        $this->price = $product[ 'price' ];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Получить все свойства в виде ассоциативного массива.
     * @return array
     */
    public function getProps()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'price' => "{$this->price} руб."
        ];
    }
}
