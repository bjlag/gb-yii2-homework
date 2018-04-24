<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои заметки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Создать заметку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'text:ntext',
            [
                'attribute' => 'created_at',
                'format' => 'datetime'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {share} {delete}',
                'buttons' => [
                    'share' => function ( $url, \app\models\Note $model, $key ) {
                        $icon = \yii\bootstrap\Html::icon( 'share' );
                        $options = [
                            'title' => 'Дать доступ',
                            'aria-label' => 'Дать доступ',
                            'data-pjax' => '0',
                        ];
                        return Html::a( $icon, [ 'access/create', 'noteId' => $model->id ], $options );
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
