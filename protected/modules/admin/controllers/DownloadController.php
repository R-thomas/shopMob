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
                        
                        foreach($modelModelsOld as $itemid)
                        {
                            $vendor = iconv('Windows-1251', 'UTF-8', (isset($data[0]) ? $data[0] : '') );
                            //если запись уже есть в базе, обновляем
                            if($itemid->vendor_code == $vendor)
                            {
                                $description = iconv('Windows-1251', 'UTF-8', (isset($data[8]) ? $data[8] : '') );
                                
                                $cena=str_replace(",",'.',(isset($data[3]) ? $data[3] : '') );
                                $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                                
                                $old_cena=str_replace(",",'.',(isset($data[4]) ? $data[4] : '') ); 
                                $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                                
                                //$accessories = iconv('Windows-1251', 'UTF-8', $data[9]);
                                $model_name = iconv('Windows-1251', 'UTF-8', (isset($data[2]) ? $data[2] : '') );
                                
                                $photo_other = explode(',', mb_convert_encoding((isset($data[6]) ? $data[6] : '') , 'UTF-8'));
                                $photo_other_arr = array();
                                foreach($photo_other as $item)
                                {
                                    $photo_other_arr[] = trim($item);
                                }
                                
                                $acces = explode(',', mb_convert_encoding((isset($data[9]) ? $data[9] : '') , 'UTF-8'));
                                $accessories = array();
                                foreach($acces as $item)
                                {
                                    $accessories[] = trim($item);
                                }
                                
                                
                                
                                $sql[] = '(\''.$vendor.'\', 
                                           \''.$model_name.'\', 
                                           \'"'.$cena.'\', 
                                           \''.$old_cena.'\', 
                                           \''.$brand_id_update.'\', 
                                           \''.(isset($data[5]) ? $data[5] : '') .'\', 
                                           \''.(($photo_other_arr[0] != '')?json_encode($photo_other_arr):'').'\',
                                           \''.(isset($data[7]) ? $data[7] : '') .'\',
                                           \''.$description.'\',
                                           \''.json_encode($accessories).'\',
                                           \''.(isset($data[10]) ? $data[10] : '') .'\',
                                           \''.(isset($data[11]) ? $data[11] : '') .'\',
                                           \''.(isset($data[12]) ? $data[12] : '') .'\',
                                           \''.(isset($data[13]) ? $data[13] : '') .'\'
                                           )';
                                
                                
                                                           
                                
                                $id_update = $itemid->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0 && isset($data[$j]))
                                    {
                                        
                                        $value = $data[$j] != '' ? iconv('Windows-1251', 'UTF-8', $data[$j]) : '';
                                        
                                        $sql_char[] = '(\''.$value.'\', \''.$items->id.'\', \''.$id_update.'\')';               
                                        
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
                        
                        $vendor = iconv('Windows-1251', 'UTF-8', (isset($data[0]) ? $data[0] : '') );
                        $model_name = iconv('Windows-1251', 'UTF-8', (isset($data[2]) ? $data[2] : '') );
                        
                        $modelModels->vendor_code = $vendor;
                        $modelModels->model_name = $model_name;
                        
                        $cena=str_replace(",",'.',(isset($data[3]) ? $data[3] : '') );
                        $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                        
                        $old_cena=str_replace(",",'.',(isset($data[4]) ? $data[4] : '') ); 
                        $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                        
                        $photo_other = explode(',', mb_convert_encoding((isset($data[6]) ? $data[6] : '') , 'UTF-8'));
                        $photo_other_arr = array();
                        foreach($photo_other as $item)
                        {
                            $photo_other_arr[] = trim($item);
                        }
                        
                        $acces = explode(',', mb_convert_encoding((isset($data[9]) ? $data[9] : '') , 'UTF-8'));
                        $accessories = array();
                        foreach($acces as $item)
                        {
                            $accessories[] = trim($item);
                        }
                        
                        
                        $modelModels->price = $cena;
                        $modelModels->old_price = $old_cena;
                        $modelModels->photo = (isset($data[5]) ? $data[5] : '') ;
                        $modelModels->photo_other = (($photo_other_arr[0] != '')?json_encode($photo_other_arr):'');
                        $modelModels->quantity = (isset($data[7]) ? $data[7] : '') ;
                        $description = iconv('Windows-1251', 'UTF-8', (isset($data[8]) ? $data[8] : '') );
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode($accessories);
                        $modelModels->top = (isset($data[10]) ? $data[10] : '') ;
                        $modelModels->promotion = (isset($data[11]) ? $data[11] : '') ;
                        $modelModels->novelty = (isset($data[12]) ? $data[12] : '') ;
                        $modelModels->bestPrice = (isset($data[13]) ? $data[13] : '') ;
                        
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
                    
                    if(isset($sql[0]))
                    {
                        $sql_implode = implode(', ', $sql);
                        $sql_query_models = 'INSERT INTO cms_temp_models (vendor_code,
                                                                          model_name,
                                                                          price,
                                                                          old_price,
                                                                          brand_id,
                                                                          photo,
                                                                          photo_other,
                                                                          quantity,
                                                                          description,
                                                                          accessories,
                                                                          top,
                                                                          promotion,
                                                                          novelty,
                                                                          bestPrice
                                                                          ) VALUES '.$sql_implode;                                                 
                        $connection = Yii::app()->db;
                        $connection->createCommand($sql_query_models)->execute();
                        
                        $sql_query_update = 'update cms_models u
                        inner join cms_temp_models s on
                            u.vendor_code = s.vendor_code
                        set u.model_name = s.model_name,
                            u.price = s.price,
                            u.old_price = s.old_price,
                            u.brand_id = s.brand_id,
                            u.photo = s.photo,
                            u.photo_other = s.photo_other,
                            u.quantity = s.quantity,
                            u.description = s.description,
                            u.accessories = s.accessories,
                            u.top = s.top,
                            u.promotion = s.promotion,
                            u.novelty = s.novelty,
                            u.bestPrice = s.bestPrice';
                        $connection->createCommand($sql_query_update)->execute();
                        $connection->createCommand()->truncateTable('cms_temp_models');
                        
                        if(isset($sql_char))
                        {
                            $sql_implode_char = implode(', ', $sql_char);
                        
                            $sql_query_models = 'INSERT INTO cms_temp_characteristicValue (value, characteristic_id, model_id) 
                                                 VALUES '.$sql_implode_char; 
                                                 
                            $connection = Yii::app()->db;
                            $connection->createCommand($sql_query_models)->execute(); 
                            
                            $sql_query_update_char = 'UPDATE cms_characteristicValue u
                            INNER JOIN cms_temp_characteristicValue s ON
                                (u.characteristic_id = s.characteristic_id) AND (u.model_id = s.model_id)
                            SET u.value = s.value';
                            $connection->createCommand($sql_query_update_char)->execute();
                            $connection->createCommand()->truncateTable('cms_temp_characteristicValue'); 
                        }  
                    }
                                      
                    
                    
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
                        
                        foreach($modelModelsOld as $itemid)
                        {
                            //если запись уже есть в базе, обновляем
                            $vendor = iconv('Windows-1251', 'UTF-8', (isset($data[0]) ? $data[0] : '') );
                            //если запись уже есть в базе, обновляем
                            if($itemid->vendor_code == $vendor)
                            {
                                $description = iconv('Windows-1251', 'UTF-8', (isset($data[8]) ? $data[8] : '') );
                                
                                $cena=str_replace(",",'.',(isset($data[3]) ? $data[3] : '') );
                                $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                                
                                $old_cena=str_replace(",",'.',(isset($data[4]) ? $data[4] : '') ); 
                                $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                                
                                //$accessories = iconv('Windows-1251', 'UTF-8', $data[9]);
                                $model_name = iconv('Windows-1251', 'UTF-8', (isset($data[2]) ? $data[2] : '') );
                                
                                $photo_other = explode(',', mb_convert_encoding((isset($data[6]) ? $data[6] : '') , 'UTF-8'));
                                $photo_other_arr = array();
                                foreach($photo_other as $item)
                                {
                                    $photo_other_arr[] = trim($item);
                                }
                                
                                $acces = explode(',', mb_convert_encoding((isset($data[9]) ? $data[9] : '') , 'UTF-8'));
                                $accessories = array();
                                foreach($acces as $item)
                                {
                                    $accessories[] = trim($item);
                                }
                                
                                
                                
                                $sql[] = '(\''.$vendor.'\', 
                                           \''.$model_name.'\', 
                                           \'"'.$cena.'\', 
                                           \''.$old_cena.'\', 
                                           \''.$brand_id_update.'\', 
                                           \''.(isset($data[5]) ? $data[5] : '') .'\', 
                                           \''.(($photo_other_arr[0] != '')?json_encode($photo_other_arr):'').'\',
                                           \''.(isset($data[7]) ? $data[7] : '') .'\',
                                           \''.$description.'\',
                                           \''.json_encode($accessories).'\',
                                           \''.(isset($data[10]) ? $data[10] : '') .'\',
                                           \''.(isset($data[11]) ? $data[11] : '') .'\',
                                           \''.(isset($data[12]) ? $data[12] : '') .'\',
                                           \''.(isset($data[13]) ? $data[13] : '') .'\'
                                           )';
                                                           
                                
                                $id_update = $itemid->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0 && isset($data[$j]))
                                    {
                                        $value = iconv('Windows-1251', 'UTF-8', $data[$j]);
                                        
                                        $sql_char[] = '(\''.$value.'\', \''.$items->id.'\', \''.$id_update.'\')'; 
                                    
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
                        
                        $vendor = iconv('Windows-1251', 'UTF-8', (isset($data[0]) ? $data[0] : '') );
                        $model_name = iconv('Windows-1251', 'UTF-8', (isset($data[2]) ? $data[2] : '') );
                        
                        $modelModels->vendor_code = $vendor;
                        $modelModels->model_name = $model_name;
                        
                        $cena=str_replace(",",'.',(isset($data[3]) ? $data[3] : '') );
                        $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                        
                        $old_cena=str_replace(",",'.',(isset($data[4]) ? $data[4] : '') ); 
                        $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                        
                        $photo_other = explode(',', mb_convert_encoding((isset($data[6]) ? $data[6] : '') , 'UTF-8'));
                        $photo_other_arr = array();
                        foreach($photo_other as $item)
                        {
                            $photo_other_arr[] = trim($item);
                        }
                        
                        $acces = explode(',', mb_convert_encoding((isset($data[9]) ? $data[9] : '') , 'UTF-8'));
                        $accessories = array();
                        foreach($acces as $item)
                        {
                            $accessories[] = trim($item);
                        }
                        
                        
                        $modelModels->price = $cena;
                        $modelModels->old_price = $old_cena;
                        $modelModels->photo = (isset($data[5]) ? $data[5] : '') ;
                        $modelModels->photo_other = (($photo_other_arr[0] != '')?json_encode($photo_other_arr):'');
                        $modelModels->quantity = (isset($data[7]) ? $data[7] : '') ;
                        $description = iconv('Windows-1251', 'UTF-8', (isset($data[8]) ? $data[8] : '') );
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode($accessories);
                        $modelModels->top = (isset($data[10]) ? $data[10] : '') ;
                        $modelModels->promotion = (isset($data[11]) ? $data[11] : '') ;
                        $modelModels->novelty = (isset($data[12]) ? $data[12] : '') ;
                        $modelModels->bestPrice = (isset($data[13]) ? $data[13] : '') ;
                        
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
                    
                    if(isset($sql[0]))
                    {
                        $sql_implode = implode(', ', $sql);
                        $sql_query_models = 'INSERT INTO cms_temp_models (vendor_code,
                                                                          model_name,
                                                                          price,
                                                                          old_price,
                                                                          brand_id,
                                                                          photo,
                                                                          photo_other,
                                                                          quantity,
                                                                          description,
                                                                          accessories,
                                                                          top,
                                                                          promotion,
                                                                          novelty,
                                                                          bestPrice
                                                                          ) VALUES '.$sql_implode; 
                                                                                                                          
                        $connection = Yii::app()->db;
                        $connection->createCommand($sql_query_models)->execute();
                        
                        $sql_query_update = 'update cms_models u
                        inner join cms_temp_models s on
                            u.vendor_code = s.vendor_code
                        set u.model_name = s.model_name,
                            u.price = s.price,
                            u.old_price = s.old_price,
                            u.brand_id = s.brand_id,
                            u.photo = s.photo,
                            u.photo_other = s.photo_other,
                            u.quantity = s.quantity,
                            u.description = s.description,
                            u.accessories = s.accessories,
                            u.top = s.top,
                            u.promotion = s.promotion,
                            u.novelty = s.novelty,
                            u.bestPrice = s.bestPrice';
                        $connection->createCommand($sql_query_update)->execute();
                        $connection->createCommand()->truncateTable('cms_temp_models');
                        
                        
                        if(isset($sql_char))
                        {
                            $sql_implode_char = implode(', ', $sql_char);
                        
                            $sql_query_models = 'INSERT INTO cms_temp_characteristicValue (value, characteristic_id, model_id) 
                                                 VALUES '.$sql_implode_char; 
                                                 
                            $connection = Yii::app()->db;
                            $connection->createCommand($sql_query_models)->execute(); 
                            
                            $sql_query_update_char = 'UPDATE cms_characteristicValue u
                            INNER JOIN cms_temp_characteristicValue s ON
                                (u.characteristic_id = s.characteristic_id) AND (u.model_id = s.model_id)
                            SET u.value = s.value';
                            $connection->createCommand($sql_query_update_char)->execute();
                            $connection->createCommand()->truncateTable('cms_temp_characteristicValue'); 
                        }
                         
                    }
                    
                    // сообщение о завершении загрузки
                    Yii::app()->user->setFlash('status','Файл загружен, данные добавлены!');
                    
                      
                }
            }
       }
       
       
       
       
       if($id == 3)
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
                                      'Операционная система',
                                      'Материал корпуса',
                                      'Цвет',
                                      'Диагональ дисплея',
                                      'Покрытие экрана',
                                      'Разрешение дисплея',
                                      'Процессор',
                                      'Тип процессора',
                                      'Количество ядер',
                                      'Тип графическго адаптера',
                                      'Объем видеокарты',
                                      'Тип видеокарты',
                                      'Объем оперативной памяти',
                                      'Тип оперативной памяти',
                                      'Объем дисков HDD',
                                      'Объем дисков SSD',
                                      'Оптический привод',
                                      'Веб-камера',
                                      'Микрофон',
                                      'Встроенные динамики',
                                      'WiFi',
                                      'Bluetooth',
                                      'Сетевой адаптер Ethernet',
                                      'USB 2.0',
                                      'USB 3.0',
                                      'VGA',
                                      'HDMI',
                                      'Аудиоразъем',
                                      'Разъем для микрофона',
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
                        $csv_file = array(trim($item->vendor_code), 
                                          trim($item->brandModel->brand), 
                                          trim($item->model_name), 
                                          trim($item->price), 
                                          trim($item->old_price), 
                                          trim($item->photo), 
                                          (is_array(json_decode($item->photo_other))?implode(', ', json_decode($item->photo_other)):json_decode($item->photo_other)), 
                                          trim($item->quantity), 
                                          trim($item->description),                                      
                                          (is_array(json_decode($item->accessories))?implode(', ', json_decode($item->accessories)):$item->accessories), 
                                          trim($item->top), 
                                          trim($item->promotion), 
                                          trim($item->novelty), 
                                          trim($item->bestPrice));
                                          
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
                        
                        foreach($modelModelsOld as $itemid)
                        {
                            $vendor = iconv('Windows-1251', 'UTF-8', (isset($data[0]) ? $data[0] : '') );
                            //если запись уже есть в базе, обновляем
                            if($itemid->vendor_code == $vendor)
                            {
                                $description = iconv('Windows-1251', 'UTF-8', (isset($data[8]) ? $data[8] : '') );
                                
                                $cena=str_replace(",",'.',(isset($data[3]) ? $data[3] : '') );
                                $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                                
                                $old_cena=str_replace(",",'.',(isset($data[4]) ? $data[4] : '') ); 
                                $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                                
                                //$accessories = iconv('Windows-1251', 'UTF-8', $data[9]);
                                $model_name = iconv('Windows-1251', 'UTF-8', (isset($data[2]) ? $data[2] : '') );
                                
                                $photo_other = explode(',', mb_convert_encoding((isset($data[6]) ? $data[6] : '') , 'UTF-8'));
                                $photo_other_arr = array();
                                foreach($photo_other as $item)
                                {
                                    $photo_other_arr[] = trim($item);
                                }
                                
                                $acces = explode(',', mb_convert_encoding((isset($data[9]) ? $data[9] : '') , 'UTF-8'));
                                $accessories = array();
                                foreach($acces as $item)
                                {
                                    $accessories[] = trim($item);
                                }
                                
                                
                                
                                $sql[] = '(\''.$vendor.'\', 
                                           \''.$model_name.'\', 
                                           \'"'.$cena.'\', 
                                           \''.$old_cena.'\', 
                                           \''.$brand_id_update.'\', 
                                           \''.(isset($data[5]) ? $data[5] : '') .'\', 
                                           \''.(($photo_other_arr[0] != '')?json_encode($photo_other_arr):'').'\',
                                           \''.(isset($data[7]) ? $data[7] : '') .'\',
                                           \''.$description.'\',
                                           \''.json_encode($accessories).'\',
                                           \''.(isset($data[10]) ? $data[10] : '') .'\',
                                           \''.(isset($data[11]) ? $data[11] : '') .'\',
                                           \''.(isset($data[12]) ? $data[12] : '') .'\',
                                           \''.(isset($data[13]) ? $data[13] : '') .'\'
                                           )';
                                
                                
                                                           
                                
                                $id_update = $itemid->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0 && isset($data[$j]))
                                    {
                                        
                                        $value = $data[$j] != '' ? iconv('Windows-1251', 'UTF-8', $data[$j]) : '';
                                        
                                        $sql_char[] = '(\''.trim($value).'\', \''.$items->id.'\', \''.$id_update.'\')';               
                                        
                                        $j++;   
                                    }
                                    if($j == 50){
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
                        
                        $vendor = iconv('Windows-1251', 'UTF-8', (isset($data[0]) ? $data[0] : '') );
                        $model_name = iconv('Windows-1251', 'UTF-8', (isset($data[2]) ? $data[2] : '') );
                        
                        $modelModels->vendor_code = $vendor;
                        $modelModels->model_name = $model_name;
                        
                        $cena=str_replace(",",'.',(isset($data[3]) ? $data[3] : '') );
                        $cena=preg_replace("/[^x\d|*\.]/","",$cena);
                        
                        $old_cena=str_replace(",",'.',(isset($data[4]) ? $data[4] : '') ); 
                        $old_cena=preg_replace("/[^x\d|*\.]/","",$old_cena);
                        
                        $photo_other = explode(',', mb_convert_encoding((isset($data[6]) ? $data[6] : '') , 'UTF-8'));
                        $photo_other_arr = array();
                        foreach($photo_other as $item)
                        {
                            $photo_other_arr[] = trim($item);
                        }
                        
                        $acces = explode(',', mb_convert_encoding((isset($data[9]) ? $data[9] : '') , 'UTF-8'));
                        $accessories = array();
                        foreach($acces as $item)
                        {
                            $accessories[] = trim($item);
                        }
                        
                        
                        $modelModels->price = $cena;
                        $modelModels->old_price = $old_cena;
                        $modelModels->photo = (isset($data[5]) ? $data[5] : '') ;
                        $modelModels->photo_other = (($photo_other_arr[0] != '')?json_encode($photo_other_arr):'');
                        $modelModels->quantity = (isset($data[7]) ? $data[7] : '') ;
                        $description = iconv('Windows-1251', 'UTF-8', (isset($data[8]) ? $data[8] : '') );
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode($accessories);
                        $modelModels->top = (isset($data[10]) ? $data[10] : '') ;
                        $modelModels->promotion = (isset($data[11]) ? $data[11] : '') ;
                        $modelModels->novelty = (isset($data[12]) ? $data[12] : '') ;
                        $modelModels->bestPrice = (isset($data[13]) ? $data[13] : '') ;
                        
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
                                    $value = $data[$j] ? iconv('Windows-1251', 'UTF-8', $data[$j]) : '-';
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
                    
                    if(isset($sql[0]))
                    {
                        $sql_implode = implode(', ', $sql);
                        $sql_query_models = 'INSERT INTO cms_temp_models (vendor_code,
                                                                          model_name,
                                                                          price,
                                                                          old_price,
                                                                          brand_id,
                                                                          photo,
                                                                          photo_other,
                                                                          quantity,
                                                                          description,
                                                                          accessories,
                                                                          top,
                                                                          promotion,
                                                                          novelty,
                                                                          bestPrice
                                                                          ) VALUES '.$sql_implode;                                                 
                        $connection = Yii::app()->db;
                        $connection->createCommand($sql_query_models)->execute();
                        
                        $sql_query_update = 'update cms_models u
                        inner join cms_temp_models s on
                            u.vendor_code = s.vendor_code
                        set u.model_name = s.model_name,
                            u.price = s.price,
                            u.old_price = s.old_price,
                            u.brand_id = s.brand_id,
                            u.photo = s.photo,
                            u.photo_other = s.photo_other,
                            u.quantity = s.quantity,
                            u.description = s.description,
                            u.accessories = s.accessories,
                            u.top = s.top,
                            u.promotion = s.promotion,
                            u.novelty = s.novelty,
                            u.bestPrice = s.bestPrice';
                        $connection->createCommand($sql_query_update)->execute();
                        $connection->createCommand()->truncateTable('cms_temp_models');
                        
                        if(isset($sql_char))
                        {
                            $sql_implode_char = implode(', ', $sql_char);
                        
                            $sql_query_models = 'INSERT INTO cms_temp_characteristicValue (value, characteristic_id, model_id) 
                                                 VALUES '.$sql_implode_char; 
                                                 
                            $connection = Yii::app()->db;
                            $connection->createCommand($sql_query_models)->execute(); 
                            
                            $sql_query_update_char = 'UPDATE cms_characteristicValue u
                            INNER JOIN cms_temp_characteristicValue s ON
                                (u.characteristic_id = s.characteristic_id) AND (u.model_id = s.model_id)
                            SET u.value = s.value';
                            $connection->createCommand($sql_query_update_char)->execute();
                            $connection->createCommand()->truncateTable('cms_temp_characteristicValue'); 
                        }  
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
	