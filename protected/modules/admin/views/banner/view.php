<?php

$this->menu=array(
	array('label'=>'Добавить баннер', 'url'=>array('create')),
	array('label'=>'Редактировать баннер', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить баннер', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Менеджер баннеров', 'url'=>array('index')),
);
?>

<h1>Баннер</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'img' => array(
            'name'=>'img',
            'type'=>'raw',
            'value'=>CHtml::image("/upload/images/" . $model->img, 
                                  "Нет изображения - загрузите изображение", 
                                  array("style" => "width:600px")),
        ),
	),
)); ?>
