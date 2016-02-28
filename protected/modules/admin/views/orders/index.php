
<h1>Менеджер заказов</h1>


<?php 
        
    $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'orders-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'cssFile' => Yii::app()->theme->baseUrl . '/css/gridview.css',
	'columns'=>array(
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
)); ?>
