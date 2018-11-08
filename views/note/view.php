<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Note */
/* @var $dataProviderAccesses yii\data\ActiveDataProvider */

$this->title = 'Заметка: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заметки', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Дать доступ', [ 'access/create', 'noteId' => $model->id ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту заметку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'text:text',
            [
                'attribute' => 'created_at',
                'format' => 'datetime'
            ]
        ],
    ]) ?>

    <?php
    if ( $model->isCreator( Yii::$app->user->getId() ) ): ?>

        <?= $this->render( '_accessed', [
            'dataProviderAccesses' => $dataProviderAccesses,
        ] ) ?>

    <?php
    endif; ?>

</div>
