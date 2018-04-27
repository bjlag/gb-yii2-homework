<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProviderUsers yii\data\ActiveDataProvider */
//\yii\bootstrap\Html::
?>

<h2>Пользователи с доступом к заметке</h2>

<?= GridView::widget( [
    'dataProvider' => $dataProviderUsers,
    'columns' => [
        [
            'attribute' => 'username',
            'label' => 'Логин'
        ],
        [
            'attribute' => 'name',
            'label' => 'Имя'
        ],
        [
            'attribute' => 'surname',
            'label' => 'Фамилия'
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {unshared}',
            'buttons' => [
                'unshared' => function ( $url, \app\models\User $model, $key ) {
                    $icon = \yii\bootstrap\Html::icon( 'remove' );
                    $options = [
                        'title' => 'Удалить доступ',
                        'aria-label' => 'Удалить доступ',
                        'data-pjax' => '0',
                    ];
                    return Html::a( $icon, [ 'access/unshared', 'userId' => $model->id ], $options );
                }
            ]
        ],
    ],
] ); ?>
