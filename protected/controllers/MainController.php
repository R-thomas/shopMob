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
        //главное меню
        $this->menu = Category::model()->findAll();
        //echo '<pre>'; print_r($_GET); echo '</pre>';
        $criteria = new CDbCriteria;
        if(isset($_GET))
        {
            if(key($_GET) == 'common')
            {
                $bz = key($_GET);
                foreach($_GET as $items)
                {
                    $query = $items;
                    break;
                    
                }
                if (is_array($query))
                {
                    $filter = array();
                    foreach($query as $item)
                    {
                        $filter[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                    }
                    $sql = implode(' UNION ', $filter);
                    $connection = Yii::app()->db; 
                    $model = $connection->createCommand($sql)->queryAll();
                    foreach ($model as $item)
                    {
                        $t1[] = $item['id'];
                    }
                    
                    
                }
            }
            
            else if(key($_GET) == 'brand')
            {
                //
            }
            
            else if(key($_GET) == 'type' || key($_GET) == 'form' || key($_GET) == 'os' || key($_GET) == 'sim' || key($_GET) == 'protection' || key($_GET) == 'screen' || key($_GET) == 'core' || key($_GET) == 'core_frequency' || key($_GET) == 'wifi'  || key($_GET) == 'GPS')
            { 
                $bz = key($_GET);
                foreach($_GET as $items)
                {
                    $query = $items;
                    break;
                    
                }
                if (is_array($query))
                {
                    $filter = array();
                    foreach($query as $item)
                    {
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE value="'.$item.'"';
                    }
                    $sql = implode(' UNION ', $filter);
                    $connection = Yii::app()->db; 
                    $model = $connection->createCommand($sql)->queryAll();
                    
                    foreach ($model as $item)
                    {
                        $t1[] = $item['model_id'];
                    }
                }
            }
            
            else if(key($_GET) == 'diagonal')
            { 
                $bz = key($_GET);
                foreach($_GET as $items)
                {
                    $query = $items;
                    break;
                    
                }
                if (is_array($query))
                {
                    
                    $filter = array();
                    foreach($query as $item)
                    {
                        explode('-', $item);
                        
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE value="'.$item.'"';
                    }
                    $sql = implode(' UNION ', $filter);
                    $connection = Yii::app()->db; 
                    $model = $connection->createCommand($sql)->queryAll();
                    
                    foreach ($model as $item)
                    {
                        $t1[] = $item['model_id'];
                    }
                }
            }
            
            
            
            
            
            $i = 0;
            foreach($_GET as $k=>$items)
            {
                $i++;
                if($i==2)
                {
                    $querys = $items;
                    if (is_array($querys))
                        {
                            $filter = array();
                            foreach($querys as $itemt)
                            {
                                $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE value="'.$itemt.'"';
                            }
                            $sql = implode(' UNION ', $filter);
                           
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                           
                            
                        }
                }
                
                if($i==3)
                {
                    $query2 = $items;
                    if (is_array($query2))
                        {
                            $filter = array();
                            foreach($query2 as $itemt)
                            {
                                $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE value="'.$itemt.'"';
                            }
                            $sql = implode(' UNION ', $filter);
                           
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $itemt)
                            {
                                $t3[] = $itemt['model_id'];
                            }
                            
                            
                        }
                }
                
                
                
            }
            echo '<pre>t1'; print_r($t1); echo '</pre>';
            echo '<pre>t2'; print_r($t2); echo '</pre>';
            echo '<pre>t3'; print_r($t3); echo '</pre>';
        }
        if($t1&&$t2)
        $z = array_intersect($t1, $t2);
        if($z&&$t3)
        $z = array_intersect($z, $t3);
        echo '<pre>'; print_r($z); echo '</pre>';
        
        $a = Yii::app()->getRequest()->getQueryString();
        /*
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
        
        */
        
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
        
        
        //Бренды для фильтров
        $criter = new CDbCriteria;
        $criter->addInCondition('id', $array);
        $brands = Brand::model()->findAll($criter);
         
        //Товары
        if($z)
        {
            $criteria->addInCondition('id', $z);
        }
        if(!$z&&$t1)
        {
            $criteria->addInCondition('id', $t1);
        }         
        
        $criteria->addInCondition('brand_id', $array);
        
        /*
        if(isset($modelId[0])){
            $criteria->addInCondition('id', $modelId);
        }
        */
           
        //пагинация 
        $count = Models::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize=6;
        $pages->applyLimit($criteria);
        
        $model = Models::model()->findAll($criteria);
        $ids = array();
        if(!$z)
        {
            foreach($model as $item)
            {
                $ids[] = $item->id;
            }
        }
        else if (!$z&&$t1)
        {
            $ids = $t1;
        }
        else if ($z)
        {
            $ids = $z;
        }
        $ids_query = implode(', ', $ids);
        echo '<pre>'; print_r($ids_query); echo '</pre>';
        $sql_query = 'SELECT (
                        SELECT COUNT( z.id ) AS ip68
                        FROM cms_characteristicValue AS z
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "ip68"
                        ) AS ip68, (
                        
                        SELECT COUNT( x.id ) AS ip67
                        FROM cms_characteristicValue x
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "ip67"
                        ) AS ip67
                      ';
        $connection = Yii::app()->db; 
        $count = $connection->createCommand($sql_query)->queryAll();
        echo '<pre>'; print_r($count); echo '</pre>';
        /*
        $models = array();
        foreach ($model as $item)
        {
            $models[]=$item->id; 
        }
        */     
                
        $this->render('goods', array(
            'model' => $model,
            'pages' => $pages,
            'brands' => $brands,
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