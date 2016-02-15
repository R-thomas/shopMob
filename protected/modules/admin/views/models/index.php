<?php
$this->menu=array(
	array('label'=>'Добавить модель', 'url'=>array('/admin/models/create/category/'.$category.'/brand/'.$brand)),
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
	'dataProvider'=>$model->search($brand),
	'filter'=>$model,
	'columns'=>array(
        array('class' => 'CCheckBoxColumn',
            'id' => 'id'
        ),
		'model_name',
        'old_price',
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
            'viewButtonUrl'=> 'Yii::app()->controller->createUrl("view",array("category"=>'.$category.', "brand"=>'.$brand.',"id"=>$data->primaryKey))',
            'updateButtonUrl'=> 'Yii::app()->controller->createUrl("update",array("category"=>'.$category.', "brand"=>'.$brand.', "id"=>$data->primaryKey))',
            'deleteButtonUrl'=> 'Yii::app()->controller->createUrl("delete",array("category"=>'.$category.', "brand"=>'.$brand.', "id"=>$data->primaryKey))',
		),
	),
)); ?>
