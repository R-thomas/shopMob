<h1>Характеристики</h1>
<div class="left_block">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Characteristics-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля со звездочкой (<span class="required">*</span>) обязательны к заполнению.</p>

	<?php echo $form->errorSummary($models); ?>

	<div class="row">
		<?php echo $form->labelEx($models,'characteristic_name'); ?>
		<?php echo $form->textField($models,'characteristic_name',array('size'=>60,'maxlength'=>255, 'autocomplete'=>'off')); ?>
		<?php echo $form->error($models,'characteristic_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($models,'parent_id'); ?>
		<?php echo $form->dropDownList($models, 'parent_id', $list); ?>
		<?php echo $form->error($models,'parent_id'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($models,'filter'); ?>
		<?php echo $form->dropDownList($models,'filter', array(0=>'Не участвует', 1=>'Участвует')); ?>
		<?php echo $form->error($models,'filter'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($models,'unit'); ?>
		<?php echo $form->textField($models,'unit',array('size'=>60,'maxlength'=>255, 'autocomplete'=>'off')); ?>
		<?php echo $form->error($models,'unit'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
<div class="right_block">
<h2>Список характеристик</h2>
<?php

	foreach($model as $i=>$item)
    {
        if($item->parent_id == 0)
        {
            echo '<b>'.CHtml::link($item->characteristic_name, '/admin/characteristics/update/id/'.$item->id.'/idk/'.$idk, array('class'=>'characteristic')).'</b><br/>';
            foreach($model as $items)
            {
                if ($items->parent_id == $item->id)
                echo '&nbsp;&nbsp;&nbsp;'.CHtml::link($items->characteristic_name, '/admin/characteristics/update/id/'.$items->id.'/idk/'.$idk, array('class'=>'characteristic')).($items->unit?',':'').'&nbsp;&nbsp;<i>'.$items->unit.'</i><br/>';
            }
        }    
    }
?>

</div>