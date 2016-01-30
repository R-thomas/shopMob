<h1>Бренды</h1>

<?php if(Yii::app()->user->hasFlash('status')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('status'); ?>
</div>

<?php endif; ?>

<?php
    Yii::app()->clientScript->registerScript('search', "
        $('.search-button').click(function(){
        $('.search-form').toggle(200);
        return false;
    });
    ");
    
    echo CHtml::link('Добавить бренд','#',array('class'=>'search-button'));
?>

<div class="search-form" style="display:none">
    <?php echo CHtml::form('', '', array('enctype'=>'multipart/form-data')); ?>
    <?php echo CHtml::activeTextField($modelBrand, 'brand'); ?>
    <?php echo CHtml::activeFileField($modelBrand, 'img'); ?>
    <?php echo CHtml::submitButton('Добавить бренд'); ?>
    
</div>
<br />
<br />

<?php
    foreach ($model as $brands)
    {
        echo '<div class = "admin_p">'.CHtml::link(CHtml::image("/upload/images/" . $brands->brandis->img, $brands->brandis->brand, array('style' => 'max-height:100px; max-width:250px; border:1px solid #c9e0ed; margin:10px') ), 
                                                   '/admin/models/index/category/'.$idkey.'/brand/'.$brands->brandis->id).
        CHtml::submitButton('', array('name'=>'ids', 'value'=> $brands->brandis->id, 'class'=>'image_button'))
        .'</div>';
    }
    
    
    
?>
<?php echo CHtml::endForm(); ?>
<script>
    $(document).ready(function(){
	   $('.image_button').on('click', function(){
	       if(!confirm('Вы уверены, что хотите удалить данный элемент?')) return false;
	   })
});
</script>
