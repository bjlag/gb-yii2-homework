<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProviderAccesses yii\data\ActiveDataProvider */
?>

<h2>Пользователи с доступом к заметке</h2>

<?= GridView::widget( [
    'dataProvider' => $dataProviderAccesses,
    'columns' => [
        [
            'attribute' => 'user.username',
            'label' => 'Логин'
        ],
        [
            'attribute' => 'user.name',
            'label' => 'Имя'
        ],
        [
            'attribute' => 'user.surname',
            'label' => 'Фамилия'
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{unshared}',
            'buttons' => [
                'unshared' => function ( $url, \app\models\Access $model, $key ) {
                    $icon = \yii\bootstrap\Html::icon( 'remove' );
                    $options = [
                        'title' => 'Удалить доступ',
                        'aria-label' => 'Удалить доступ',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить доступ к заметке?',
                            'method' => 'post',
                        ],
                    ];

                    return Html::a( $icon, [ 'access/unshared', 'accessId' => $model->id ], $options );
                }
            ]
        ],
    ],
] ); ?>
