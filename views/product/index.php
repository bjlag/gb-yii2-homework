<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function( $model ) {
                    /** @var \app\models\Product $model */
                    return Html::a( $model->name, Url::to( [ 'product/view', 'id' => $model->id ] ) );
                }
            ],
            [
                'attribute' => 'price',
                'format' => 'html',
                'value' => function( $model ) {
                    /** @var \app\models\Product $model */
                    return ( $model->price > 0
                        ? Yii::$app->formatter->asCurrency( $model->price )
                        : '<span class="text-danger">не указана</span>' );
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'contentOptions' => [ 'class' => 'small' ]
            ],

            ['class' => \yii\grid\ActionColumn::class],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
