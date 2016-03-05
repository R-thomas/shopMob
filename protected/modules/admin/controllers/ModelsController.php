<?php

class ModelsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='/layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','file'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($category, $id, $brand)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
            'id'=>$id,
            'category'=>$category,
            'brand'=>$brand
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($category, $brand)
	{
		$model=new Models;
        
        $criteria = new CDbCriteria;
        $criteria->condition = 'category_id = :category';
        $criteria->params = array(':category'=>$category);
        
        $modelChar = Characteristics::model()->findAll($criteria);
        $modelCharVal = new CharacteristicValue;
        
        
        
		if(isset($_POST['Models']))
		{
		    $acces = explode(',', $_POST['Models']['accessories']);
            $accessories = array();
            foreach($acces as $item)
            {
                $accessories[] = trim($item);
            }
            $model->accessories = json_encode($accessories);
              
			$model->attributes=$_POST['Models'];
            $model->description = $_POST['Models']['description'];
            $model->brand_id=$brand;
			if($model->save())
            {
                $modelId = $model->id;
            }	
		
        
            if(isset($_POST['characteristicValue']))
            {
                $modelCharVal = new CharacteristicValue;
                foreach ($_POST['characteristicValue'] as $k=>$item)
                {
                    $modelCharVal->id = false;
                    $modelCharVal->isNewRecord = true;
                    if($_POST['characteristicValue'][$k] != '')
                    {
                        $modelCharVal->value = $item; 
                    }
                    else
                    {
                        $modelCharVal->value = '-';
                    }
                    $modelCharVal->characteristic_id = $k;
                    $modelCharVal->model_id = $modelId;
                    $save = $modelCharVal->save();    
                }
                if($save)
                    $this->refresh();   
            }
	
        	
        }

		$this->render('create',array(
			'model'=>$model,
            'brand'=>$brand,
            'category'=>$category,
            'modelCharVal'=>$modelCharVal,
            'modelChar'=>$modelChar
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($category, $id, $brand)
	{
		$model=$this->loadModel($id);
        $modelChar = Characteristics::values($category, $id);
		if(isset($_POST['Models']))
		{
		    $model->attributes=$_POST['Models'];
            $acces = explode(',', $_POST['Models']['accessories']);
            $accessories = array();
            foreach($acces as $item)
            {
                $accessories[] = trim($item);
            }
            $model->accessories = json_encode($accessories);
			if($model->save())
			{
                if(isset($_POST['characteristicValue']))
                {
                    
                    foreach ($_POST['characteristicValue'] as $k=>$item)
                    {
                        $modelCharVal = CharacteristicValue::model()->updateAll(array('value'=>$item), 'characteristic_id = :k AND model_id = :id', array(':k'=>$k, ':id'=>$id));
                    }
                    if(isset($modelCharVal))
                        $this->refresh();   
                }
			}
		}
        
        

		$this->render('update',array(
			'model'=>$model,
            'category'=>$category,
            'modelChar'=>$modelChar,
            'brand'=>$brand
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($category, $brand, $id)
	{
	    $model = $this->loadModel($id);
		$model->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(array('/admin/models/index/category/'.$category.'/brand/'.$brand));
	}
    
    public function actionFile()
	{
	    $model = Models::model()->findAll();
        
        
        
         
	    $file_name = 'export.csv'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
        foreach($model as $item)
        {
            $csv_file = array($item->vendor_code, 
                              $item->brandModel->brand, 
                              $item->model_name, 
                              $item->price, 
                              $item->old_price, 
                              $item->photo, 
                              $item->photo_other, 
                              $item->quantity, 
                              $item->accessories, 
                              $item->top, 
                              $item->promotion, 
                              $item->novelty, 
                              $item_bestPrice);
            fputcsv($file, $csv_file, ";"); // записываем в файл строки
        }
        
        fclose($file); // закрываем файл
        
        // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        header('Content-type: application/csv'); // указываем, что это csv документ
        header("Content-Disposition: inline; filename=".$file_name); // указываем файл, с которым будем работать
        readfile($file_name); // считываем файл
        unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера

	}

	
	/**
	 * Manages all models.
	 */
	public function actionIndex($brand, $category)
	{
	    
	    if (isset($_POST['top'])&isset($_POST['id']))
        {
$models = Models::model()->findByPk($_POST['id']);
            $models->top == 0 ? $models->top = 1 : $models->top = 0;    
            $models->save(true, array('top'));
        }
        
        if (isset($_POST['promotion'])&isset($_POST['id']))
        {
$models = Models::model()->findByPk($_POST['id']);
            $models->promotion == 0 ? $models->promotion = 1 : $models->promotion = 0;    
            $models->save(true, array('promotion'));
        }
        
        if (isset($_POST['novelty'])&isset($_POST['id']))
        {
$models = Models::model()->findByPk($_POST['id']);
            $models->novelty == 0 ? $models->novelty = 1 : $models->novelty = 0;    
            $models->save(true, array('novelty'));
        }
        
        if (isset($_POST['bestPrice'])&isset($_POST['id']))
        {
$models = Models::model()->findByPk($_POST['id']);
            $models->bestPrice == 0?$models->bestPrice = 1:$models->bestPrice = 0;    
            $models->save(true, array('bestPrice'));
        }
        
		$model=new Models('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Models']))
			$model->attributes=$_GET['Models'];

		$this->render('index',array(
			'model'=>$model,
            'brand'=>$brand,
            'category'=>$category
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Models the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Models::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Models $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='models-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}