<?php
	
class MainController extends Controller
{
    public $layout='//layouts/main';
    public $menu;
    
    public function actionIndex2()
    {
        $this->menu = Category::model()->findAll();
        $banner = Banner::model()->findAll();
        $news = News::model()->findAll();
        $topSales = Models::model()->findAll('top = 1');
        
        
               
        if(Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->shoppingCart->put(Models::model()->findByPk($_POST['id']));
            $data[0] = Yii::app()->shoppingCart->getCost();
            $data[1] = Yii::app()->shoppingCart->getCount();
            echo json_encode($data);
            
            
            // Завершаем приложение
            Yii::app()->end();
        }
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
        $this->menu = Category::model()->findAll();
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
        
        if(Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->shoppingCart->put(Models::model()->findByPk($_POST['id']));
            $data[0] = Yii::app()->shoppingCart->getCost();
            $data[1] = Yii::app()->shoppingCart->getCount();
            echo json_encode($data);
            
            
            // Завершаем приложение
            Yii::app()->end();
        }
                   
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
        $this->menu = Category::model()->findAll();
        if(isset($_POST['submit_cart']))
        {
            Yii::app()->shoppingCart->remove($_POST['submit_cart']);
        }
        
        if(Yii::app()->request->isAjaxRequest)
        {
            $pos_first = Yii::app()->shoppingCart->getPositions();
            foreach($pos_first as $position) 
            {
                if($position->id == $_POST['id'])
                {
                    $quantity = $position->getQuantity();
                }
                
            }
            Yii::app()->shoppingCart->update((Models::model()->findByPk($_POST['id'])), $_POST['button']=='minus'?($quantity>1?$quantity-1:$quantity):$quantity+1);
            $pos = Yii::app()->shoppingCart->getPositions();
            $data[0] = Yii::app()->shoppingCart->getCost();
            $data[1] = Yii::app()->shoppingCart->getCount();
            foreach($pos as $position) 
            {
                if($position->id == $_POST['id'])
                {
                    $data[2] = $position->getQuantity();
                    $data[3] = $position->getSumPrice();
                }
                
            }
            
            echo json_encode($data);
            
            
            // Завершаем приложение
            Yii::app()->end();
        }
        
        $order = new Orders;
        if (isset($_POST['Orders']))
        {
            $items = Yii::app()->shoppingCart->getPositions();
            $id = array();
            $quant = array();
            $sum = array();
            $cost = Yii::app()->shoppingCart->getCost();
            foreach($items as $item)
            {
                $name[] = $item->brandModel->brand.' '.$item->model_name;
                $quant[] = $item->getQuantity();
                $sum[] = $item->getSumPrice();
            }
            
            $order->attributes = $_POST['Orders'];
            $order->model_id = json_encode($name);
            $order->quantity = json_encode($quant);
            $order->sum = json_encode($sum);
            $order->total = $cost;
            if ($order->save())
            {
                Yii::app()->shoppingCart->clear();
                echo '111';
            }
        }
        
        $this->render('cart', array(
            'order'=>$order
        ));
    }
    
    public function actionProduct($id)
    {
        $this->menu = Category::model()->findAll();
        $model = Models::model()->findByPk($id);
        if(Yii::app()->request->getPost('submit'))
        {
            Yii::app()->shoppingCart->put($model);
        }
        if(Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->shoppingCart->put($model);
            $data[0] = Yii::app()->shoppingCart->getCost();
            $data[1] = Yii::app()->shoppingCart->getCount();
            echo json_encode($data);
            
            // Завершаем приложение
            Yii::app()->end();
        }
        
        $accessories = explode(', ', $model->accessories);
        $criteria = new CDbCriteria;
        $criteria->addInCondition('vendor_code', $accessories);
        $slider = Models::model()->findAll($criteria);
        $char = Characteristics::cardChar($id);
        
        
        $this->render('product',
            array(
                'model' => $model,
                'char' => $char,
                'slider' => $slider,
            )
        );
    }
    
    
    
    
}