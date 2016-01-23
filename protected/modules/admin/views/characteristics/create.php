<?php
/* @var $this CharacteristicsController */
/* @var $model Characteristics */

$this->breadcrumbs=array(
	'Characteristics'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Characteristics', 'url'=>array('index')),
	array('label'=>'Manage Characteristics', 'url'=>array('admin')),
);
?>

<h1>Create Characteristics</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>