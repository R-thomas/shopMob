<?php

class CharacteristicsController extends Controller
{
	public function actionIndex($id)
	{
		$model = Characteristics::model()->findAll('category_id = :num', array(':num' => $id));
        $models = new Characteristics;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        $criteria = new CDbCriteria;
        $criteria->condition = '(parent_id = 0)&(category_id = :id)';
        $criteria->params = array(':id'=>$id);
        $list = array(0=>"")+CHtml::listData(Characteristics::model()->findAll($criteria), 'id', 'characteristic_name');
		if(isset($_POST['Characteristics']))
		{
            //echo '<pre>';
            //print_r($_POST);
            //echo '</pre>';
			$models->attributes=$_POST['Characteristics'];
            $models->category_id = $id;
			if($models->save())
				$this->refresh();
		}

		$this->render('index',array(
			'model'=>$model,
            'models'=>$models,
            'list'=>$list,
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

		if(isset($_POST['Characteristics']))
		{
			$model->attributes=$_POST['Characteristics'];
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Characteristics the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Characteristics::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Characteristics $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='characteristics-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
