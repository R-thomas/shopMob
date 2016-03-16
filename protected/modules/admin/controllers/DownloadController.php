<?php

class DownloadController extends Controller
{
    public $layout='/layouts/column2-2';
	public function actionIndex($id = false)
	{
	   $models = new CsvUpload;
	   if($id == 1)
       {
            if(isset($_POST['CsvUpload']['photo_img']))
            {
                
                if ($files = CUploadedFile::getInstances($models, 'photo_img'))
                {
                    $array = array();
                    foreach ($files as $img)
                    {
                        $filename = $img->name;
                        $img->saveAs('upload/images' . '/' . $filename);
                    }
                }
            }
            
            if(isset($_POST['yt0']))
            {
                $brand = ModelCategory::model()->findAll('category_id = :id', array(':id'=>$id));
                $arr = array();
                foreach($brand as $item)
                {
                    $arr[] = $item->brand_id;
                }
                $criteria = new CDbCriteria;
                $criteria->addInCondition('brand_id', $arr);
        	    $model = Models::model()->findAll($criteria);
                foreach($model as $item)
                {
                    $array_model_id[] = $item->id;
                }
                if(isset($array_model_id[0]))
                {
                    $criteria_model_id = new CDbCriteria;
                    $criteria_model_id->addInCondition('model_id', $array_model_id);
                    
                    $characteristics = CharacteristicValue::model()->findAll($criteria_model_id);
                    
            	    $file_name = 'export.csv'; // название файла
                    $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
                    $csv_file = array('Артикул', 
                                      'Название бренда', 
                                      'Название модели', 
                                      'Цена', 
                                      'Старая цена', 
                                      'Главная фотография', 
                                      'Другие фотографии', 
                                      'Количество',
                                      'Описание', 
                                      'Сопутствующие товары', 
                                      'Топ продаж', 
                                      'Акция', 
                                      'Новинка', 
                                      'Лучшая цена',
                                      'Тип',
                                      'Форм-фактор',
                                      'Операционная система',
                                      'Количество SIM-карт',
                                      'Стандарты связи',
                                      'Формат SIM-карты',
                                      'Материал корпуса',
                                      'Степень защиты',
                                      'Цвет',
                                      'Диагональ экрана',
                                      'Разрешение экрана',
                                      'Тип экрана',
                                      'Количество цветов экрана',
                                      'Тип процессора',
                                      'Количесво ядер',
                                      'Частота процессора',
                                      'Основная камера',
                                      'Фронтальная камера',
                                      'Встроенная вспышка',
                                      'Оперативная память',
                                      'Встроенная память',
                                      'Поддержка карт памяти',
                                      'Wi-Fi',
                                      'Bluetooth',
                                      'GPS',
                                      'NFC',
                                      'MP3-проигрыватель',
                                      'FM-тюнер',
                                      'SMS/MMS/Email',
                                      'Интерфейс',
                                      'Разъем для наушников',
                                      'Емкость аккумулятора',
                                      'Размеры и вес',
                                      'Комплектация',
                                      'Гарантийный срок',
                                      'Возврат и обмен товара',
                                      'Дополнительно');
                    $csv_file = explode( "^", iconv( 'UTF-8', 'Windows-1251', implode( "^", $csv_file ) ) );                 
                    fputcsv($file, $csv_file, ";"); // записываем в файл заголовки
                    foreach($model as $item)
                    {
                        $csv_file = array($item->vendor_code, 
                                          $item->brandModel->brand, 
                                          $item->model_name, 
                                          $item->price, 
                                          $item->old_price, 
                                          $item->photo, 
                                          (is_array(json_decode($item->photo_other))?implode(', ', json_decode($item->photo_other)):json_decode($item->photo_other)), 
                                          $item->quantity, 
                                          $item->description,                                      
                                          (is_array(json_decode($item->accessories))?implode(', ', json_decode($item->accessories)):$item->accessories), 
                                          $item->top, 
                                          $item->promotion, 
                                          $item->novelty, 
                                          $item->bestPrice);
                                          
                        foreach($characteristics as $characteristic)
                        {          
                            if ($item->id == $characteristic->model_id)
                            {
                                if(isset($characteristic->value))
                                {
                                    array_push($csv_file, $characteristic->value);
                                }
                                else
                                {
                                    array_push($csv_file, "");
                                }
                                
                            }
                              
                        }
                        
                        $csv_file = explode( "^", iconv( 'UTF-8', 'Windows-1251', implode( "^", $csv_file ) ) );                  
                        fputcsv($file, $csv_file, ";"); // записываем в файл строки
                    }
                    
                    fclose($file); // закрываем файл
                    
                    // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
                    header('Content-type: application/csv'); // указываем, что это csv документ
                    header("Content-Disposition: attachment; filename=".$file_name); // указываем файл, с которым будем работать
                    readfile($file_name); // считываем файл
                    unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера
                    Yii::app()->end();
                }
                else{
                    Yii::app()->user->setFlash('status','База пустая!');
                }
                
            }
            
            // загрузка из файла
            
            if(isset($_POST['CsvUpload']))
            {
                $brand = ModelCategory::model()->findAll('category_id = :id', array(':id'=>$id));
                $arr = array();
                foreach($brand as $item)
                {
                    $arr[] = $item->brand_id;
                }
                $criteria = new CDbCriteria;
                $criteria->addInCondition('id', $arr);
        	    $model = Brand::model()->findAll($criteria);
                
                
                // получаем данные из формы
                if ($file = CUploadedFile::getInstance($models, 'csv'))
                {
                    $extension = strtolower($file->extensionName);
                    $filename = 'import_csv';
                    $basename = $filename . '.' . $extension;
                    $file->saveAs('upload/csv' . '/' . $basename);
                    $path_csv = Yii::app()->request->baseUrl.'upload/csv/'.$basename;
                    if (($handle = fopen($path_csv, "r")) !== false) {
                        
                      
                      $modelCharacteristicValue = new CharacteristicValue;
                      $modelCharacteristics = Characteristics::model()->findAll('category_id = :id', array(':id' => $id));
                      $modelModelsOld = Models::model()->findAll();
                      
                      $counter = 0;
                      while (($data = fgetcsv($handle, 5000, ";")) !== false) 
                      {
                        $brand_tel = iconv('Windows-1251', 'UTF-8', $data[1]);
                        $counter++; 
                        if($counter == 1)
                            continue;
                        
                        // если есть такой бренд, то продолжаем
                        $modelModels = new Models;    
                        $i = 0;
                        foreach($model as $item)
                        {
                            if(trim(strtolower($item->brand)) == trim(strtolower($brand_tel)))
                            {
                                $i = 1;
                                $modelModels->brand_id = $item->id;
                                $brand_id_update = $item->id;
                            }
                        }
                        // если бренда нет, идем на следующую итерацию
                        if($i == 0)
                            continue; 
                        
                        $reset_while = 0;
                        
                        foreach($modelModelsOld as $item)
                        {
                            $vendor = iconv('Windows-1251', 'UTF-8', $data[0]);
                            //если запись уже есть в базе, обновляем
                            if($item->vendor_code == $vendor)
                            {
                                $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                                
                                $cena=str_replace(",",'.',$data[3]);
                                $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                                
                                $old_cena=str_replace(",",'.',$data[4]); 
                                $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                                
                                //$accessories = iconv('Windows-1251', 'UTF-8', $data[9]);
                                $model_name = iconv('Windows-1251', 'UTF-8', $data[2]);
                                
                                $photo_other = explode(',', mb_convert_encoding($data[6], 'UTF-8'));
                                $photo_other_arr = array();
                                foreach($photo_other as $item)
                                {
                                    $photo_other_arr[] = trim($item);
                                }
                                
                                $acces = explode(',', mb_convert_encoding($data[9], 'UTF-8'));
                                $accessories = array();
                                foreach($acces as $item)
                                {
                                    $accessories[] = trim($item);
                                }
                                
                                
                                 
                                $sql1[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$brand_id_update.'"';
                                $vendor1[] = '"'.$vendor.'"';
                                $sql2[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$model_name.'"';
                                $sql3[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$cena.'"';
                                $sql4[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$old_cena.'"'; 
                                $sql5[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$data[5].'"';
                                $sql6[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.json_encode($photo_other_arr).'"'; 
                                $sql7[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$data[7].'"';
                                $sql8[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$description.'"';
                                $sql9[] = 'WHEN `vendor_code`="'.$vendor.'" THEN "'.json_encode($accessories).'"';
                                $sql10[]= 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$data[10].'"'; 
                                $sql11[]= 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$data[11].'"';
                                $sql12[]= 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$data[12].'"';
                                $sql13[]= 'WHEN `vendor_code`="'.$vendor.'" THEN "'.$data[13].'"';
                                
                                                                 
                                                                  /*`model_name`="'.$model_name.'", 
                                                                  `price`="'.$cena.'", 
                                                                  `old_price`="'.$old_cena.'", 
                                                                  `photo`="'.$data[5].'", 
                                                                  `photo_other`="'.json_encode($photo_other_arr).'", 
                                                                  `quantity`="'.$data[7].'", 
                                                                  `description`="'.$description.'", 
                                                                  `accessories`="'.json_encode($accessories).'", 
                                                                  `top`="'.$data[10].'", 
                                                                  `promotion`="'.$data[11].'", 
                                                                  `novelty`="'.$data[12].'", 
                                                                  `bestPrice`="'.$data[13].'"
                                WHERE vendor_code = '.$vendor.''*/
                                /*
                                Models::model()->updateAll(array('vendor_code'=>$vendor,
                                                                 'brand_id'=>$brand_id_update,
                                                                 'model_name'=>$model_name,
                                                                 'price'=>$cena,
                                                                 'old_price'=>$old_cena,
                                                                 'photo'=>$data[5],
                                                                 'photo_other'=>json_encode($photo_other_arr),
                                                                 'quantity'=>$data[7],
                                                                 'description'=>$description,
                                                                 'accessories'=>json_encode($accessories),
                                                                 'top'=>$data[10],
                                                                 'promotion'=>$data[11],
                                                                 'novelty'=>$data[12],
                                                                 'bestPrice'=>$data[13]
                                                                 ), 
                                                           'vendor_code = :code', 
                                                           array(':code'=>$vendor));*/
                                                           
                                
                                $id_update = $item->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0 && isset($data[$j]))
                                    {
                                        
                                        $value = $data[$j] != '' ? iconv('Windows-1251', 'UTF-8', $data[$j]) : '';
                                        $sql_char[] = 'UPDATE `cms_characteristicValue` SET `value`="'.$value.'"
                                                       WHERE model_id = "'.$id_update.'" AND characteristic_id = "'.$items->id.'"';
                                        /*
                                        CharacteristicValue::model()->updateAll(array('value'=>$value), 
                                                                                'model_id = :id_update AND characteristic_id = :characteristic_id',
                                                                                array(':id_update'=>$id_update, ':characteristic_id'=>$items->id)); */
                                    
                                    $j++;   
                                    }
                                    if($j == 51){
                                        break;
                                    }
                                    
                                }                          
                                $reset_while = 1;                         
                            }                        
                        }
                        // если обновили, пропускаем итерацию
                        if($reset_while == 1)
                            continue;
                        // если нет, записываем новые данные
                        
                        $vendor = iconv('Windows-1251', 'UTF-8', $data[0]);
                        $model_name = iconv('Windows-1251', 'UTF-8', $data[2]);
                        
                        $modelModels->vendor_code = $vendor;
                        $modelModels->model_name = $model_name;
                        
                        $cena=str_replace(",",'.',$data[3]);
                        $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                        
                        $old_cena=str_replace(",",'.',$data[4]); 
                        $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                        
                        $photo_other = explode(',', mb_convert_encoding($data[6], 'UTF-8'));
                        $photo_other_arr = array();
                        foreach($photo_other as $item)
                        {
                            $photo_other_arr[] = trim($item);
                        }
                        
                        $acces = explode(',', mb_convert_encoding($data[9], 'UTF-8'));
                        $accessories = array();
                        foreach($acces as $item)
                        {
                            $accessories[] = trim($item);
                        }
                        
                        $modelModels->price = $cena;
                        $modelModels->old_price = $old_cena;
                        $modelModels->photo = $data[5];
                        $modelModels->photo_other = json_encode($photo_other_arr);
                        $modelModels->quantity = $data[7];
                        $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode($accessories);
                        $modelModels->top = $data[10];
                        $modelModels->promotion = $data[11];
                        $modelModels->novelty = $data[12];
                        $modelModels->bestPrice = $data[13];
                        
                        if($modelModels->save(false))
                        {
                            $id_model = $modelModels->id; 
                            $j = 14;
                            
                            foreach($modelCharacteristics as $k=>$item)
                            {
                                if($item->parent_id != 0 && isset($data[$j]))
                                {
                                    $modelCharacteristicValue->id = false;
                                    $modelCharacteristicValue->isNewRecord = true;
                                    $value = $data[$j] ? iconv('Windows-1251', 'UTF-8', $data[$j]) : '';
                                    $modelCharacteristicValue->value = $value;
                                    $modelCharacteristicValue->characteristic_id = $item->id;
                                    $modelCharacteristicValue->model_id = $id_model;
                                    if($modelCharacteristicValue->save(false))
                                    {
                                        $j++;
                                    }    
                                }
                                if($j == 51)
                                    break;
                                }
                                
                            }   
                        
                        
                      }
                      fclose($handle);
                      unlink($path_csv);
                    }
                    $sql1 =  implode(' ', $sql1);
                    $sql2 =  implode(' ', $sql2);
                    $sql3 =  implode(' ', $sql3);
                    $sql4 =  implode(' ', $sql4);
                    $sql5 =  implode(' ', $sql5);
                    $sql6 =  implode(' ', $sql6);
                    $sql7 =  implode(' ', $sql7);
                    $sql8 =  implode(' ', $sql8);
                    $sql9 =  implode(' ', $sql9);
                    $sql10 = implode(' ', $sql10);
                    $sql11 = implode(' ', $sql11);
                    $sql12 = implode(' ', $sql12);
                    $sql13 = implode(' ', $sql13);
                    
                    
                    $vendor1 = implode(', ', $vendor1);
                    $sql_brand_id =    'UPDATE `cms_models` SET brand_id = CASE '.$sql1.' END WHERE `vendor_code` IN ('.$vendor1.')';
                    $sql_model_name =  'UPDATE `cms_models` SET model_name = CASE '.$sql2.' END';
                    $sql_price =       'UPDATE `cms_models` SET price = CASE '.$sql3.' END';
                    $sql_old_price =   'UPDATE `cms_models` SET old_price = CASE '.$sql4.' END';
                    $sql_photo =       'UPDATE `cms_models` SET photo = CASE '.$sql5.' END';
                    $sql_photo_other = 'UPDATE `cms_models` SET photo_other = CASE '.$sql6.' END';
                    $sql_quantity =    'UPDATE `cms_models` SET quantity = CASE '.$sql7.' END';
                    $sql_description = 'UPDATE `cms_models` SET description = CASE '.$sql8.' END';
                    $sql_accessories = 'UPDATE `cms_models` SET accessories = CASE '.$sql9.' END';
                    $sql_top =         'UPDATE `cms_models` SET top = CASE '.$sql10.' END';
                    $sql_promotion =   'UPDATE `cms_models` SET promotion = CASE '.$sql11.' END';
                    $sql_novelty =     'UPDATE `cms_models` SET novelty = CASE '.$sql12.' END';
                    $sql_bestPrice =   'UPDATE `cms_models` SET bestPrice = CASE '.$sql3.' END';
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_brand_id)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_model_name)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_price)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_old_price)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_photo)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_photo_other)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_quantity)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_description)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_accessories)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_top)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_promotion)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_novelty)->queryAll();
                    
                    $connection = Yii::app()->db;
                    $connection->createCommand($sql_bestPrice)->queryAll();
                    
                    
                    
                    
                    // сообщение о завершении загрузки
                    Yii::app()->user->setFlash('status','Файл загружен, данные добавлены!');
                    
                      
                }
            }
       }
	   
       if($id == 2)
       {
            if(isset($_POST['CsvUpload']['photo_img']))
            {
                
                if ($files = CUploadedFile::getInstances($models, 'photo_img'))
                {
                    $array = array();
                    foreach ($files as $img)
                    {
                        $filename = $img->name;
                        //$filename = mb_convert_encoding($filename, 'Windows-1251', 'UTF-8');
                        $img->saveAs('upload/images' . '/' . $filename);
                    }
                }
            }
            
            if(isset($_POST['yt0']))
            {
                $brand = ModelCategory::model()->findAll('category_id = :id', array(':id'=>$id));
                $arr = array();
                foreach($brand as $item)
                {
                    $arr[] = $item->brand_id;
                }
                $criteria = new CDbCriteria;
                $criteria->addInCondition('brand_id', $arr);
        	    $model = Models::model()->findAll($criteria);
                foreach($model as $item)
                {
                    $array_model_id[] = $item->id;
                }
                
                if(isset($array_model_id[0]))
                {
                    $criteria_model_id = new CDbCriteria;
                    $criteria_model_id->addInCondition('model_id', $array_model_id);
                    
                    $characteristics = CharacteristicValue::model()->findAll($criteria_model_id);
                    
            	    $file_name = 'export.csv'; // название файла
                    $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
                    $csv_file = array('Артикул', 
                                      'Название бренда', 
                                      'Название модели', 
                                      'Цена', 
                                      'Старая цена', 
                                      'Главная фотография', 
                                      'Другие фотографии', 
                                      'Количество',
                                      'Описание', 
                                      'Сопутствующие товары', 
                                      'Топ продаж', 
                                      'Акция', 
                                      'Новинка', 
                                      'Лучшая цена',
                                      'Тип',
                                      'Операционная система',
                                      'Количество SIM-карт',
                                      'Стандарты связи',
                                      'Материал корпуса',
                                      'Цвет',
                                      'Диагональ экрана',
                                      'Разрешение экрана',
                                      'Тип экрана',
                                      'Тип процессора',
                                      'Количесво ядер',
                                      'Частота процессора',
                                      'Основная камера',
                                      'Фронтальная камера',
                                      'Оперативная память',
                                      'Встроенная память',
                                      'Поддержка карт памяти',
                                      'Wi-Fi',
                                      'Bluetooth',
                                      'GPS',
                                      'NFC',
                                      'Интерфейс',
                                      'Разъем для наушников',
                                      'Емкость аккумулятора',
                                      'Размеры и вес',
                                      'Комплектация',
                                      'Гарантийный срок',
                                      'Возврат и обмен товара',
                                      'Дополнительно');
                    $csv_file = explode( "^", iconv( 'UTF-8', 'Windows-1251', implode( "^", $csv_file ) ) );                 
                    fputcsv($file, $csv_file, ";"); // записываем в файл заголовки
                    foreach($model as $item)
                    {
                        $csv_file = array($item->vendor_code, 
                                          $item->brandModel->brand, 
                                          $item->model_name, 
                                          $item->price, 
                                          $item->old_price, 
                                          $item->photo, 
                                          (is_array(json_decode($item->photo_other))?implode(', ', json_decode($item->photo_other)):json_decode($item->photo_other)), 
                                          $item->quantity, 
                                          $item->description,                                      
                                          (is_array(json_decode($item->accessories))?implode(', ', json_decode($item->accessories)):$item->accessories), 
                                          $item->top, 
                                          $item->promotion, 
                                          $item->novelty, 
                                          $item->bestPrice);
                                          
                        foreach($characteristics as $characteristic)
                        {          
                            if ($item->id == $characteristic->model_id)
                            {
                                if(isset($characteristic->value))
                                {
                                    array_push($csv_file, $characteristic->value);
                                }
                                else
                                {
                                    array_push($csv_file, "");
                                }
                                
                            }
                              
                        }
                        
                        $csv_file = explode( "^", iconv( 'UTF-8', 'Windows-1251', implode( "^", $csv_file ) ) );                  
                        fputcsv($file, $csv_file, ";"); // записываем в файл строки
                    }
                    
                    fclose($file); // закрываем файл
                    
                    // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
                    header('Content-type: application/csv'); // указываем, что это csv документ
                    header("Content-Disposition: attachment; filename=".$file_name); // указываем файл, с которым будем работать
                    readfile($file_name); // считываем файл
                    unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера
                    Yii::app()->end();
                }
                else
                {
                    Yii::app()->user->setFlash('status','База пустая!');
                }
                
            }
            
            
            if(isset($_POST['CsvUpload']))
            {
                $brand = ModelCategory::model()->findAll('category_id = :id', array(':id'=>$id));
                $arr = array();
                foreach($brand as $item)
                {
                    $arr[] = $item->brand_id;
                }
                $criteria = new CDbCriteria;
                $criteria->addInCondition('id', $arr);
        	    $model = Brand::model()->findAll($criteria);
                
                
                // получаем данные из формы
                if ($file = CUploadedFile::getInstance($models, 'csv'))
                {
                    $extension = strtolower($file->extensionName);
                    $filename = 'import_csv';
                    $basename = $filename . '.' . $extension;
                    $file->saveAs('upload/csv' . '/' . $basename);
                    $path_csv = Yii::app()->request->baseUrl.'upload/csv/'.$basename;
                    if (($handle = fopen($path_csv, "r")) !== false) {
                        
                      
                      $modelCharacteristicValue = new CharacteristicValue;
                      $modelCharacteristics = Characteristics::model()->findAll('category_id = :id', array(':id' => $id));
                      $modelModelsOld = Models::model()->findAll();
                      
                      $counter = 0;
                      while (($data = fgetcsv($handle, 5000, ";")) !== false) 
                      {
                        $brand_pl = iconv('Windows-1251', 'UTF-8', $data[1]);
                        $counter++; 
                        if($counter == 1)
                            continue;
                        
                        // если есть такой бренд, то продолжаем
                        $modelModels = new Models;    
                        $i = 0;
                        foreach($model as $item)
                        {
                            if(trim(strtolower($item->brand)) == trim(strtolower($brand_pl)))
                            {
                                $i = 1;
                                $modelModels->brand_id = $item->id;
                                $brand_id_update = $item->id;
                            }
                        }
                        // если бренда нет, идем на следующую итерацию
                        if($i == 0)
                            continue; 
                        
                        $reset_while = 0;
                        
                        foreach($modelModelsOld as $item)
                        {
                            //если запись уже есть в базе, обновляем
                            $vendor = iconv('Windows-1251', 'UTF-8', $data[0]);
                            if($item->vendor_code == $vendor)
                            {
                                
                                                                
                                $cena=str_replace(",",'.',$data[3]);
                                $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                                
                                $old_cena=str_replace(",",'.',$data[4]); 
                                $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                                
                                $accessories = iconv('Windows-1251', 'UTF-8', $data[9]);
                                $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                                
                                $photo_other = explode(',', mb_convert_encoding($data[6], 'UTF-8'));
                                $photo_other_arr = array();
                                foreach($photo_other as $item)
                                {
                                    $photo_other_arr[] = trim($item);
                                }
                                
                                $acces = explode(',', mb_convert_encoding($data[9], 'UTF-8'));
                                $accessories = array();
                                foreach($acces as $item)
                                {
                                    $accessories[] = trim($item);
                                }
                                
                                Models::model()->updateAll(array('vendor_code'=>$vendor,
                                                                 'brand_id'=>$brand_id_update,
                                                                 'model_name'=>iconv('Windows-1251', 'UTF-8', $data[2]),
                                                                 'price'=>$cena,
                                                                 'old_price'=>$old_cena,
                                                                 'photo'=>$data[5],
                                                                 'photo_other'=>json_encode($photo_other),
                                                                 'quantity'=>$data[7],
                                                                 'description'=>$description,
                                                                 'accessories'=>json_encode($accessories),
                                                                 'top'=>(isset($data[10])?$data[10]:''),
                                                                 'promotion'=>(isset($data[10])?$data[11]:''),
                                                                 'novelty'=>(isset($data[10])?$data[12]:''),
                                                                 'bestPrice'=>(isset($data[10])?$data[13]:'')
                                                                 ), 
                                                           'vendor_code = :code', 
                                                           array(':code'=>$data[0]));
                                                           
                                
                                $id_update = $item->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0 && isset($data[$j]))
                                    {
                                        $value = iconv('Windows-1251', 'UTF-8', $data[$j]);
                                        CharacteristicValue::model()->updateAll(array('value'=>$value), 
                                                                                'model_id = :id_update AND characteristic_id = :characteristic_id',
                                                                                array(':id_update'=>$id_update, ':characteristic_id'=>$items->id));
                                    
                                    $j++;   
                                    }
                                    if($j == 44){
                                        break;
                                    }
                                    
                                }                          
                                $reset_while = 1;                         
                            }                        
                        }
                        // если обновили, пропускаем итерацию
                        if($reset_while == 1)
                            continue;
                        // если нет, записываем новые данные 
                        
                        $vendor = iconv('Windows-1251', 'UTF-8', $data[0]);
                        $modelModels->vendor_code = $vendor;
                        $modelModels->model_name = iconv('Windows-1251', 'UTF-8', $data[2]);
                        
                        $cena=str_replace(",",'.',$data[3]);
                        $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                        
                        $old_cena=str_replace(",",'.',$data[4]); 
                        $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                        
                        $photo = iconv('Windows-1251', 'UTF-8', $data[5]);
                        $photo_other = explode(',', mb_convert_encoding($data[6], 'UTF-8'));
                        $photo_other_arr = array();
                        foreach($photo_other as $item)
                        {
                            $photo_other_arr[] = trim($item);
                        }
                        
                        $acces = explode(',', mb_convert_encoding($data[9], 'UTF-8'));
                        $accessories = array();
                        foreach($acces as $item)
                        {
                            $accessories[] = trim($item);
                        }
                           
                        
                        $modelModels->price = $cena;
                        $modelModels->old_price = $old_cena;
                        $modelModels->photo = $photo;
                        $modelModels->photo_other = json_encode($photo_other_arr);
                        $modelModels->quantity = $data[7];
                        $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode($accessories);
                        $modelModels->top = (isset($data[10])?$data[10]:'');
                        $modelModels->promotion = (isset($data[11])?$data[11]:'');
                        $modelModels->novelty = (isset($data[12])?$data[12]:'');
                        $modelModels->bestPrice = (isset($data[13])?$data[13]:'');
                        
                        if($modelModels->save(false))
                        {
                            $id_model = $modelModels->id; 
                            $j = 14;
                            
                            foreach($modelCharacteristics as $k=>$item)
                            {
                                if($item->parent_id != 0 && isset($data[$j]))
                                {
                                    $modelCharacteristicValue->id = false;
                                    $modelCharacteristicValue->isNewRecord = true;
                                    $value = iconv('Windows-1251', 'UTF-8', $data[$j]);
                                    $modelCharacteristicValue->value = $value;
                                    $modelCharacteristicValue->characteristic_id = $item->id;
                                    $modelCharacteristicValue->model_id = $id_model;
                                    if($modelCharacteristicValue->save(false))
                                    {
                                        $j++;
                                    }    
                                }
                                if($j == 44)
                                    break;
                                }
                                
                            }   
                        
                        
                      }
                      fclose($handle);
                      unlink($path_csv);
                    }
                    // сообщение о завершении загрузки
                    Yii::app()->user->setFlash('status','Файл загружен, данные добавлены!');
                    
                      
                }
            }
       } 
       
       if(isset($_POST['del']))
       {
            $brand = ModelCategory::model()->findAll('category_id = :id', array(':id'=>$id));
            $arr = array();
            foreach($brand as $item)
            {
                $arr[] = $item->brand_id;
            }
            $criteria = new CDbCriteria;
            $criteria->addInCondition('brand_id', $arr);
            
            $model = Models::model()->findAll($criteria);
            
            $del = Models::model()->deleteAll($criteria);
            
            $arr_val = array();
            foreach($model as $item)
            {
                $arr_val[] = $item->id;
            }
            
            $criteria_val = new CDbCriteria;
            $criteria_val->addInCondition('model_id', $arr_val);
            $val = CharacteristicValue::model()->deleteAll($criteria_val);
            Yii::app()->user->setFlash('status','Данные удалены!');
            $this->refresh();
       }
        
		$this->render('index', array(
            'id'=>$id,
            'models' => $models,
        ));
	}

}
	