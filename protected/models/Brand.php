<?php

/**
 * This is the model class for table "{{brand}}".
 *
 * The followings are the available columns in table '{{brand}}':
 * @property integer $id
 * @property string $brand
 * @property string $img
 */
class Brand extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{brand}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('brand', 'required'),
			array('brand', 'length', 'max'=>255),
            array('img','file','types'=>'jpg, jpeg, png', 'message'=>'Добавьте изображение'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, brand, img', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'brand' => 'Brand',
			'img' => 'Img',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('img',$this->img,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Brand the static model class
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
            $this->deleteDocument();
            if ($file = CUploadedFile::getInstance($this, 'img'))
            {
                
                $extension = strtolower($file->extensionName);
                $filename = DFileHelper::getRandomFileName(self::IMAGE_PATH, $extension);
                $basename = $filename . '.' . $extension;                          
                
                if($file->saveAs(self::IMAGE_PATH . '/' . $basename))
                $this->img = $basename;                    
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
 
    public function deleteDocument(){
        $documentPath=self::IMAGE_PATH . '/' . $this->img;
        if(is_file($documentPath))
        {
            unlink($documentPath);
        }
            
    }
}
