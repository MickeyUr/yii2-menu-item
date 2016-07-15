<div class="row">
    <div class="span6">
        <?php if ($model->getChildrenCount()==false): ?>
        Это меню пустое
        <?php else: ?>

        <?= execut\widget\TreeView::widget([
            'data' =>  $model->createTree(),
            'size' => execut\widget\TreeView::SIZE_SMALL,
            'header' => $model->name,
            'searchOptions' => [
                'inputOptions' => [
                    'placeholder' => 'Search...'
                ],
            ],
            'clientOptions' => [
                'enableLinks' => true,
//                'onNodeSelected' => $onSelect,
//                'selectedBackColor' => 'rgb(40, 153, 57)',
                'borderColor' => '#fff',
            ],
        ]);?>
        <?php endif;?>
    </div>

    <div class="pull-right">
        <?= \yii\helpers\Html::a(Yii::t('MenuItem', 'Добавить'), ['create', 'root_id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= \backend\helpers\Html::a(Yii::t('MenuItem', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= \backend\helpers\Html::a(Yii::t('MenuItem', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('MenuItem', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>
