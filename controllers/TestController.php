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
//        \Yii::$app->test->setProp( 'new value' );
        $component = \Yii::$app->test->getProp();

        $productsData = [
            [
                'id' => 1,
                'name' => 'Шапка ушанка',
                'category' => 'Головные уборы',
                'price' => 12000
            ],
            [
                'id' => 2,
                'name' => 'Кепка',
                'category' => 'Головные уборы',
                'price' => 1500
            ],
            [
                'id' => 3,
                'name' => 'Кроссовки',
                'category' => 'Обувь',
                'price' => 10500
            ]
        ];

        $products = [];
        foreach ( $productsData as $data ) {
            $products[] = new Product( $data );
        }

        return $this->render( 'index', [
            'component' => $component,
            'products' => $products
        ] );
    }
}