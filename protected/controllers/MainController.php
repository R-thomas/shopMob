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
                        $items = explode('-', $item);
                        $item_finish = implode(' AND ', $items);
                        echo '<pre>'; print_r($item_finish); echo '</pre>';
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 12';
                        echo '<pre>'; print_r($filter); echo '</pre>';
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
                        echo '<pre>'; print_r($item_finish); echo '</pre>';
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 21';
                        echo '<pre>'; print_r($filter); echo '</pre>';
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
                    echo '<pre>'; print_r($sql); echo '</pre>';
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
                        echo '<pre>'; print_r($item_finish); echo '</pre>';
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 25';
                        echo '<pre>'; print_r($filter); echo '</pre>';
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
                        echo '<pre>'; print_r($item_finish); echo '</pre>';
                        $filter[] = 'SELECT DISTINCT model_id FROM {{characteristicValue}} WHERE (value BETWEEN '.$item_finish.') AND characteristic_id = 41';
                        echo '<pre>'; print_r($filter); echo '</pre>';
                    }
                    $sql = implode(' UNION ', $filter);
                    echo '<pre>'; print_r($sql); echo '</pre>';
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
        $sql_query = 'SELECT 
                        (SELECT COUNT( a1.id ) AS smart
                        FROM cms_characteristicValue AS a1
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "смартфон"
                        AND characteristic_id = 2
                        ) AS smart,
                        
                        (SELECT COUNT( a2.id ) AS tel
                        FROM cms_characteristicValue AS a2
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "телефон"
                        AND characteristic_id = 2
                        ) AS tel,
                        
                        (SELECT COUNT( a3.id ) AS button_mono
                        FROM cms_characteristicValue AS a3
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "кнопочный моноблок"
                        AND characteristic_id = 3
                        ) AS button_mono,
                        
                        (SELECT COUNT( a4.id ) AS trasformer
                        FROM cms_characteristicValue AS a4
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "раскладушка"
                        AND characteristic_id = 3
                        ) AS trasformer,
                        
                        (SELECT COUNT( a5.id ) AS sensor_mono
                        FROM cms_characteristicValue AS a5
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "сенсорный моноблок"
                        AND characteristic_id = 3
                        ) AS sensor_mono,
                        
                        (SELECT COUNT( a6.id ) AS android
                        FROM cms_characteristicValue AS a6
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "android"
                        AND characteristic_id = 4
                        ) AS android,
                        
                        (SELECT COUNT( a7.id ) AS ios
                        FROM cms_characteristicValue AS a7
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "ios"
                        AND characteristic_id = 4
                        ) AS ios,
                        
                        (SELECT COUNT( a8.id ) AS windows
                        FROM cms_characteristicValue AS a8
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "windows"
                        AND characteristic_id = 4
                        ) AS windows,
                        
                        (SELECT COUNT( a9.id ) AS no_os
                        FROM cms_characteristicValue AS a9
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "без ОС"
                        AND characteristic_id = 4
                        ) AS no_os,
                        
                        (SELECT COUNT( a10.id ) AS 1sim
                        FROM cms_characteristicValue AS a10
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1 sim"
                        AND characteristic_id = 5
                        ) AS 1sim,
                        
                        (SELECT COUNT( a11.id ) AS 2sim
                        FROM cms_characteristicValue AS a11
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2 sim"
                        AND characteristic_id = 5
                        ) AS 2sim,
                        
                        (SELECT COUNT( a12.id ) AS 3sim
                        FROM cms_characteristicValue AS a12
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "3 sim"
                        AND characteristic_id = 5
                        ) AS 3sim,
                        
                        (SELECT COUNT( a12.id ) AS 3sim
                        FROM cms_characteristicValue AS a12
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "3 sim"
                        AND characteristic_id = 5
                        ) AS 3sim,
                        
                        (SELECT COUNT( a13.id ) AS no_protect
                        FROM cms_characteristicValue AS a13
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "нет"
                        AND characteristic_id = 9
                        ) AS no_protect, 
                        
                        (SELECT COUNT( a14.id ) AS ip68
                        FROM cms_characteristicValue AS a14
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "ip68"
                        AND characteristic_id = 9
                        ) AS ip68, 
                        
                        (SELECT COUNT( a15.id ) AS ip67
                        FROM cms_characteristicValue a15
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "ip67"
                        AND characteristic_id = 9
                        ) AS ip67,
                        
                        (SELECT COUNT( a16.id ) AS diagonal_0_39
                        FROM cms_characteristicValue a16
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.1 AND 3.9)
                        AND characteristic_id = 12
                        ) AS diagonal_0_39,
                        
                        (SELECT COUNT( a17.id ) AS diagonal_40_45
                        FROM cms_characteristicValue a17
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 4.0 AND 4.5)
                        AND characteristic_id = 12
                        ) AS diagonal_40_45,
                        
                        (SELECT COUNT( a18.id ) AS diagonal_46_50
                        FROM cms_characteristicValue a18
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 4.6 AND 5.0)
                        AND characteristic_id = 12
                        ) AS diagonal_46_50,
                        
                        (SELECT COUNT( a19.id ) AS diagonal_51_55
                        FROM cms_characteristicValue a19
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 5.1 AND 5.5)
                        AND characteristic_id = 12
                        ) AS diagonal_51_55,
                        
                        (SELECT COUNT( a20.id ) AS diagonal_55_1000
                        FROM cms_characteristicValue a20
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 5.6 AND 1000)
                        AND characteristic_id = 12
                        ) AS diagonal_55_1000,
                        
                        (SELECT COUNT( a21.id ) AS TFT
                        FROM cms_characteristicValue a21
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "TFT"
                        AND characteristic_id = 14
                        ) AS TFT,
                        
                        (SELECT COUNT( a22.id ) AS TN
                        FROM cms_characteristicValue a22
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "TN"
                        AND characteristic_id = 14
                        ) AS TN,
                        
                        (SELECT COUNT( a23.id ) AS Retina
                        FROM cms_characteristicValue a23
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "Retina"
                        AND characteristic_id = 14
                        ) AS Retina,
                        
                        (SELECT COUNT( a24.id ) AS IPS
                        FROM cms_characteristicValue a24
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "IPS"
                        AND characteristic_id = 14
                        ) AS IPS,
                        
                        (SELECT COUNT( a25.id ) AS Amoled
                        FROM cms_characteristicValue a25
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "Amoled"
                        AND characteristic_id = 14
                        ) AS Amoled,
                        
                        (SELECT COUNT( a26.id ) AS SuperAmoled
                        FROM cms_characteristicValue a26
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "SuperAmoled"
                        AND characteristic_id = 14
                        ) AS SuperAmoled,
                        
                        (SELECT COUNT( a27.id ) AS x1
                        FROM cms_characteristicValue a27
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "x1"
                        AND characteristic_id = 18
                        ) AS x1,
                        
                        (SELECT COUNT( a28.id ) AS x2
                        FROM cms_characteristicValue a28
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "x2"
                        AND characteristic_id = 18
                        ) AS x2,
                        
                        (SELECT COUNT( a29.id ) AS x3
                        FROM cms_characteristicValue a29
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "x3"
                        AND characteristic_id = 18
                        ) AS x3,
                        
                        (SELECT COUNT( a30.id ) AS x4
                        FROM cms_characteristicValue a30
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "x4"
                        AND characteristic_id = 18
                        ) AS x4,
                        
                        (SELECT COUNT( a31.id ) AS f10
                        FROM cms_characteristicValue a31
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.0"
                        AND characteristic_id = 19
                        ) AS f10,
                        
                        (SELECT COUNT( a32.id ) AS f11
                        FROM cms_characteristicValue a32
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.1"
                        AND characteristic_id = 19
                        ) AS f11,
                        
                        (SELECT COUNT( a33.id ) AS f12
                        FROM cms_characteristicValue a33
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.2"
                        AND characteristic_id = 19
                        ) AS f12,
                        
                        (SELECT COUNT( a34.id ) AS f13
                        FROM cms_characteristicValue a34
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.3"
                        AND characteristic_id = 19
                        ) AS f13,
                        
                        (SELECT COUNT( a35.id ) AS f14
                        FROM cms_characteristicValue a35
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.4"
                        AND characteristic_id = 19
                        ) AS f14,
                        
                        (SELECT COUNT( a36.id ) AS f15
                        FROM cms_characteristicValue a36
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.5"
                        AND characteristic_id = 19
                        ) AS f15,
                        
                        (SELECT COUNT( a37.id ) AS f16
                        FROM cms_characteristicValue a37
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.6"
                        AND characteristic_id = 19
                        ) AS f16,
                        
                        (SELECT COUNT( a38.id ) AS f17
                        FROM cms_characteristicValue a38
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.7"
                        AND characteristic_id = 19
                        ) AS f17,
                        
                        (SELECT COUNT( a39.id ) AS f18
                        FROM cms_characteristicValue a39
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.8"
                        AND characteristic_id = 19
                        ) AS f18,
                        
                        (SELECT COUNT( a40.id ) AS f19
                        FROM cms_characteristicValue a40
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "1.9"
                        AND characteristic_id = 19
                        ) AS f19,
                        
                        (SELECT COUNT( a41.id ) AS f20
                        FROM cms_characteristicValue a41
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2.0"
                        AND characteristic_id = 19
                        ) AS f20,
                        
                        (SELECT COUNT( a42.id ) AS f21
                        FROM cms_characteristicValue a42
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2.1"
                        AND characteristic_id = 19
                        ) AS f21,
                        
                        (SELECT COUNT( a43.id ) AS f22
                        FROM cms_characteristicValue a43
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2.2"
                        AND characteristic_id = 19
                        ) AS f22,
                        
                        (SELECT COUNT( a44.id ) AS f23
                        FROM cms_characteristicValue a44
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2.3"
                        AND characteristic_id = 19
                        ) AS f23,
                        
                        (SELECT COUNT( a45.id ) AS f24
                        FROM cms_characteristicValue a45
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2.4"
                        AND characteristic_id = 19
                        ) AS f24,
                        
                        (SELECT COUNT( a46.id ) AS f25
                        FROM cms_characteristicValue a46
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value =  "2.5"
                        AND characteristic_id = 19
                        ) AS f25,
                        
                        (SELECT COUNT( a47.id ) AS cam_0_3
                        FROM cms_characteristicValue a47
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.1 AND 2.9)
                        AND characteristic_id = 21
                        ) AS cam_0_3,
                        
                        (SELECT COUNT( a48.id ) AS cam_3_5
                        FROM cms_characteristicValue a48
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 3 AND 4.9)
                        AND characteristic_id = 21
                        ) AS cam_3_5,
                        
                        (SELECT COUNT( a49.id ) AS cam_5_8
                        FROM cms_characteristicValue a49
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 5 AND 7.9)
                        AND characteristic_id = 21
                        ) AS cam_5_8,
                        
                        (SELECT COUNT( a50.id ) AS cam_8_13
                        FROM cms_characteristicValue a50
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 8 AND 12.9)
                        AND characteristic_id = 21
                        ) AS cam_8_13,
                        
                        (SELECT COUNT( a51.id ) AS cam_13_20
                        FROM cms_characteristicValue a51
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 13 AND 19.9)
                        AND characteristic_id = 21
                        ) AS cam_13_20,
                        
                        (SELECT COUNT( a52.id ) AS cam_20_100
                        FROM cms_characteristicValue a52
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 20 AND 100)
                        AND characteristic_id = 21
                        ) AS cam_20_100,
                        
                        (SELECT COUNT( a53.id ) AS front_cam_0_2
                        FROM cms_characteristicValue a53
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.1 AND 1.9)
                        AND characteristic_id = 22
                        ) AS front_cam_0_2,
                        
                        (SELECT COUNT( a54.id ) AS front_cam_2_5
                        FROM cms_characteristicValue a54
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 2 AND 4.9)
                        AND characteristic_id = 22
                        ) AS front_cam_2_5,
                        
                        (SELECT COUNT( a55.id ) AS front_cam_5_100
                        FROM cms_characteristicValue a55
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 5 AND 100)
                        AND characteristic_id = 22
                        ) AS front_cam_5_100,
                        
                        (SELECT COUNT( a56.id ) AS front_cam_no
                        FROM cms_characteristicValue a56
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "нет"
                        AND characteristic_id = 22
                        ) AS front_cam_no,
                        
                        (SELECT COUNT( a57.id ) AS ram_0_512
                        FROM cms_characteristicValue a57
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.100 AND 0.512)
                        AND characteristic_id = 25
                        ) AS ram_0_512,
                        
                        (SELECT COUNT( a58.id ) AS ram_512_1
                        FROM cms_characteristicValue a58
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.513 AND 0.999)
                        AND characteristic_id = 25
                        ) AS ram_512_1,
                        
                        (SELECT COUNT( a59.id ) AS ram_1_2
                        FROM cms_characteristicValue a59
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 1 AND 1.999)
                        AND characteristic_id = 25
                        ) AS ram_1_2,
                        
                        (SELECT COUNT( a60.id ) AS ram_2_3
                        FROM cms_characteristicValue a60
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 2 AND 2.999)
                        AND characteristic_id = 25
                        ) AS ram_2_3,
                        
                        (SELECT COUNT( a61.id ) AS ram_3_100
                        FROM cms_characteristicValue a61
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 3 AND 100)
                        AND characteristic_id = 25
                        ) AS ram_3_100,
                        
                        (SELECT COUNT( a62.id ) AS rom_0_4
                        FROM cms_characteristicValue a62
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.1 AND 3.9)
                        AND characteristic_id = 26
                        ) AS rom_0_4,
                        
                        (SELECT COUNT( a63.id ) AS rom_8
                        FROM cms_characteristicValue a63
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = 8
                        AND characteristic_id = 26
                        ) AS rom_8,
                        
                        (SELECT COUNT( a64.id ) AS rom_16
                        FROM cms_characteristicValue a64
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = 16
                        AND characteristic_id = 26
                        ) AS rom_16,
                        
                        (SELECT COUNT( a65.id ) AS rom_32
                        FROM cms_characteristicValue a65
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = 32
                        AND characteristic_id = 26
                        ) AS rom_32,
                        
                        (SELECT COUNT( a66.id ) AS rom_64
                        FROM cms_characteristicValue a66
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = 64
                        AND characteristic_id = 26
                        ) AS rom_64,
                        
                        (SELECT COUNT( a67.id ) AS rom_128
                        FROM cms_characteristicValue a67
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = 128
                        AND characteristic_id = 26
                        ) AS rom_128,
                        
                        (SELECT COUNT( a68.id ) AS wifi_yes
                        FROM cms_characteristicValue a68
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "есть"
                        AND characteristic_id = 29
                        ) AS wifi_yes,
                        
                        (SELECT COUNT( a69.id ) AS wifi_no
                        FROM cms_characteristicValue a69
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "нет"
                        AND characteristic_id = 29
                        ) AS wifi_no,
                        
                        (SELECT COUNT( a70.id ) AS gps_A_GPS
                        FROM cms_characteristicValue a70
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "A-GPS"
                        AND characteristic_id = 31
                        ) AS gps_A_GPS,
                        
                        (SELECT COUNT( a71.id ) AS gps_A_GPS_GPS
                        FROM cms_characteristicValue a71
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "A-GPS/GPS"
                        AND characteristic_id = 31
                        ) AS gps_A_GPS_GPS,
                        
                        (SELECT COUNT( a72.id ) AS gps_GPS
                        FROM cms_characteristicValue a72
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "GPS"
                        AND characteristic_id = 31
                        ) AS gps_GPS,
                        
                        (SELECT COUNT( a73.id ) AS gps_no
                        FROM cms_characteristicValue a73
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND value = "нет"
                        AND characteristic_id = 31
                        ) AS gps_no,
                        
                        (SELECT COUNT( a74.id ) AS batar_0_1000
                        FROM cms_characteristicValue a74
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 0.1 AND 999)
                        AND characteristic_id = 41
                        ) AS batar_0_1000,
                        
                        (SELECT COUNT( a75.id ) AS batar_1000_1500
                        FROM cms_characteristicValue a75
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 1000 AND 1499)
                        AND characteristic_id = 41
                        ) AS batar_1000_1500,
                        
                        (SELECT COUNT( a76.id ) AS batar_1500_2000
                        FROM cms_characteristicValue a76
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 1500 AND 1999)
                        AND characteristic_id = 41
                        ) AS batar_1500_2000,
                        
                        (SELECT COUNT( a77.id ) AS batar_more_2000
                        FROM cms_characteristicValue a77
                        WHERE model_id
                        IN ('.$ids_query.') 
                        AND (value BETWEEN 2000 AND 100000)
                        AND characteristic_id = 41
                        ) AS batar_more_2000
                      ';
        $connection = Yii::app()->db; 
        $count = $connection->createCommand($sql_query)->queryAll();
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
            'a' => $a,
            'count' => $count,
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