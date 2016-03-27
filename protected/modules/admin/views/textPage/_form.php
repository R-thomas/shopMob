
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'text-page-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php $this->widget('application.extensions.ckeditor.CKEditor', 
                array( 'model'=>$model, 
                    'attribute'=>'text', 
                    'language'=>'ru', 
                    'editorTemplate'=>'full', 
                )); 
        ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->