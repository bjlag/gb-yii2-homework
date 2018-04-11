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

    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionInsert()
    {
        for ( $i = 0; $i < 3; $i++ ) {
            $values = [
                'username' => "login_{$i}",
                'name' => 'Иван',
                'surname' => 'Иванов',
                'password_hash' => uniqid(),
                'access_token' => uniqid(),
                'auth_key' => uniqid(),
                'created_at' => strtotime( '09-04-2018' ),
                'updated_at' => strtotime( '11-04-2018' )
            ];

            \Yii::$app->db->createCommand()->insert( 'user', $values )->execute();
        }

        return $this->render( 'insert', [
            'data' => 'Вставка данных в таблицу User'
        ] );
    }
}