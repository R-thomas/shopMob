<?php

class DownloadController extends Controller
{
    public $layout='/layouts/column2-2';
	public function actionIndex($id = false)
	{
	   $models = new CsvUpload;
	   if($id == 1)
       {
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
                        $counter++; 
                        if($counter == 1)
                            continue;
                        
                        // если есть такой бренд, то продолжаем
                        $modelModels = new Models;    
                        $i = 0;
                        foreach($model as $item)
                        {
                            if($item->brand == $data[1])
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
                            if($item->vendor_code == $data[0])
                            {
                                $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                                Models::model()->updateAll(array('vendor_code'=>$data[0],
                                                                 'brand_id'=>$brand_id_update,
                                                                 'model_name'=>$data[2],
                                                                 'price'=>$data[3],
                                                                 'old_price'=>$data[4],
                                                                 'photo'=>$data[5],
                                                                 'photo_other'=>json_encode(explode(', ', $data[6])),
                                                                 'quantity'=>$data[7],
                                                                 'description'=>$description,
                                                                 'accessories'=>json_encode(explode(', ', $data[9])),
                                                                 'top'=>$data[10],
                                                                 'promotion'=>$data[11],
                                                                 'novelty'=>$data[12],
                                                                 'bestPrice'=>$data[13]
                                                                 ), 
                                                           'vendor_code = :code', 
                                                           array(':code'=>$data[0]));
                                                           
                                
                                $id_update = $item->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0)
                                    {
                                        $value = iconv('Windows-1251', 'UTF-8', $data[$j]);
                                        CharacteristicValue::model()->updateAll(array('value'=>$value), 
                                                                                'model_id = :id_update AND characteristic_id = :characteristic_id',
                                                                                array(':id_update'=>$id_update, ':characteristic_id'=>$items->id));
                                    
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
                        $modelModels->vendor_code = $data[0];
                        $modelModels->model_name = $data[2];
                        $modelModels->price = $data[3];
                        $modelModels->old_price = $data[4];
                        $modelModels->photo = $data[5];
                        $modelModels->photo_other = json_encode(explode(', ', $data[6]));
                        $modelModels->quantity = $data[7];
                        $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode(explode(', ', $data[9]));
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
                                if($item->parent_id != 0)
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
                                if($j == 51)
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
	   
       if($id == 2)
       {
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
                        $counter++; 
                        if($counter == 1)
                            continue;
                        
                        // если есть такой бренд, то продолжаем
                        $modelModels = new Models;    
                        $i = 0;
                        foreach($model as $item)
                        {
                            if($item->brand == $data[1])
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
                            if($item->vendor_code == $data[0])
                            {
                                $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                                Models::model()->updateAll(array('vendor_code'=>$data[0],
                                                                 'brand_id'=>$brand_id_update,
                                                                 'model_name'=>$data[2],
                                                                 'price'=>$data[3],
                                                                 'old_price'=>$data[4],
                                                                 'photo'=>$data[5],
                                                                 'photo_other'=>json_encode(explode(', ', $data[6])),
                                                                 'quantity'=>$data[7],
                                                                 'description'=>$description,
                                                                 'accessories'=>json_encode(explode(', ', $data[9])),
                                                                 'top'=>$data[10],
                                                                 'promotion'=>$data[11],
                                                                 'novelty'=>$data[12],
                                                                 'bestPrice'=>$data[13]
                                                                 ), 
                                                           'vendor_code = :code', 
                                                           array(':code'=>$data[0]));
                                                           
                                
                                $id_update = $item->id;
                                $j = 14;                           
                                foreach($modelCharacteristics as $k=>$items)
                                {
                                    if($items->parent_id != 0)
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
                        $modelModels->vendor_code = $data[0];
                        $modelModels->model_name = $data[2];
                        $modelModels->price = $data[3];
                        $modelModels->old_price = $data[4];
                        $modelModels->photo = $data[5];
                        $modelModels->photo_other = json_encode(explode(', ', $data[6]));
                        $modelModels->quantity = $data[7];
                        $description = iconv('Windows-1251', 'UTF-8', $data[8]);
                        $modelModels->description = $description;
                        $modelModels->accessories = json_encode(explode(', ', $data[9]));
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
                                if($item->parent_id != 0)
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
        
		$this->render('index', array(
            'id'=>$id,
            'models' => $models,
        ));
	}

}
	