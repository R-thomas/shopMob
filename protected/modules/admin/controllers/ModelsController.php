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
				'actions'=>array('index','view'),
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
        
        
        
        
		if(isset($_POST['Models']))
		{
			$model->attributes=$_POST['Models'];
            $model->brand_id=$brand;
			if($model->save())
            {
                $modelId = $model->id;
            }	
		
        
            if(isset($_POST['characteristicValue']))
            {
                foreach ($_POST['characteristicValue'] as $k=>$item)
                {
                    $modelCharVal = new CharacteristicValue;
                    $modelCharVal->value = $item; 
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
			if($model->save())
			{
                if(isset($_POST['characteristicValue']))
                {
                    foreach ($_POST['characteristicValue'] as $k=>$item)
                    {
                        $criteria = new CDbCriteria;
                        $criteria->condition = '(characteristic_id = :k)&(model_id = :id)';
                        $criteria->params = array(':k'=>$k, ':id'=>$id);
                        $modelCharVal = CharacteristicValue::model()->find($criteria);
                        $modelCharVal->value = $item;
                        $save = $modelCharVal->save(false);    
                    }
                    if($save)
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

	
	/**
	 * Manages all models.
	 */
	public function actionIndex($brand, $category)
	{
	    $models = Models::model()->findByPk($_POST['id']);
	    if (isset($_POST['top'])&isset($_POST['id']))
        {
            $models->top == 0 ? $models->top = 1 : $models->top = 0;    
            if($models->save())
                $this->refresh();
        }
        
        if (isset($_POST['promotion'])&isset($_POST['id']))
        {
            $models->promotion == 0 ? $models->promotion = 1 : $models->promotion = 0;    
            if($models->save())
                $this->refresh();
        }
        
        if (isset($_POST['novelty'])&isset($_POST['id']))
        {
            $models->novelty == 0 ? $models->novelty = 1 : $models->novelty = 0;    
            if($models->save())
                $this->refresh();
        }
        
        if (isset($_POST['bestPrice'])&isset($_POST['id']))
        {
            $models->bestPrice == 0?$models->bestPrice = 1:$models->bestPrice = 0;    
            if($models->save())
                $this->refresh();
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
