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
            // todo: вывести информацию кому предоставлен доступ
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {unshared}',
                'buttons' => [
                    'unshared' => function ( $url, $model, $key ) {
                        /** @var $model \app\controllers\NoteController */
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
