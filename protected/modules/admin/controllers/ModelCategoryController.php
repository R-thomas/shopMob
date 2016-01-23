<?php

class ModelCategoryController extends Controller
{
    public $layout='/layouts/column2-2';
	public function actionIndex($id)
	{
	   $model = ModelCategory::model()->findAll('category_id = :num', array(':num' => $id));
       
       
       
       $modelBrand = new Brand;
       $modelCategory = new ModelCategory;
       if(isset($_POST['ids']))
       {    
            
            ModelCategory::model()->deleteAll('brand_id = :brand_id', array(':brand_id' => $_POST['ids']));
            Brand::model()->findByPk($_POST['ids'])->delete();
            $this->refresh();
            
       }
       if(isset($_POST['yt0']))
       {
            $modelBrand->attributes = $_POST['Brand'];
            $modelBrand->save();
            $modelCategory->category_id = $id;
            $modelCategory->brand_id = $modelBrand->id;
            if ($modelCategory->save())
                Yii::app()->user->setFlash('status','Бренд добавлен');
                $this->refresh();
       }
       
       
            
		$this->render('index', array(
        'model' => $model,
        'modelBrand' => $modelBrand));
	}

}
	