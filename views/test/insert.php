<?php

/* @var $this yii\web\View */
/* @var $component string */
/* @var $data string */

use yii\helpers\Html;

$this->title = 'Test insert';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode( $this->title ) ?></h1>

    <?= $data ?>
</div>
