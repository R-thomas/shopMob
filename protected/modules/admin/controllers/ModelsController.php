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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id)
	{
		$model=new Models;
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Models']))
		{
			$model->attributes=$_POST['Models'];
            $model->brand_id=$id;
			if($model->save())
            {
                Yii::app()->user->setFlash('status','Модель добавлена');
                $this->refresh();
            }	
		}

		$this->render('create',array(
			'model'=>$model,
            'id'=>$id,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Models']))
		{
		    $model->attributes=$_POST['Models'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
	    $model = $this->loadModel($id);
		$model->delete();
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/models/'.$model->brandModel->id));
	}

	
	/**
	 * Manages all models.
	 */
	public function actionIndex($id)
	{
	    if (isset($_POST['top'])&isset($_POST['id']))
        {
            $models = Models::model()->findByPk($_POST['id']);
            if ($models->top == 0)
                $models->top = 1;
            else
                $models->top = 0;    
            if($models->save())
                $this->refresh();
        }
        
        if (isset($_POST['promotion'])&isset($_POST['id']))
        {
            $models = Models::model()->findByPk($_POST['id']);
            if ($models->promotion == 0)
                $models->promotion = 1;
            else
                $models->promotion = 0;    
            if($models->save())
                $this->refresh();
        }
        
        if (isset($_POST['novelty'])&isset($_POST['id']))
        {
            $models = Models::model()->findByPk($_POST['id']);
            if ($models->novelty == 0)
                $models->novelty = 1;
            else
                $models->novelty = 0;    
            if($models->save())
                $this->refresh();
        }
        
        if (isset($_POST['bestPrice'])&isset($_POST['id']))
        {
            $models = Models::model()->findByPk($_POST['id']);
            if ($models->bestPrice == 0)
                $models->bestPrice = 1;
            else
                $models->bestPrice = 0;    
            if($models->save())
                $this->refresh();
        }
        
		$model=new Models('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Models']))
			$model->attributes=$_GET['Models'];

		$this->render('index',array(
			'model'=>$model,
            'id'=>$id
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
