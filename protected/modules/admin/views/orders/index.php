<h1>Менеджер заказов</h1>

<?php
	echo CHtml::form();
    echo 'Отметьте статус заказа<br/>';
    echo CHtml::dropDownList('drop_status', '', array(0=>'Новый', 1=>'В обработке', 2=>'Отправлен', 3=>'Доставлен', 4=>'Отменен', 5=>'Отменен навсегда')).' ';
    echo CHtml::submitButton('Изменить статус', array('name' => 'change'));
    
?>

<?php 
        
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'orders-grid',
    'selectableRows'=>2,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'cssFile' => Yii::app()->request->baseUrl . '/css/gridview.css',
	'columns'=>array(
        array(
            'class' => 'CCheckBoxColumn',
            'id' => 'id'
        ),
        'id'=>array(
            'name'=>'id',
            'htmlOptions' => array('class'=>'number')
            ),
        array(
            'name'=>'status',
            'htmlOptions' => array('class'=>'number'),
            'value'=>'($data->status == 0?"Новый":
                      ($data->status == 1?"В обработке":
                      ($data->status == 2?"Отправлен":
                      ($data->status == 3?"Доставлен":
                      ($data->status == 4?"Отменен":"Отменен навсегда")))))',
            'filter'=> array(0=>'Новый', 1=>'В обработке', 2=>'Отправлен', 3=>'Доставлен', 4=>'Отменен', 5=>'Отменен навсегда')          
        ),    
		'name',
		'tel',
		'email',
		'model_id'=>array(
            'name'=>'model_id',
            'type' => 'html',
            'value' => 'implode("<br/><br/>", json_decode($data->model_id))',
            'htmlOptions' => array('class'=>'bold')
            ),
		'quantity'=>array(
            'name'=>'quantity',
            'type' => 'html',
            'value' => 'implode("<br/><br/>", json_decode($data->quantity))',
            'htmlOptions' => array('class'=>'bold quantity')
            ),
		'sum'=>array(
            'name'=>'sum',
            'type' => 'html',
            'value' => 'implode("<br/><br/>", json_decode($data->sum))',
            'htmlOptions' => array('class'=>'bold quantity')
            ),
		'total'=>array(
            'name'=>'total',
            'htmlOptions' => array('class'=>'quantity')
            ),
		array(
			'class'=>'CButtonColumn',
            'viewButtonOptions' => array('style' => 'display:none'),
            'updateButtonOptions' => array('style' => 'display:none'),
		),  
	),
)); 

echo CHtml::endForm();

?>
