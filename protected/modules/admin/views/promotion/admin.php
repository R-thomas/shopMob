<?php

$this->menu=array(
	array('label'=>'Все акции', 'url'=>array('index')),
	array('label'=>'Добавить акцию', 'url'=>array('create')),
);

?>

<h1>Менеджер акций</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title',
		'text',
        'img'=>array(
            'name'=>'img',
            'type' => 'html',
            'value'=>'CHtml::image("/upload/images/".$data->img, "Нет изображения - загрузите изображение", array("style" => "width:200px"))'
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
