<?php
$this->menu=array(
	array('label'=>'Добавить модель', 'url'=>array('/admin/models/create/id/'.$id)),
);

?>

<h1>Управление моделями</h1>

<?php
	echo CHtml::form();
    echo '(Отметьте поля, к которым хотите добавить стикер)<br/>';
    echo CHtml::submitButton('Топ продаж', array('name' => 'top'));
    echo CHtml::submitButton('Акция', array('name' => 'promotion'));
    echo CHtml::submitButton('Новинка', array('name' => 'novelty'));
    echo CHtml::submitButton('Лучшая цена', array('name' => 'bestPrice'));
    
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'models-grid',
    'selectableRows'=>2,
	'dataProvider'=>$model->search($id),
	'filter'=>$model,
	'columns'=>array(
        array('class' => 'CCheckBoxColumn',
            'id' => 'id'
        ),
		'model_name',
		'price',
        'quantity',
        'photo'=>array(
            'name'=>'photo',
            'type' => 'html',
            'value'=>'CHtml::image("/upload/images/".$data->photo, "Нет изображения - загрузите изображение", array("style" => "width:100px"))
            .($data->top == 1?"<br/>Топ продаж":"")
            .($data->promotion == 1?"<br/>Акция":"")
            .($data->novelty == 1?"<br/>Новинка":"")
            .($data->bestPrice == 1?"<br/>Лучшая цена":"")'
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
