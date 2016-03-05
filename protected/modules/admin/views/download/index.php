<?php if(Yii::app()->user->hasFlash('status')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('status'); ?>
</div>

<?php endif; ?>

<?php
	Yii::app()->clientScript->registerScript('search', "
        $('.import-button').click(function(){
        $('#import-form').toggle(200);
        return false;
        });
        
        $('.export-button').click(function(){
        $('.search-form').toggle(200);
        return false;
        });
    ");
?>
<h1>Массовая загрузка-выгрузка товаров</h1>
<p>Выберите категорию</p>
<?php if($id): ?>
<?php echo CHtml::link('Выгрузить товары','#',array('class'=>'export-button')); ?>

<div class="search-form" style="display:none; background-color: #EFFDFF;">
    <p>Чтобы сохранить товары этой категории в файл csv, нажмите кнопку "Сохранить" </p>
    <?php echo CHtml::form(); ?>
    <?php echo CHtml::submitButton('Сохранить'); ?>
    <?php echo CHtml::endForm(); ?>
    
</div>
<br /><br />
<hr />
<?php echo CHtml::link('Загрузить файл с товарами','#',array('class'=>'import-button')); ?>
<br /><br />
<div id="import-form" style="display:none; background-color: #EFFDFF;">
    <?php echo CHtml::form('', '', array('enctype'=>'multipart/form-data')); ?>
    <p>1. Выберите csv-файл. Если формат файла не сsv, он не будет загружен </p>
    <?php echo CHtml::activeFileField($models, 'csv'); ?>
    <br /><br />
    <p>2. Нажмите кнопку "Импортировать"</p>
    
    <?php echo CHtml::submitButton('Импортировать'); ?>
    <?php echo CHtml::endForm(); ?>
</div>
<?php endif; ?>