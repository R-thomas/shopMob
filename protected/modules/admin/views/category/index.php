<?php

$this->menu=array(
	array('label'=>'Создать категорию', 'url'=>array('create')),
);
?>

<h1>Менеджер категорий</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'category_name'=>array(
            'name'=>'category_name',
            'type'=>'raw',
            'value'=>'CHtml::link($data->category_name, "/admin/characteristics/".$data->id)'
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
