<?php //use kartik\sortinput\SortableInput; ?>
<?php //foreach ($menuItems as $key=>$menuItem):
//    $items[] = ['content' => "<i class='glyphicon glyphicon-cog'></i> $menuItem->name(<span>$menuItem->url</span>)"];
// endforeach; ?>
<?php //echo SortableInput::widget([
//    'name'=> 'MenuItem_'.$menuItem->id,
//    'items' => $items,
//    'hideInput' => true,
//]);?>

<table id="sortable-menu-item" class="table table-striped">
    <colgroup>
        <col width="18"/>
        <col/>
        <col/>
    </colgroup>
    <?php foreach ($menuItems as $menuItem): ?>
        <tr id="MenuItem_id-<?php echo $menuItem->id; ?>" <?php if($model->id==$menuItem->id) echo 'class="current-row"'; ?>>
            <td><i class="glyphicon glyphicon-resize-vertical" style="cursor: row-resize;"></i></td>
            <td><?php echo $menuItem->name; ?></td>
            <td><?php echo $menuItem->url; ?></td>
        </tr>
    <?php endforeach; ?>

    <?php if (count($menuItems)==0): ?>
        <tr>
            <td colspan="3">Невозможно настроить порядок</td>
        </tr>
    <?php endif; ?>
</table>

<div class="row">
    <div class="span2">
        <a class="btn btn-success" id="save-menu-item" href="#">Сохранить порядок</a>
    </div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    $('#save-menu-item').on('click', function () {
        $.post("menu-item/save-order", $('#sortable-menu-item tbody').sortable('serialize'), function () {
            $.jGrowl('Порядок сохранен', { theme: 'success' });
//            displayMessage('Порядок сохранен', 'success');
        });
    });
    $('#sortable-menu-item tbody').sortable({});
});

JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>
