<?php

$this->menu=array(
	array('label'=>'Все новости', 'url'=>array('index')),
	array('label'=>'Добавить новость', 'url'=>array('create')),
	array('label'=>'Редактировать новость', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить новость', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Менеджер новостей', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->title; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'date' => array(
            'name' => 'date',
            'value' => date("j.m.Y", $model->date)." в ".date(" H:i", $model->date),
        ),
		'text',
        'img' => array(
            'name'=>'img',
            'type'=>'raw',
            'value'=>CHtml::image("/upload/images/" . $model->img, 
                                  "Нет изображения - загрузите изображение", 
                                  array("style" => "width:600px")))
	),
)); ?>
