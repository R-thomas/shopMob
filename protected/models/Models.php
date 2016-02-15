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
class Models extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
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
			array('model_name, price', 'required'),
			array('price, old_price, brand_id, quantity, top, promotion, novelty, bestPrice', 'numerical', 'integerOnly'=>true),
			array('model_name', 'length', 'max'=>255),
            array('photo','file','types'=>'jpg, jpeg, png', 'message'=>'Добавьте изображение', 'allowEmpty'=>'true'),
            array('photo_other','file','types'=>'jpg, jpeg, png', 'allowEmpty'=>'true'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model_name, price, brand_id, photo, quantity, top, promotion, novelty, bestPrice', 'safe', 'on'=>'search'),
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
            'categoryId' => array(self::BELONGS_TO, 'modelCategory', 'brand_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'model_name' => 'Название модели',
			'price' => 'Цена',
            'old_price' => 'Старая цена',
			'brand_id' => 'Бренд',
			'photo' => 'Главная фотография',
            'photo_other' => 'Дополнительные фотографии',
			'quantity' => 'Количество',
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
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('photo',$this->photo,true);
        $criteria->compare('photo_other',$this->photo_other,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('top',$this->top);
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
        if (($this->scenario=='update') && ($file = CUploadedFile::getInstance($this, 'photo')))
        $this->deleteDocumentPhoto();
        if (($this->scenario=='update') && ($files = CUploadedFile::getInstancesByName('photo_other')))
        $this->deleteDocumentPhoto_other();
        
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
        return $arr;
    }
    
    public static function randomId()
    {
        $connection = Yii::app()->db;  
        $sql = 'SELECT cms_models.id, model_name, price, old_price, photo, cms_category.category_name 
                FROM cms_models JOIN cms_brand JOIN cms_modelCategory JOIN cms_category
                                ON cms_models.brand_id = cms_brand.id AND  cms_models.brand_id = cms_modelCategory.id AND cms_modelCategory.category_id = cms_category.id
                ORDER BY RAND() 
                LIMIT 4';      
        $result = $connection->createCommand($sql)->queryAll();
        return $result;
    }
}
