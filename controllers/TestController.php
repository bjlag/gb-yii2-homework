<?php

namespace app\controllers;

use yii\db\Query;
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
        // Добавление данных в таблицу user с помощью insert()
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

        // Пакетное добавление данных в таблицу note с помощью batchInsert()
        \Yii::$app->db->createCommand()->batchInsert( 'note', [ 'text', 'creator_id', 'created_at' ], [
            [ 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 1, strtotime( '10-04-2018' ) ],
            [ 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 1, strtotime( '11-04-2018' ) ],
            [ 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 1, strtotime( '12-04-2018' ) ],
        ] )->execute();

        return $this->render( 'insert', [
            'data' => 'Добавление данных'
        ] );
    }

    public function actionSelect()
    {
        // Выборка данных из таблицы user
        $query = 'SELECT * FROM [[user]] WHERE {{id}} = :id';

        $result[ 'result_1' ] = \Yii::$app->db->createCommand( $query, [ 'id' => 1 ] )->queryAll();
        $result[ 'result_2' ] = ( new Query() )->from( 'user' )->where( 'id > 1' )->orderBy( [ 'name' => SORT_ASC ] )->all();
        $result[ 'result_3' ] = ( new Query() )->from( 'user' )->count();

        // Выборка данных из note
        $result[ 'result_4' ] = ( new Query() )->from( 'note n' )->innerJoin( 'user u', 'n.creator_id = u.id' )->all();

        _end( $result );

        return $this->render( 'select', [
            'result' => $result
        ] );
    }
}