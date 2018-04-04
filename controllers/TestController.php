<?php

namespace app\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    /**
     * Вывод главной страницы раздела.
     * @return string
     */
    public function actionIndex()
    {
        $component = \Yii::$app->test->getProp();

        return $this->render( 'index', [
            'component' => $component
        ] );
    }
}