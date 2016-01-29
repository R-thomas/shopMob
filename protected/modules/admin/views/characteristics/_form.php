
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'characteristics-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля со звездочкой (<span class="required">*</span>) обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'characteristic_name'); ?>
		<?php echo $form->textField($model,'characteristic_name',array('size'=>60,'maxlength'=>255, 'autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'characteristic_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model, 'parent_id', $list); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'filter'); ?>
		<?php echo $form->dropDownList($model,'filter', array(0=>'Не участвует', 1=>'Участвует')); ?>
		<?php echo $form->error($model,'filter'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'unit'); ?>
		<?php echo $form->textField($model,'unit',array('size'=>60,'maxlength'=>255, 'autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'unit'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

