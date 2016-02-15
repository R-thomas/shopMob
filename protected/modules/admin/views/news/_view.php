
<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br /><br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode(date("j.m.Y" ,$data->date).' в '.date("H:i", $data->date)); ?>
	<br /><br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CHtml::encode($data->text); ?>
	<br /><br />
    
	<?php echo CHtml::image("/upload/images/".$data->img, "Нет изображения - загрузите изображение", array("style" => "max-width:400px; max-height:300px")); ?>
	<br />


</div>