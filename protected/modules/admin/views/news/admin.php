<?php

$this->menu=array(
	array('label'=>'Все новости', 'url'=>array('index')),
	array('label'=>'Добавить новость', 'url'=>array('create')),
);

?>

<h1>Менеджер новостей</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'title',
		'date' => array(
            'name' => 'date',
            'value' => 'date("j.m.Y", $data->date)." в ".date(" H:i", $data->date)',
        ),
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
