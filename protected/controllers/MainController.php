<?php
	
class MainController extends Controller
{
    public $layout='//layouts/main'; 
    
    public function actionIndex2()
    {
        $banner = Banner::model()->findAll();
        $news = News::model()->findAll();
        $topSales = Models::model()->findAll('top = 1');
        $novelty = Models::model()->findAll('novelty = 1');
        $random = Models::randomId();
                
        $this->render('index',
        array(
            'banner' => $banner,
            'news' => $news,
            'topSales' => $topSales,
            'novelty' => $novelty,
            'random' => $random,
        ));
    }
    
    public function actionIndex(){
        $this->layout = '//layouts/zagl';
        $this->render('zaglushka');
    }
    
    public function actionGoods($category_id)
    {
        
        $a = Yii::app()->getRequest()->getQueryString();
        
        $sql = array();
        if (isset($_GET['aaa'][0]))
        {
            foreach ($_GET['aaa'] as $item)
            {
                $sql[] = 'SELECT model_id FROM `cms_characteristicValue` WHERE value IN (SELECT value FROM `cms_characteristicValue` WHERE id="'.$item.'")';
            }
                
            $sql = implode(' UNION ', $sql);
            $connection = Yii::app()->db;          
            $select = $connection->createCommand($sql)->queryAll();
            $modelId = array();
            
            foreach($select as $item)
            {
                $modelId[] = $item['model_id'];
            }
        }
        
        
        
        
               
            //$sel = new CDbCriteria;
            //$sel->addCondition('t.value = "4,5"');
            //$select = CharacteristicValue::model()->findAll($sel);
              
                   
                   
        $brand = ModelCategory::model()->findAll('category_id = :category_id', array(':category_id' => $category_id));
        
        $array = array();
        foreach ($brand as $item)
        {
            $array[] = $item->brand_id;
        }
        
        
        $criteria = new CDbCriteria;
        $criteria->addInCondition('brand_id', $array);
        if(isset($modelId[0])){
            $criteria->addInCondition('id', $modelId);
        }
        
           
        //пагинация 
        $count = Models::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize=6;
        $pages->applyLimit($criteria);
        
        $model = Models::model()->findAll($criteria);
        
        $models = array();
        foreach ($model as $item)
        {
            $models[]=$item->id; 
        }
             
                
        $this->render('goods', array(
            'model' => $model,
            'pages' => $pages,
            'category_id' => $category_id,
            'models' => $models,
            'a' => $a
        ));
    }
    
    public function actionCart()
    {
        $this->render('cart');
    }
    
    public function actionProduct($id)
    {
        $model = Models::model()->findByPk($id);
        
        $accessories = explode(', ', $model->accessories);
        $criteria = new CDbCriteria;
        $criteria->addInCondition('vendor_code', $accessories);
        $slider = Models::model()->findAll($criteria);
        $char = Characteristics::cardChar($id);
        
        $this->render('product',
            array(
                'model' => $model,
                'char' => $char,
                'slider' => $slider
            )
        );
    }
    
    
}