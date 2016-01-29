<?php

class CharacteristicsController extends Controller
{
    
    
    
	public function actionIndex($id)
	{
		$model = Characteristics::model()->findAll('category_id = :num', array(':num' => $id));
        $models = new Characteristics;
        $list = $this->listDown($id);
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
            'idk'=>$id
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $idk)
	{
	    $this->layout='/layouts/column2';
		$model=$this->loadModel($id);
        $models = new Characteristics;
        $list = $this->listDown($idk);

		if(isset($_POST['Characteristics']))
		{
			$model->attributes=$_POST['Characteristics'];
			if($model->save())
				$this->redirect(array('index','id'=>$idk));
		}

		$this->render('update',array(
			'model'=>$model,
            'models'=>$models,
            'list'=>$list,
            'idk'=>$idk
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id, $idk)
	{
		$model = $this->loadModel($id);
        if ($model->parent_id == 0)
        {
            Characteristics::model()->deleteAll('parent_id = :id', array(':id'=>$model->id));
        }
        $model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/characteristics/'.$idk));
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
    
    public function listDown($id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = '(parent_id = 0)&(category_id = :id)';
        $criteria->params = array(':id'=>$id);
        $list = array(0=>"")+CHtml::listData(Characteristics::model()->findAll($criteria), 'id', 'characteristic_name');
        return $list;
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
