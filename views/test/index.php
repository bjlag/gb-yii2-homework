<?php

/* @var $this yii\web\View */
/* @var $product \app\models\Product */

use yii\helpers\Html;

$this->title = 'Тест';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode( $this->title ) ?></h1>

    <h2>Прямой вывод из модели</h2>
    ID: <?= $product->getId() ?><br>
    Имя товара: <?= $product->getName() ?><br>
    Категория: <?= $product->getCategory() ?><br>
    Цена: <?= $product->getPrice() ?> руб.

    <h2>Вывод с помощью виджета DetailView</h2>
    <?= \yii\widgets\DetailView::widget( [
        'model' => $product->getProps(),
        'attributes' => [
            [
                'attribute' => 'id',
                'label' => 'ID'
            ],
            [
                'attribute' => 'name',
                'label' => 'Имя товара'
            ],
            [
                'attribute' => 'category',
                'label' => 'Категория'
            ],
            [
                'attribute' => 'price',
                'label' => 'Цена'
            ]
        ] ] ) ?>
</div>
