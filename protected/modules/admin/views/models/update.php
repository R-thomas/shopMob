<?php


$this->menu=array(
	array('label'=>'Карточка товара', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление моделями', 'url'=>array('/admin/models/'.$model->brandModel->id)),
);
?>

<h1>Редактирование модели "<?php echo $model->brandModel->brand.' <b>'.$model->model_name.'</b>'; ?>"</h1>

<?php $this->renderPartial('_form_update', array('model'=>$model, 'modelChar'=>$modelChar)); ?>