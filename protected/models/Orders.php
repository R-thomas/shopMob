<?php

/**
 * This is the model class for table "{{orders}}".
 *
 * The followings are the available columns in table '{{orders}}':
 * @property integer $id
 * @property string $name
 * @property string $tel
 * @property string $email
 * @property string $model_id
 * @property string $quantity
 * @property string $sum
 * @property integer $total
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{orders}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, tel, email', 'required'),
			array('total, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('tel, email', 'length', 'max'=>100),
            array('email', 'email'),
            array('tel', 'match', 'pattern'=>'/^([+]?[0-9-() ]+)$/', 'message'=>'Неверный формат номера телефона'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, status, name, tel, email, model_id, quantity, sum, total', 'safe', 'on'=>'search'),
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
			'id' => 'Номер заказа',
            'status' => 'Статус заказа',            
			'name' => 'Имя',
			'tel' => 'Телефон',
			'email' => 'Email',
			'model_id' => 'Модель',
			'quantity' => 'Количество',
			'sum' => 'Сумма, руб',
			'total' => 'Сумма<br/>заказа, руб',
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
        $criteria->compare('status',$this->status);        
		$criteria->compare('name',$this->name,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('model_id',$this->model_id,true);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('sum',$this->sum,true);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
            'defaultOrder' => 'id DESC',
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
