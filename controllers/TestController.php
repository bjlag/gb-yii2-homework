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

        return $this->render( 'index', [
            'component' => $component
        ] );
    }
}