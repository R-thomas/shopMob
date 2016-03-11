<?php
	
class MainController extends Controller
{
    public $layout='//layouts/main';
    public $menu;
    
    public function actionIndex()
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
    
        
    public function actionGoods($category_id)
    {

        if ($category_id == 1)
        {
            
            $cat = $category_id;
            //главное меню
            $this->menu = Category::model()->findAll();
            
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
                            $filter[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
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
                            $items = explode('-', $item);
                            $item_finish = implode(' AND ', $items);
                           
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 12';
                            
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
                
                else if(key($_GET) == 'camera')
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
                            $items = explode('-', $item);
                            $item_finish = implode(' AND ', $items);
                           
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 21';
                            
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
                
                else if(key($_GET) == 'front_camera')
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
                            if($item == 'нет')
                            {
                                $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 22';
                            }
                            else
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 22';
                            }
                            
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
                
                else if(key($_GET) == 'ram')
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
                            $items = explode('-', $item);
                            $item_finish = implode(' AND ', $items);
                            
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 25';
                            
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
                
                else if(key($_GET) == 'rom')
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
                            if($item == '0.1-4')
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 26';
                            }
                            else
                            {
                                $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item.') AND characteristic_id = 26';
                            }
                            
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
                
                else if(key($_GET) == 'battery')
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
                            $items = explode('-', $item);
                            $item_finish = implode(' AND ', $items);
                            
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 41';
                            
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
                        
                        
                        if($k == 'common')
                        {
                            $querys = $items;
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    
                                    $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                    
                                }
                                $sql = implode(' UNION ', $filter2);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['id'];
                                }
                            }
                            
                        }
                        
                        elseif($k == 'brand')
                        {
                            $querys = $items;
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                                    
                                }
                                $sql = implode(' UNION ', $filter2);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                            
                        }
                        
                        elseif($k == 'diagonal')
                        {
                            $querys = $items;
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 12';
                                    
                                }
                                $sql = implode(' UNION ', $filter2);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                            
                        }
                        
                        elseif($k == 'camera')
                        {
                            
                            $querys = $items;
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 21';
                                    
                                }
                                $sql = implode(' UNION ', $filter3);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
    
                                }
                            }
                            
                        }
                        
                        elseif($k == 'front_camera')
                        {
                            $querys = $items;
                            
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    if($item == 'нет')
                                    {
                                        $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 22';
                                        
                                    }
                                    else
                                    {
                                        $items = explode('-', $item);
                                        $item_finish = implode(' AND ', $items);
                                        $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 22';
                                        
                                    }
                                    
                                }
                                $sql = implode(' UNION ', $filter4);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                                
                            }
                            
                        }
                        
                        elseif($k == 'ram')
                        {
                            $querys = $items;
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 25';
                                    
                                }
                                $sql = implode(' UNION ', $filter5);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                            
                        }
                        
                        elseif($k == 'rom')
                        {
                            $querys = $items;
                            if($querys[0] == '0.1-4')
                            {
                                if (is_array($querys))
                                {
                                    foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 26';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 26';
                                   
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 41';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t2 = array();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            } 
                        }
                    }
                    
                    
                }
                
                if($i==3)
                {
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                           
                            foreach ($model as $item)
                            {
                                $t3[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                               
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 12';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 21';
                                
                            }
                            $sql = implode(' UNION ', $filter3);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 22';
                                    
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 22';
                                    
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 25';
                               
                            }
                            $sql = implode(' UNION ', $filter5);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 26';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t3[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 26';
                                   
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t3[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 41';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t3 = array();
                            foreach ($model as $item)
                            {
                                $t3[] = $item['model_id'];
                            } 
                        }
                    }
                }
                
                
                if($i==4)
                {
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                           
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t4[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 12';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                           
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 21';
                                
                            }
                           
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 22';
                                   
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 22';
                                   
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 25';
                                
                            }
                            $sql = implode(' UNION ', $filter5);
                           
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 26';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t4[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 26';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t4[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 41';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t4 = array();
                            foreach ($model as $item)
                            {
                                $t4[] = $item['model_id'];
                            } 
                        }
                    }
                }
                
                if($i==5)
                {
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                               
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t5[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                               
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 12';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 21';
                                
                            }
                            $sql = implode(' UNION ', $filter3);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 22';
                                    
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 22';
                                    
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 25';
                                
                            }
                            $sql = implode(' UNION ', $filter5);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 26';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t5[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                           
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 26';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t5[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 41';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t5 = array();
                            foreach ($model as $item)
                            {
                                $t5[] = $item['model_id'];
                            } 
                        }
                    }
                }
                
            }
            
        }
        if(isset($t1)&&isset($t2))
        $z = array_intersect($t1, $t2);
        if(isset($z)&&isset($t3))
        $z = array_intersect($z, $t3);
        if(isset($z)&&isset($t4))
        $z = array_intersect($z, $t4);
        if(isset($z)&&isset($t5))
        $z = array_intersect($z, $t5);
        
        $a = Yii::app()->getRequest()->getQueryString();
        
        
        if(Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->shoppingCart->put(Models::model()->findByPk($_POST['id']));
            $data[0] = Yii::app()->shoppingCart->getCost();
            $data[1] = Yii::app()->shoppingCart->getCount();
            echo json_encode($data);
            
            
            // Завершаем приложение
            Yii::app()->end();
        }
        
                  
        $brand = ModelCategory::model()->findAll('category_id = :category_id', array(':category_id' => $cat));
        
        
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
        if(isset($z))
        {
            $criteria->addInCondition('id', $z);
        }
        if(!isset($z)&&isset($t1))
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
        if(!isset($z))
        {
            foreach($model as $item)
            {
                $ids[] = $item->id;
            }
        }
        else if (!isset($z)&&isset($t1))
        {
            $ids = $t1;
        }
        else if (isset($z))
        {
            $ids = $z;
        }
        
        $ids_query = implode(', ', $ids);
        
        
        //sql запрос - возвращает количество моделей по каждой характеристике в фильтре
        $count = Models::filterCategory($ids_query, $category_id);
        
        //подсчет количества производителей
        $count_maker_arr = array();
        foreach ($model as $item)
        {
            $count_maker_arr[] = $item->brand_id;
        }
        $count_maker = array_count_values ($count_maker_arr);
        
        // подсчет количества моделей "топ продаж"
        $count_top = 0;
        foreach ($model as $item)
        {
            if($item->top == 1)
            {
                $count_top++;
            }
        }
        
        // подсчет количества моделей "Акция"
        $count_promotion = 0;
        foreach ($model as $item)
        {
            if($item->promotion == 1)
            {
                $count_promotion++;
            }
        }
        
        // подсчет количества моделей "Новинки"
        $count_novelty = 0;
        foreach ($model as $item)
        {
            if($item->novelty == 1)
            {
                $count_novelty++;
            }
        }
        
        // подсчет количества моделей "Лучшая цена"
        $count_bestPrice = 0;
        foreach ($model as $item)
        {
            if($item->bestPrice == 1)
            {
                $count_bestPrice++;
            }
        }
        }
        elseif($category_id == 2)
        {
            $cat = $category_id;
        //главное меню
        $this->menu = Category::model()->findAll();
        
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
                        $filter[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
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
            
            else if(key($_GET) == 'type' || key($_GET) == 'os' || key($_GET) == 'sim' || key($_GET) == 'screen' || key($_GET) == 'core' || key($_GET) == 'core_frequency' || key($_GET) == 'GPS')
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
                        $items = explode('-', $item);
                        $item_finish = implode(' AND ', $items);
                        
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 57';
                        
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
            
            else if(key($_GET) == 'camera')
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
                        $items = explode('-', $item);
                        $item_finish = implode(' AND ', $items);
                        
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 65';
                        
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
            
            else if(key($_GET) == 'front_camera')
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
                        if($item == 'нет')
                        {
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 66';
                        }
                        else
                        {
                            $items = explode('-', $item);
                            $item_finish = implode(' AND ', $items);
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 66';
                        }
                        
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
            
            else if(key($_GET) == 'ram')
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
                        $items = explode('-', $item);
                        $item_finish = implode(' AND ', $items);
                        
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 68';
                        
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
            
            else if(key($_GET) == 'rom')
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
                        if($item == '0.1-4')
                        {
                            $items = explode('-', $item);
                            $item_finish = implode(' AND ', $items);
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 69';
                        }
                        else
                        {
                            $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item.') AND characteristic_id = 69';
                        }
                        
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
            
            else if(key($_GET) == 'battery')
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
                        $items = explode('-', $item);
                        $item_finish = implode(' AND ', $items);
                        
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 80';
                        
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
                    
                    
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 57';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 65';
                               
                            }
                            $sql = implode(' UNION ', $filter3);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 66';
                                    
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 66';
                                    
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 68';
                                
                            }
                            $sql = implode(' UNION ', $filter5);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 69';
                                   
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 69';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 80';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t2 = array();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            } 
                        }
                    }
                    
                    
                }
                
                if($i==3)
                {
                    
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 57';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 65';
                               
                            }
                            $sql = implode(' UNION ', $filter3);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 66';
                                    
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 66';
                                    
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 68';
                                
                            }
                            $sql = implode(' UNION ', $filter5);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 69';
                                   
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 69';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 80';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t2 = array();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            } 
                        }
                    }
                }
                
                
                if($i==4)
                {
                    
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 57';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 65';
                               
                            }
                            $sql = implode(' UNION ', $filter3);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 66';
                                    
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 66';
                                    
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 68';
                                
                            }
                            $sql = implode(' UNION ', $filter5);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 69';
                                   
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 69';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 80';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t2 = array();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            } 
                        }
                    }
                }
                
                if($i==5)
                {
                    
                    if($k == 'common')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                
                                $filter2[] = 'SELECT id FROM {{models}} WHERE '.$item.'=1';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'brand')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT cms_models.id as model_id FROM cms_models JOIN cms_brand ON brand_id = cms_brand.id WHERE brand = "'.$item.'"';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'diagonal')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                               
                                $filter2[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 57';
                                
                            }
                            $sql = implode(' UNION ', $filter2);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'camera')
                    {
                        
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                $filter3[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 65';
                               
                            }
                            $sql = implode(' UNION ', $filter3);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];

                            }
                        }
                        
                    }
                    
                    elseif($k == 'front_camera')
                    {
                        $querys = $items;
                        
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                if($item == 'нет')
                                {
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = "'.$item.'") AND characteristic_id = 66';
                                    
                                }
                                else
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    $filter4[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 66';
                                    
                                }
                                
                            }
                            $sql = implode(' UNION ', $filter4);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                            
                        }
                        
                    }
                    
                    elseif($k == 'ram')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter5[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 68';
                                
                            }
                            $sql = implode(' UNION ', $filter5);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    
                    elseif($k == 'rom')
                    {
                        $querys = $items;
                        if($querys[0] == '0.1-4')
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item)
                                {
                                    $items = explode('-', $item);
                                    $item_finish = implode(' AND ', $items);
                                    
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 69';
                                   
                                }
                                $sql = implode(' UNION ', $filter6);
                               
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        else
                        {
                            if (is_array($querys))
                            {
                                foreach($querys as $item2)
                                {
                                    $filter6[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value = '.$item2.') AND characteristic_id = 69';
                                    
                                }
                                $sql = implode(' UNION ', $filter6);
                                
                                $connection = Yii::app()->db; 
                                $model = $connection->createCommand($sql)->queryAll();
                                
                                foreach ($model as $item)
                                {
                                    $t2[] = $item['model_id'];
                                }
                            }
                        }
                        
                        
                        
                    }
                    
                    elseif($k == 'battery')
                    {
                        $querys = $items;
                        if (is_array($querys))
                        {
                            foreach($querys as $item)
                            {
                                $items = explode('-', $item);
                                $item_finish = implode(' AND ', $items);
                                
                                $filter7[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 80';
                                
                            }
                            $sql = implode(' UNION ', $filter7);
                            
                            $connection = Yii::app()->db; 
                            $model = $connection->createCommand($sql)->queryAll();
                            
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            }
                        }
                        
                    }
                    else
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
                            $t2 = array();
                            foreach ($model as $item)
                            {
                                $t2[] = $item['model_id'];
                            } 
                        }
                    }
                
            }
            
        }
        if(isset($t1)&& isset($t2))
        $z = array_intersect($t1, $t2);
        if(isset($z)&&isset($t3))
        $z = array_intersect($z, $t3);
        if(isset($z)&&isset($t4))
        $z = array_intersect($z, $t4);
        if(isset($z)&&isset($t5))
        $z = array_intersect($z, $t5);
        
        
        $a = Yii::app()->getRequest()->getQueryString();
        
        
        if(Yii::app()->request->isAjaxRequest)
        {
            Yii::app()->shoppingCart->put(Models::model()->findByPk($_POST['id']));
            $data[0] = Yii::app()->shoppingCart->getCost();
            $data[1] = Yii::app()->shoppingCart->getCount();
            echo json_encode($data);
            
            
            // Завершаем приложение
            Yii::app()->end();
        }
        
                  
        $brand = ModelCategory::model()->findAll('category_id = :category_id', array(':category_id' => $cat));
        
        
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
        if(isset($z))
        {
            $criteria->addInCondition('id', $z);
        }
        if(!isset($z)&&isset($t1))
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
        if(!isset($z))
        {
            foreach($model as $item)
            {
                $ids[] = $item->id;
            }
        }
        else if (!isset($z)&&isset($t1))
        {
            $ids = $t1;
        }
        else if (isset($z))
        {
            $ids = $z;
        }
        
        $ids_query = implode(', ', $ids);
        
        
        //sql запрос - возвращает количество моделей по каждой характеристике в фильтре
        if($ids_query!='')
            $count = Models::filterCategory($ids_query, $category_id);
        
        //подсчет количества производителей
        $count_maker_arr = array();
        foreach ($model as $item)
        {
            $count_maker_arr[] = $item->brand_id;
        }
        $count_maker = array_count_values ($count_maker_arr);
        
        // подсчет количества моделей "топ продаж"
        $count_top = 0;
        foreach ($model as $item)
        {
            if($item->top == 1)
            {
                $count_top++;
            }
        }
        
        // подсчет количества моделей "Акция"
        $count_promotion = 0;
        foreach ($model as $item)
        {
            if($item->promotion == 1)
            {
                $count_promotion++;
            }
        }
        
        // подсчет количества моделей "Новинки"
        $count_novelty = 0;
        foreach ($model as $item)
        {
            if($item->novelty == 1)
            {
                $count_novelty++;
            }
        }
        
        // подсчет количества моделей "Лучшая цена"
        $count_bestPrice = 0;
        foreach ($model as $item)
        {
            if($item->bestPrice == 1)
            {
                $count_bestPrice++;
            }
        }
        }}
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
            //'models' => $models,
            'a' => $a,
            'count' => $count,
            'count_maker' => $count_maker,
            'count_top' => $count_top,
            'count_promotion' => $count_promotion,
            'count_novelty' => $count_novelty,
            'count_bestPrice' => $count_bestPrice
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
                $this->redirect(array('success','id_order'=>$order->id));
                
            }
        }
        
        $this->render('cart', array(
            'order'=>$order
        ));
    }
    
    public function actionSuccess()
    {
        $this->menu = Category::model()->findAll();
        $this->render('success');
    }
    
    public function actionOrderNumber()
    {
        $this->menu = Category::model()->findAll();
        $input = Yii::app()->request->getPost('input');
        $input_int = (int)$input;
        if($input_int !=0)
        {
            
            $model = Orders::model()->findByPk($input_int);
            if(gettype($model) != 'NULL')
            {
                $id = $model->id;
                $status = $model->status;
                if($status == 0 && $id !=0)
                    $output = 'Новый';
                elseif($status == 1 && $id !=0)
                    $output = 'В обработке';
                elseif($status == 2 && $id !=0)
                    $output = 'Отправлен';
                elseif($status == 3 && $id !=0)
                    $output = 'Доставлен';
                elseif($status == 4 && $id !=0)
                    $output = 'Отменен';
                elseif($status == 5 && $id !=0)
                    $output = 'Отменен навсегда';
                
            }   
            else
                $output = 'Данные отсутствуют';     
            
            
        }
        else
        {
            $output = 'Неверный ввод';
        }
        
             
                        
        
 
        // если запрос асинхронный, то нам нужно отдать только данные
        if(Yii::app()->request->isAjaxRequest){
            echo CHtml::encode($output);
            // Завершаем приложение
            Yii::app()->end();
        }
        $this->render('orderNumber');
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