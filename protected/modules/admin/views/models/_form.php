<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'models-form',
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'model_name'); ?>
		<?php echo $form->textField($model,'model_name',array('size'=>60,'maxlength'=>255, 'autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'model_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price', array('autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity', array('autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'quantity'); ?>
	</div>
    
    <div class="row">
		<?php echo $form->labelEx($model,'photo'); ?>
		<?php echo $form->fileField($model,'photo'); ?>
		<?php echo $form->error($model,'photo'); ?>
	</div>
    
    <div class="row">

		<?php echo $form->labelEx($model,'photo_other'); ?>
		<?php echo $form->fileField($model,'photo_other', array('multiple'=>'multiple', 'name' => 'photo_other[]', 'value'=>'')); ?>
		<?php echo $form->error($model,'photo_other'); ?>
        <?php
                
                /*
                $this->widget('CMultiFileUpload', array(
                'model'=>$model,
                'name' => 'photo_other',
                'attribute' => 'attachments',
                'max'=>10,
                'accept' => 'jpeg|jpg|gif|png', // useful for verifying files
                'duplicate' => 'Already Selected', // useful, i think
                'denied' => 'Invalid file type', // useful, i think
                'remove'=>'[x]',
                'htmlOptions'=>[
                    'multiple'=>"multiple",
                ]
                ));	
                */
        ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->