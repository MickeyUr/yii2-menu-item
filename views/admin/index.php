<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $searchModel \common\modules\menuItem\models\search\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('MenuItem', 'Menu Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('MenuItem', 'Create Menu Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?/*= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'uri',
            'parent_id',
            'level',
            // 'position',
            // 'created_at',
            // 'created_by',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>
    <?php
    $tabs = array();
    foreach ($menuItems as $menuItem) {
        $tabs[] = array(
            'label' => $menuItem->name,
            'content' => $this->render('_view', ['model' => $menuItem]),
        );
    }
    ?>
    <?php echo Tabs::widget(['items' => $tabs]); ?>
</div>
