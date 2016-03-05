<?php

class DownloadController extends Controller
{
    public $layout='/layouts/column2-2';
	public function actionIndex($id = false)
	{
	   if($id)
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
                                      (is_array(json_decode($item->accessories))?implode(', ', json_decode($item->accessories)):json_decode($item->accessories)), 
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
            
            $models = new CsvUpload;
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
                    if (($handle = fopen($path_csv, "r")) !== FALSE) {
                        
                      $modelModels = new Models;
                      while (($data = fgetcsv($handle, 5000, ";")) !== FALSE) {
                        
                        $modelModels->id = false;
                        $modelModels->isNewRecord = true;
                        $modelModels->vendor_code = $data[0];
                        foreach($model as $item)
                        {
                            if($item->brand == $data[1])
                            {
                                $modelModels->brand_id = $item->id;
                            }
                        }      
                        $modelModels->model_name = $data[2];
                        $modelModels->price = $data[3];
                        $modelModels->old_price = $data[4];
                        $modelModels->photo = $data[5];
                        $modelModels->photo_other = $data[6];
                        $modelModels->quantity = $data[7];
                        $modelModels->accessories = $data[8];
                        $modelModels->top = $data[9];
                        $modelModels->promotion = $data[10];
                        $modelModels->novelty = $data[11];
                        $modelModels->bestPrice = $data[12];
                        
                        if($modelModels->save(false))
                        {
                            //
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
	