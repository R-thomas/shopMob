<?php

/**
 * This is the model class for table "{{models}}".
 *
 * The followings are the available columns in table '{{models}}':
 * @property integer $id
 * @property string $model_name
 * @property integer $price
 * @property integer $brand_id
 * @property string $photo
 * @property string $photo_other
 * @property integer $quantity
 * @property integer $top
 * @property integer $promotion
 * @property integer $novelty
 * @property integer $bestPrice
 */
class Models extends CActiveRecord implements IECartPosition
{
	/**
	 * @return string the associated database table name
	 */
     
    function getId(){
        return $this->id;
    }

    function getPrice(){
        return $this->price;
    }
    
     
	public function tableName()
	{
		return '{{models}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model_name, price, vendor_code', 'required'),
			array('price, old_price, brand_id, quantity, top, promotion, novelty, bestPrice', 'numerical', 'integerOnly'=>true),
			array('model_name, vendor_code', 'length', 'max'=>255),
            array('photo','file','types'=>'jpg, jpeg, png', 'message'=>'Добавьте изображение', 'allowEmpty'=>'true'),
            array('photo_other','file','types'=>'jpg, jpeg, png', 'allowEmpty'=>'true'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, vendor_code, model_name, price, brand_id, photo, quantity, description, accessories, top, promotion, novelty, bestPrice', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'brandModel' => array(self::BELONGS_TO, 'Brand', 'brand_id'),
            'categoryId' => array(self::BELONGS_TO, 'ModelCategory', 'brand_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'vendor_code' => 'Артикул',
			'model_name' => 'Название модели',
			'price' => 'Цена',
            'old_price' => 'Старая цена',
			'brand_id' => 'Бренд',
			'photo' => 'Главная фотография',
            'photo_other' => 'Дополнительные фотографии',
			'quantity' => 'Количество',
            'description' => 'Описание',
            'accessories' => 'Сопутствующие товары',
			'top' => 'Топ продаж',
			'promotion' => 'Акция',
			'novelty' => 'Новинка',
			'bestPrice' => 'Лучшая цена',
            'Charact' => 'Характеристики'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search($id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
        $criteria->compare('vendor_code',$this->vendor_code,true);
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('photo',$this->photo,true);
        $criteria->compare('photo_other',$this->photo_other,true);
		$criteria->compare('quantity',$this->quantity);
        $criteria->compare('description',$this->description, true);
		$criteria->compare('top',$this->top);
        $criteria->compare('accessories',$this->accessories, true);
		$criteria->compare('promotion',$this->promotion);
		$criteria->compare('novelty',$this->novelty);
		$criteria->compare('bestPrice',$this->bestPrice);
        $criteria->condition = 'brand_id = :id';
        $criteria->params = array(':id' => $id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Models the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    const IMAGE_PATH = 'upload/images';
    
    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            
            if (($this->scenario=='insert' || $this->scenario=='update') && ($file = CUploadedFile::getInstance($this, 'photo')))
            {
                $extension = strtolower($file->extensionName);
                $filename = DFileHelper::getRandomFileName(self::IMAGE_PATH, $extension);
                $basename = $filename . '.' . $extension;                          
                $this->deleteDocument();
                if($file->saveAs(self::IMAGE_PATH . '/' . $basename))
                $this->photo = $basename;   
            }
            else{
                
                if($this->scenario == 'update')
                    unset($this->photo);
            }
            
            
            
            if ($files = CUploadedFile::getInstancesByName('photo_other'))
            {
                $array = array();
                foreach ($files as $img)
                {
                    $extension = strtolower($img->extensionName);
                    $filename = DFileHelper::getRandomFileName(self::IMAGE_PATH, $extension);
                    $basename = $filename . '.' . $extension;                          
                    array_push($array, $basename); 
                    $img->saveAs(self::IMAGE_PATH . '/' . $basename);
                }
                
                if($photo_other = json_encode($array))
                    $this->photo_other = $photo_other;
                  
            }
            else{
                if(strtolower($this->scenario) == 'update')
                    unset($this->photo_other);
            }                 
             
            return true;
        } 
        else 
            return false;
    }
    
    
    
    protected function beforeDelete(){
        if(!parent::beforeDelete())
            return false;
        $this->deleteDocument(); // удалили модель? удаляем и файл
        return true;
    }
    
    protected function afterFind(){
        if (($this->scenario=='update')&&($this->accessories))
        {
            $arr = json_decode($this->accessories);
            $this->accessories = implode(', ', $arr);
        }
        
        if (($this->scenario=='update') && ($file = CUploadedFile::getInstance($this, 'photo')))
        $this->deleteDocumentPhoto();
        if (($this->scenario=='update') && ($files = CUploadedFile::getInstancesByName('photo_other')))
        $this->deleteDocumentPhoto_other();
        
    }
    
    protected function afterSave(){
        $id_update = $this->id;
    }
 
    public function deleteDocument(){
        $documentPath=self::IMAGE_PATH . '/' . $this->photo;
        if(is_file($documentPath))
        {
            unlink($documentPath);
        }
        
        if($file_array = json_decode($this->photo_other))
        {
            foreach($file_array as $item)
            {
                $documentPath2=self::IMAGE_PATH . '/' . $item;
                if(is_file($documentPath2))
                {
                    unlink($documentPath2);
                }
            }
        }    
    }
    
    public function deleteDocumentPhoto(){
        $documentPath=self::IMAGE_PATH . '/' . $this->photo;
        if(is_file($documentPath))
        {
            unlink($documentPath);
        }
    }
    
    public function deleteDocumentPhoto_other(){
        if($file_array = json_decode($this->photo_other))
        {
            foreach($file_array as $item)
            {
                $documentPath2=self::IMAGE_PATH . '/' . $item;
                if(is_file($documentPath2))
                {
                    unlink($documentPath2);
                }
            }
        }
    }
    
    public static function images($id)
    {
        $model = self::model()->findByPk($id);
        $array = '';
        if($model->photo_other != '')
        {
            $arr = json_decode($model->photo_other);
            foreach($arr as $img)
            {
                $array = $array.CHtml::image('/upload/images/'.$img,
                                             '', 
                                             array("style" => "max-height:140px; 
                                                               max-width: 140px; 
                                                               margin-bottom: 10px; 
                                                               margin-left:20px; 
                                                               border:1px solid #ccc"));
            }
            
            return $array;
        }
    }
    
    public static function charView($idkey, $model_id)
    {
        $array =  Characteristics::values($idkey, $model_id);
        $arr = '';
        foreach ($array as $item)
        {
            if ($item['parent_id'] == 0)
            {
                $arr = $arr
                       .'<b>'
                       .$item['characteristic_name']
                       .'</b><br/>';
            }
            else
            {
		if(isset($item['value']))
		{
                $arr = $arr
                       .'&nbsp;&nbsp;'
                       .$item['characteristic_name']
                       .'&nbsp;&nbsp;-&nbsp;&nbsp;'
                       .'<b>'
                       .$item['value']
                       .'</b>'
                       .'&nbsp;&nbsp;'
                       .$item['unit']
                       .'<br/>';
		}
            }
        }
        return $arr;
    }
    
    public static function randomId()
    {
        $connection = Yii::app()->db;  
        $sql = 'SELECT cms_models.id, model_name, price, old_price, photo, cms_category.category_name, cms_brand.brand 
                FROM cms_models JOIN cms_brand JOIN cms_modelCategory JOIN cms_category
                                ON cms_models.brand_id = cms_brand.id AND  cms_models.brand_id = cms_modelCategory.id AND cms_modelCategory.category_id = cms_category.id
                ORDER BY RAND() 
                LIMIT 4';      
        $result = $connection->createCommand($sql)->queryAll();
        return $result;
    }
    
    public static function filterCategory1($ids_query)
    {
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
        return $count;
    }
}