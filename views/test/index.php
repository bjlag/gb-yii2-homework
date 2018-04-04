<?php

/* @var $this yii\web\View */
/* @var $component string */

use yii\helpers\Html;

$this->title = 'Тест';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode( $this->title ) ?></h1>

    <h2>Данные компонента Test</h2>
    <p>
        <?= $component ?>
    </p>
</div>
