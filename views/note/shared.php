<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Расшаренные заметки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'text:ntext',
            [
                'attribute' => 'created_at',
                'format' => 'datetime'
            ],

            [
                'header' => 'Кому',
                'value' => function ( \app\models\Note $model ) {
                    return join( ', ', $model->getAccessUsers()->select( 'user.username' )->column() );
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {unshared}',
                'buttons' => [
                    'unshared' => function ( $url, \app\models\Note $model, $key ) {
                        $options = [
                            'title' => 'Удалить доступ для всех пользователей',
                            'aria-label' => 'Удалить доступ для всех пользователей',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить доступ к заметке для всех пользователей?',
                                'method' => 'post',
                            ]
                        ];
                        $icon = \yii\bootstrap\Html::icon( 'remove' );
                        return Html::a( $icon, [ 'access/unshared-all', 'noteId' => $model->id ], $options );
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
