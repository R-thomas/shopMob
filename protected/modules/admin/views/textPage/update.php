<h1>Редактирование страницы "<?php echo ($model->id==1 ? 'О компании' : 
                                        ($model->id==2 ? 'Доставка и оплата' :
                                        ($model->id==3 ? 'Контакты' : 
                                        '')));       
                             ?>"</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>