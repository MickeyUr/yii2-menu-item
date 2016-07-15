<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
/* @var $model \common\modules\menuItem\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('basic'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uri')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->widget('common\widgets\McDropdown' , array(
        'data' => \mickey\menuItem\models\MenuItem::find()->where(['parent_id'=>null])->with('children')->all(),
//        'without' => $model->id,
        'options' => array(
            'allowParentSelect' => true,
            'select' => "js:function(id){
                $('#tab2').html('Сначала нужно сохранить пункт меню');
            }"
        ),
        'options'=>['class'=>'span5']
    ))?>

    <?php $this->endBlock(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('MenuItem', 'Create') : Yii::t('MenuItem', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?php if(!$model->isNewRecord){
                echo Html::a(Yii::t('MenuItem', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('MenuItem', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]);
            echo Html::a(Yii::t('MenuItem', 'Create'), ['create', 'root_id' => $model->parent_id], [
                'class' => 'btn btn-success',
            ]);
            }
            ?>
        <?=Html::a(Yii::t('MenuItem', 'Каталог'), ['index'], [
            'class' => 'btn btn-info',
        ]);?>
    </div>

    <?php echo Tabs::widget([
        'items' => [
            [
                'label' => 'Основные',
                'content' => $this->blocks['basic'],
                'active' => true
            ],
            [
            'label' => 'Настроить порядок',
            'content'=>$this->render('_position', ['model' => $model, 'menuItems' => $model->neighbors]),
            'visible' => !$model->isNewRecord && (bool)$model->neighbors,
                'options'=>['id'=>'tab2']
            ],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
