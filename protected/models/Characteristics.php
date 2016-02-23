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
    
    
    // для админки
    public static function values($category, $model_id)
    {
        $connection = Yii::app()->db;
        $char = $connection->createCommand(array(
            'select' => array('id', 'characteristic_name', 'parent_id', 'unit', 'category_id'),
            'from' => '{{characteristics}}',
            'where' => 'category_id = :category',
            'params' => array(':category'=>$category)
        ))->queryAll();
        
        $id = $char['id'];
        
        
        $result = $connection->createCommand(array(
            'select' => array('value', 'characteristic_id'),
            'from' => '{{characteristicValue}}',
            'where' => array('and', 'model_id = :model_id' , array('in', 'characteristic_id', $id)),
            'params'=>array(':model_id'=>$model_id)
        ))->queryAll();
        
        foreach ($char as $k=>$item_char)
        {
            foreach($result as $item_result)
            {
                if($item_char['id'] == $item_result['characteristic_id'])
                {
                    $char[$k]['value'] = $item_result['value'];
                }
            }
        }
              
        
        return $char;
    }
    
    
    // для фильтров
    public static function filterRender($category, $model){
        
        $char = self::valuesChar($category, $model);
        
        foreach ($char as $item)
        {
            if($item['parent_id'] == 0)
            {
                echo '<div class="goods_filter_selected">
                          <p>'.$item['characteristic_name'].' <span class="glyphicon glyphicon-triangle-bottom"></p>
                      </div>';
            }
            else
            {
                if (isset($charName))
                {
                    if($charName == $item['characteristic_name']) continue;
                }
                echo '<div class="filter_selected">
                        <div class="selected_item">
                             <p>'.$item['characteristic_name'].'</p>';
                             
                $charName = $item['characteristic_name'];
                
                foreach($char as $items)
                {
                    if($items['characteristic_name'] == $charName & $items['value']!='')
                    {
                        
                        echo '<div><input type="checkbox" name="aaa[]" value="'.$items['id'].'" id="bbb-'.$items['id'].'" class="ccc" style = "display:none" '.(   (isset($_GET['aaa'])  ?    (in_array($items['id'], $_GET['aaa'])?'disabled':'' ):'')).'><label class="goods_content_label" for="bbb-'.$items['id'].'"'.(   (isset($_GET['aaa'])  ?    (in_array($items['id'], $_GET['aaa'])?'style="opacity:0.5" disabled><span class="glyphicon glyphicon-check"></span>':'><span class="glyphicon glyphicon-unchecked"></span>' ):'><span class="glyphicon glyphicon-unchecked"></span>')).'
                                 
                                 <span class="selected_value">'.$items['value'].' '.$items['unit'].'</span></label>
                             </div>';
                    }
                }
                echo '</div></div>';
                        
            }
        }//'.$items['val'].'
    }
    
    public static function valuesChar($category, $model)
    {/*
        $connection = Yii::app()->db;
        $sql = 'SELECT val, cms_characteristicValue.id, cms_characteristicValue.value, cms_characteristicValue.model_id, cms_characteristicValue.characteristic_id, cms_characteristics.characteristic_name, cms_characteristics.parent_id, cms_characteristics.filter, cms_characteristics.unit, cms_characteristics.category_id
                FROM (
                      SELECT value, id, (SELECT count(model_id) FROM cms_characteristicValue AS a WHERE a.value = cms_characteristicValue.value) AS val, model_id, characteristic_id
                      FROM cms_characteristicValue
                      GROUP BY value
                      ) AS cms_characteristicValue
                RIGHT JOIN cms_characteristics ON cms_characteristicValue.characteristic_id = cms_characteristics.id
                WHERE (cms_characteristics.category_id = '.$category.' AND cms_characteristicValue.model_id IN (1,2,59)) OR (parent_id = 0 AND cms_characteristics.category_id = '.$category.')';
        $char = $connection->createCommand($sql)->queryAll(); */   
        $connection = Yii::app()->db;          
        $char = $connection->createCommand()
        ->select('val, cms_characteristicValue.id, cms_characteristicValue.value, cms_characteristicValue.model_id, cms_characteristicValue.characteristic_id, cms_characteristics.characteristic_name, cms_characteristics.parent_id, cms_characteristics.filter, cms_characteristics.unit, cms_characteristics.category_id')
        ->from('(SELECT value, id, (SELECT count(model_id) FROM cms_characteristicValue AS a WHERE a.value = cms_characteristicValue.value) AS val, model_id, characteristic_id
                  FROM cms_characteristicValue
                  GROUP BY value
                  ) AS cms_characteristicValue')
        ->rightJoin('cms_characteristics', 'cms_characteristicValue.characteristic_id = cms_characteristics.id')
        //->where(array('OR', 
                      //array('AND', 'cms_characteristics.category_id = :category', array('IN', 'cms_characteristicValue.model_id', $model)),
                      //array('AND', 'parent_id = 0', 'cms_characteristics.category_id = :category')), 
                //array(':category'=>$category))
        ->where('cms_characteristics.category_id = 1', array(':category'=>$category))        
        ->queryAll();
        return $char;
    }
    
    
    // для карточки товара фронтенд
    public static function cardChar($modelId){
        
        $connection = Yii::app()->db;
        $categoryArr = $connection->createCommand()
        ->select('category_id')
        ->from('cms_models')
        ->join('cms_modelCategory', 'cms_models.brand_id = cms_modelCategory.brand_id')
        ->where('cms_models.id = :id', array(':id' => $modelId))
        ->queryAll();
            
        $category = $categoryArr[0]['category_id'];
        
        $connection = Yii::app()->db;
        $char = $connection->createCommand(array(
            'select' => array('id', 'characteristic_name', 'parent_id', 'unit', 'category_id'),
            'from' => '{{characteristics}}',
            'where' => 'category_id = :category',
            'params' => array(':category'=>$category)
        ))->queryAll();
        
        $id = $char['id'];
        
        
        $result = $connection->createCommand(array(
            'select' => array('value', 'characteristic_id'),
            'from' => '{{characteristicValue}}',
            'where' => array('and', 'model_id = :model_id' , array('in', 'characteristic_id', $id)),
            'params'=>array(':model_id'=>$modelId)
        ))->queryAll();
        
        foreach ($char as $k=>$item_char)
        {
            foreach($result as $item_result)
            {
                if($item_char['id'] == $item_result['characteristic_id'])
                {
                    $char[$k]['value'] = $item_result['value'];
                }
            }
        }
              
        
        return $char;
    }
    
    
}
