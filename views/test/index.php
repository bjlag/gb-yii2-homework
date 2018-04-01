<?php

/* @var $this yii\web\View */
/* @var $products array */

use yii\helpers\Html;

$this->title = 'Тест';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode( $this->title ) ?></h1>

    <h2>Прямой вывод из модели</h2>

    <?php
    $count = count( $products );

    /** @var \app\models\Product $item */
    foreach ( $products as $key => $item ) {

        echo "ID: {$item->getId()}<br>";
        echo "Имя товара: {$item->getName()}<br>";
        echo "Категория: {$item->getCategory()}<br>";
        echo "Цена: {$item->getPrice()} руб.";

        if ( $count > $key + 1 ) {
            echo "<hr>";
        }
    }?>

    <h2>Вывод с помощью виджета DetailView</h2>
    <?php
    /** @var \app\models\Product $item */
    foreach ( $products as $item ) {
        echo \yii\widgets\DetailView::widget( [
            'model' => $item->getProps(),
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
            ] ] );
    } ?>

</div>
