<?php

$this->menu=array(
	array('label'=>'Редактировать модель', 'url'=>array('update', 'category'=>$category, 'brand'=>$brand, 'id'=>$model->id)),
	array('label'=>'Удалить модель', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete', 'category'=>$category, 'brand'=>$brand, 'id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить эту модель?')),
	array('label'=>'Управление моделями', 'url'=>array('/admin/models/index/category/'.$category.'/brand/'.$brand)),
);
?>

<h1>Карточка товара "<?php echo $model->brandModel->brand.' '.$model->model_name; ?>"</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
        'brand' =>array(
            'name'=>'brand_id',
            'value'=>$model->brandModel->brand,
        ),
		'model_name',
		'price',
		'quantity',
        'photo' => array(
            'name'=>'photo',
            'type'=>'raw',
            'value'=>CHtml::image("/upload/images/" . $model->photo, 
                                  "Нет изображения - загрузите изображение", 
                                  array("style" => "width:250px")),
        ),
        'photo_other' => array(
            'name'=>'photo_other',
            'type'=>'raw',
            'value'=>Models::images($model->id),
        ),
        array(
            'name'=>'Charact',
            'type'=>'raw',
            'value'=>Models::charView($category, $id)
        )
	),
)); ?>
