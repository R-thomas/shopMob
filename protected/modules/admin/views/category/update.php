<?php

$this->menu=array(
	array('label'=>'Менеджер категорий', 'url'=>array('index')),
	array('label'=>'Создать категорию', 'url'=>array('create')),
);
?>

<h1>Изменить категорию "<?php echo $model->category_name; ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>