<?php
//...
 
$form = $this->beginWidget('CActiveForm', 
    array(
        'method' => 'GET',
        'action' => '/search',
        'htmlOptions' => array(
            'class' => 'navbar-form navbar-right',
            'role' => 'form'
        ),
    )
);
?>
<div class="input-group">
<?php
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    //модель
    'model' => $searchModel,
    //атрибут модели
    'attribute' => 'query',
    //контроллер отдающий данные для выборки
    'source' => Yii::app()->createUrl('/main/index2'),
    'id' => 'top-search-field',
    'options' => array(
        //минимальное количество символов, после которого начнется поиск
        'minLength' => '1',
        'showAnim' => 'fold',
        //обработчик события, выбор пункта из списка (при выборе конкретного пункта отправляем на выбранную статью)
        'select' => 'js: function(event, ui) {
            window.location.href = ui.item.href;
            return false;
        }',),
        'htmlOptions' => array(
            'maxlength' => 50,
            'class' => 'form-control top-search-field',
            'placeholder' => 'Поиск...',
            'name' => 'query',
        ),
    )
);
 
?>     
</div>
<?php
echo CHtml::openTag('button', array('type' => 'submit', 'class' => 'btn btn-success alt-right'));
echo 'Поиск';
echo CHtml::closeTag('button');
$this->endWidget();
 
//...