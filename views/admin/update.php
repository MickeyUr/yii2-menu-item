<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\modules\menuItem\models\MenuItem */

$this->title = Yii::t('MenuItem', 'Update {modelClass}: ', [
    'modelClass' => 'Menu Item',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('MenuItem', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('MenuItem', 'Update');
?>
<div class="menu-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
