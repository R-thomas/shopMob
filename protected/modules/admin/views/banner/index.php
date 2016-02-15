<?php

$this->menu=array(
	array('label'=>'Добавить баннер', 'url'=>array('create')),
);

?>

<h1>Manage Banners</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'banner-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        'img'=>array(
            'name'=>'img',
            'type' => 'html',
            'value'=>'CHtml::image("/upload/images/".$data->img, "Нет изображения - загрузите изображение", array("style" => "width:600px"))'
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
