<?php

$this->menu=array(
	array('label'=>'Удалить характеристику', 'url'=>array('delete', 'id'=>$model->id, 'idk'=>$idk), 'linkOptions' => array('class'=>'del_char')),
	array('label'=>'Все характеристкики', 'url'=>array('/admin/characteristics', 'id'=>$idk)),
);
?>

<?php
    if($model->parent_id == 0)
    {
    	Yii::app()->clientScript->registerScript('', "
            $(document).ready(function(){
        	    $('.del_char').on('click', function(){
        	       if(!confirm('Внимание! Если Вы удалите родительскую характеристику, то удалятся все подхарактеристики? Вы уверены, что хотите удалить характеристику?')) return false;
        	    })
            });
        ");
    }
    else
    {
        Yii::app()->clientScript->registerScript('', "
            $(document).ready(function(){
        	    $('.del_char').on('click', function(){
        	       if(!confirm('Вы уверены?')) return false;
        	    })
            });
        ");
    }
?>
<h1>Редактирование характеристики "<?php echo $model->characteristic_name; ?>"</h1>

<?php $this->renderPartial('_form_update', array('model'=>$model,
                                          'models'=>$models,
                                          'list'=>$list,)); ?>