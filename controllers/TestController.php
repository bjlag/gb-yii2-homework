<?php

namespace app\controllers;

use app\models\Product;
use yii\web\Controller;

class TestController extends Controller
{
    /**
     * Вывод главной страницы раздела.
     * @return string
     */
    public function actionIndex()
    {
        $productData = [
            'id' => 1,
            'name' => 'Шапка ушанка',
            'category' => 'Головные уборы',
            'price' => 12000,
        ];
        $product = new Product( $productData );

        return $this->render( 'index', [ 'product' => $product ] );
    }
}