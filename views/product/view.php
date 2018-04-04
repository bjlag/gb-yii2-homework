<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => "<b>{$model->name}</b>"
            ],
            [
                'attribute' => 'price',
                'format' => ( $model->price > 0 ? 'currency' : 'html' ),
                'value' => ( $model->price > 0 ? $model->price : '<span class="text-danger">не указана</span>' )
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime'
            ],
        ],
    ]) ?>

</div>
