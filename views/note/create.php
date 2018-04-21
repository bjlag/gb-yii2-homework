<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Note */

$this->title = 'Создать заметку';
$this->params['breadcrumbs'][] = ['label' => 'Мои заметки', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="note-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
