<?php

/**
 * This is the model class for table "{{characteristics}}".
 *
 * The followings are the available columns in table '{{characteristics}}':
 * @property integer $id
 * @property string $characteristic_name
 * @property integer $parent_id
 * @property integer $filter
 * @property string $unit
 * @property integer $category_id
 */
class Characteristics extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{characteristics}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('characteristic_name', 'required'),
			array('parent_id, filter, category_id', 'numerical', 'integerOnly'=>true),
			array('characteristic_name, unit', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, characteristic_name, parent_id, filter, unit, category_id', 'safe', 'on'=>'search'),
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
            'categorys' => array(self::BELONGS_TO, 'Category', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'characteristic_name' => 'Имя характеристики',
			'parent_id' => 'Родитель',
			'filter' => 'Участие в фильтрации',
			'unit' => 'Еденицы измерения',
			'category_id' => 'Категория',
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
		$criteria->compare('characteristic_name',$this->characteristic_name,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('filter',$this->filter);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('category_id',$this->category_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Characteristics the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
}
