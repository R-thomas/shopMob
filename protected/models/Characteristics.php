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
        
        $id = array();
        foreach ($char as $item)
        {
            $id[] = $item['id'];
        }
        
        
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
    /*
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
                        
                        echo '<div><input type="checkbox" name="aaa[]" value="'.$items['id'].'" id="bbb-'.$items['id'].'" class="ccc" style = "display:none" '.(   (isset($_GET['aaa'])  ?    (in_array($items['id'], $_GET['aaa'])?'':'' ):'')).'><label class="goods_content_label" for="bbb-'.$items['id'].'"'.(   (isset($_GET['aaa'])  ?    (in_array($items['id'], $_GET['aaa'])?'style="opacity:0.5" ><span class="glyphicon glyphicon-check"></span>':'><span class="glyphicon glyphicon-unchecked"></span>' ):'><span class="glyphicon glyphicon-unchecked"></span>')).'
                                 
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
        $char = $connection->createCommand($sql)->queryAll();    
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
    }*/
    
    public static function filterRender($category, $model, $count, $count_maker, $count_top, $count_promotion, $count_novelty, $count_bestPrice)
    {
        if($category == 1 && isset($count))
        {
            echo '<div class="goods_filter_selected">
                    <p>Общие параметры <span class="glyphicon glyphicon-triangle-bottom"></p>
                  </div>
                  <div class="filter_selected">
                      <div class="selected_item">
                         <div>
                             <input type="checkbox" name="common[]" value="promotion" id="promotion" class="ccc" style = "display:none" '.($count_promotion==0?"disabled":"").'>
                             <label class="goods_content_label" for="promotion" style="'.($count_promotion==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['common']) ? 
                                            (in_array ("promotion", $_GET['common']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Акции ('.$count_promotion.')</span>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="top" id="top" class="ccc" style = "display:none" '.($count_top==0?"disabled":"").'>
                             <label class="goods_content_label" for="top" style="'.($count_top==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['common']) ? 
                                            (in_array ("top", $_GET['common']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">Хит продаж ('.$count_top.')</span>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="novelty" id="novelty" class="ccc" style = "display:none" '.($count_novelty==0?"disabled":"").'>
                             <label class="goods_content_label" for="novelty" style="'.($count_novelty==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['common']) ? 
                                            (in_array ("novelty", $_GET['common']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Новинка ('.$count_novelty.')</span>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="bestPrice" id="bestPrice" class="ccc" style = "display:none" '.($count_bestPrice==0?"disabled":"").'>
                             <label class="goods_content_label" for="bestPrice" style="'.($count_bestPrice==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['common']) ? 
                                            (in_array ("bestPrice", $_GET['common']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Лучшая цена ('.$count_bestPrice.')</span>
                         </div>
                         <p>Производитель</p>';
                         $arr = array();
                 foreach($model as $item)
                 {
                    $arr[] = $item->brandModel->brand;
                 }
                         
                 $m = array_unique($arr); 
                 $j = 0;      
                 foreach($m as $k=>$item)
                 {
                    $j++;
                    $i = 0;
                    foreach($count_maker as $n=>$maker)
                    {
                        $i++;
                        if($j == $i)
                        {
                            echo '<div>
                                 <input type="checkbox" name="brand[]" value="'.$item.'" id="brand_'.$item.'" class="ccc" style = "display:none" '.($maker==0?"disabled":"").'>
                                 <label class="goods_content_label" for="brand_'.$item.'" style="'.($maker==0?"opacity: 0.5; cursor:default;":"").'">
                                 <span class="'.(isset($_GET['brand']) ? 
                                            (in_array ($item, $_GET['brand']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                                 </span> 
                                 <span class="selected_value">'.$item.' ('.$maker.')</span>
                             </div>';
                        }
                    }
                    
                 } 
                            
                 echo    '
                          <p>Тип</p>
                          <div>
                             <input type="checkbox" name="type[]" value="Смартфон" id="type_smartphone" class="ccc" style = "display:none" '.($count[0]["smart"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="type_smartphone" style="'.($count[0]["smart"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['type']) ? 
                                            (in_array ("Смартфон", $_GET['type']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                                 </span>   
                             <span class="selected_value">Смартфон ('.$count[0]["smart"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="type[]" value="Телефон" id="type_telephone" class="ccc" style = "display:none" '.($count[0]["tel"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="type_telephone" style="'.($count[0]["tel"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['type']) ? 
                                            (in_array ("Телефон", $_GET['type']) ? 
                                            "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                            "glyphicon glyphicon-unchecked").'">
                                 </span>    
                             <span class="selected_value">Телефон ('.$count[0]["tel"].')</span></label>
                         </div>
                         <p>Форм-фактор</p>
                         <div>
                             <input type="checkbox" name="form[]" value="Кнопочный моноблок" id="form_mono" class="ccc" style = "display:none" '.($count[0]["button_mono"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="form_mono" style="'.($count[0]["button_mono"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['form']) ? 
                                        (in_array ("Кнопочный моноблок", $_GET['form']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Кнопочный моноблок ('.$count[0]["button_mono"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="form[]" value="Раскладушка" id="form_transf" class="ccc" style = "display:none" '.($count[0]["trasformer"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="form_transf" style="'.($count[0]["trasformer"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['form']) ? 
                                        (in_array ("Раскладушка", $_GET['form']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Раскладушка ('.$count[0]["trasformer"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="form[]" value="Сенсорный моноблок" id="form_sensor" class="ccc" style = "display:none" '.($count[0]["sensor_mono"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="form_sensor" style="'.($count[0]["sensor_mono"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['form']) ? 
                                        (in_array ("Сенсорный моноблок", $_GET['form']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">Сенсорный моноблок ('.$count[0]["sensor_mono"].')</span></label>
                         </div>
                         <p>Операционная система</p>
                         <div>
                             <input type="checkbox" name="os[]" value="Android" id="os_android" class="ccc" style = "display:none" '.($count[0]["android"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="os_android" style="'.($count[0]["android"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['os']) ? 
                                        (in_array ("Android", $_GET['os']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>   
                             <span class="selected_value">Android ('.$count[0]["android"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="iOS" id="os_iOS" class="ccc" style = "display:none" '.($count[0]["ios"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="os_iOS" style="'.($count[0]["ios"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['os']) ? 
                                        (in_array ("iOS", $_GET['os']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">iOS ('.$count[0]["ios"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows" id="os_windows" class="ccc" style = "display:none" '.($count[0]["windows"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="os_windows" style="'.($count[0]["windows"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['os']) ? 
                                        (in_array ("Windows", $_GET['os']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Windows ('.$count[0]["windows"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="без ОС" id="os_no" class="ccc" style = "display:none" '.($count[0]["no_os"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="os_no" style="'.($count[0]["no_os"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['os']) ? 
                                        (in_array ("без ОС", $_GET['os']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">без ОС ('.$count[0]["no_os"].')</span></label>
                         </div>
                         <p>Количество Sim-карт</p>
                         <div>
                             <input type="checkbox" name="sim[]" value="1 sim" id="sim1" class="ccc" style = "display:none" '.($count[0]["1sim"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="sim1" style="'.($count[0]["1sim"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['sim']) ? 
                                        (in_array ("1 sim", $_GET['sim']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">1 sim ('.$count[0]["1sim"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="sim[]" value="2 sim" id="sim2" class="ccc" style = "display:none" '.($count[0]["2sim"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="sim2" style="'.($count[0]["2sim"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['sim']) ? 
                                        (in_array ("2 sim", $_GET['sim']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">2 sim ('.$count[0]["2sim"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="sim[]" value="3 sim" id="sim3" class="ccc" style = "display:none" '.($count[0]["3sim"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="sim3" style="'.($count[0]["3sim"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['sim']) ? 
                                        (in_array ("3 sim", $_GET['sim']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">3 sim ('.$count[0]["3sim"].')</span></label>
                         </div>
                         <p>Степень защиты</p>
                         <div>
                             <input type="checkbox" name="protection[]" value="нет" id="protection_no" class="ccc" style = "display:none" '.($count[0]["no_protect"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="protection_no" style="'.($count[0]["no_protect"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['protection']) ? 
                                        (in_array ("нет", $_GET['protection']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">нет ('.$count[0]["no_protect"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="protection[]" value="ip67" id="protection_ip67" class="ccc" style = "display:none" '.($count[0]["ip67"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="protection_ip67" style="'.($count[0]["ip67"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['protection']) ? 
                                        (in_array ("ip67", $_GET['protection']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">ip67 ('.$count[0]["ip67"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="protection[]" value="ip68" id="protection_ip68" class="ccc" style = "display:none" '.($count[0]["ip68"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="protection_ip68" style="'.($count[0]["ip68"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['protection']) ? 
                                        (in_array ("ip68", $_GET['protection']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">ip68 ('.$count[0]["ip68"].')</span></label>
                         </div>
                     </div>  
                </div>
                <div class="goods_filter_selected">
                    <p>Экран <span class="glyphicon glyphicon-triangle-bottom"></p>
                </div>
                <div class="filter_selected">
                      <div class="selected_item">
                         <p>Диагональ экрана</p>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="0-3.9" id="less39" class="ccc" style = "display:none" '.($count[0]["diagonal_0_39"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="less39" style="'.($count[0]["diagonal_0_39"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['diagonal']) ? 
                                        (in_array ("0-3.9", $_GET['diagonal']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">до 3.9 ('.$count[0]["diagonal_0_39"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="4.0-4.5" id="40-45" class="ccc" style = "display:none" '.($count[0]["diagonal_40_45"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="40-45" style="'.($count[0]["diagonal_40_45"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['diagonal']) ? 
                                        (in_array ("4.0-4.5", $_GET['diagonal']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>  
                             <span class="selected_value">4.0-4.5 ('.$count[0]["diagonal_40_45"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="4.6-5.0" id="46-50" class="ccc" style = "display:none" '.($count[0]["diagonal_46_50"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="46-50" style="'.($count[0]["diagonal_46_50"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['diagonal']) ? 
                                        (in_array ("4.6-5.0", $_GET['diagonal']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>  
                             <span class="selected_value">4.6-5.0 ('.$count[0]["diagonal_46_50"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="5.1-5.5" id="51-55" class="ccc" style = "display:none" '.($count[0]["diagonal_51_55"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="51-55" style="'.($count[0]["diagonal_51_55"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['diagonal']) ? 
                                        (in_array ("5.1-5.5", $_GET['diagonal']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">5.1-5.5 ('.$count[0]["diagonal_51_55"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="5,5-100,0" id="more55" class="ccc" style = "display:none" '.($count[0]["diagonal_55_1000"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="more55" style="'.($count[0]["diagonal_55_1000"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['diagonal']) ? 
                                        (in_array ("5,5-100,0", $_GET['diagonal']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">свыше 5.5 ('.$count[0]["diagonal_55_1000"].')</span></label>
                         </div>
                         <p>Тип экрана</p>
                         <div>
                             <input type="checkbox" name="screen[]" value="TFT" id="screen_TFT" class="ccc" style = "display:none" '.($count[0]["TFT"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="screen_TFT" style="'.($count[0]["TFT"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['screen']) ? 
                                        (in_array ("TFT", $_GET['screen']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">TFT ('.$count[0]["TFT"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="TN" id="screen_TTN" class="ccc" style = "display:none" '.($count[0]["TN"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="screen_TTN" style="'.($count[0]["TN"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['screen']) ? 
                                        (in_array ("TN", $_GET['screen']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">TN ('.$count[0]["TN"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="Retina" id="screen_Retina" class="ccc" style = "display:none" '.($count[0]["Retina"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="screen_Retina" style="'.($count[0]["Retina"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['screen']) ? 
                                        (in_array ("Retina", $_GET['screen']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">Retina ('.$count[0]["Retina"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="IPS" id="screen_IPS" class="ccc" style = "display:none" '.($count[0]["IPS"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="screen_IPS" style="'.($count[0]["IPS"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['screen']) ? 
                                        (in_array ("IPS", $_GET['screen']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">IPS ('.$count[0]["IPS"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="Amoled" id="screen_Amoled" class="ccc" style = "display:none" '.($count[0]["Amoled"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="screen_Amoled" style="'.($count[0]["Amoled"]==0?"opacity: 0.5; cursor:default;":"").'">
                             <span class="'.(isset($_GET['screen']) ? 
                                        (in_array ("Amoled", $_GET['screen']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span>
                             <span class="selected_value">Amoled ('.$count[0]["Amoled"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="SuperAmoled" id="screen_SuperAmoled" class="ccc" style = "display:none" '.($count[0]["SuperAmoled"]==0?"disabled":"").'>
                             <label class="goods_content_label" for="screen_SuperAmoled" style="'.($count[0]["SuperAmoled"]==0?"opacity: 0.5; cursor:default;":"").'" >
                             <span class="'.(isset($_GET['screen']) ? 
                                        (in_array ("SuperAmoled", $_GET['screen']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                             </span> 
                             <span class="selected_value">SuperAmoled ('.$count[0]["SuperAmoled"].')</span></label>
                         </div>
                      </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Процессор <span class="glyphicon glyphicon-triangle-bottom"></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Количесвтво ядер</p>
                            <div>
                                <input type="checkbox" name="core[]" value="x1" id="core_x1" class="ccc" style = "display:none" '.($count[0]["x1"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="core_x1" style="'.($count[0]["x1"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core']) ? 
                                        (in_array ("x1", $_GET['core']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">x1 ('.$count[0]["x1"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x2" id="core_x2" class="ccc" style = "display:none" '.($count[0]["x2"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="core_x2" style="'.($count[0]["x2"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core']) ? 
                                        (in_array ("x2", $_GET['core']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">x2 ('.$count[0]["x2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x3" id="core_x3" class="ccc" style = "display:none" '.($count[0]["x3"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="core_x3" style="'.($count[0]["x3"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core']) ? 
                                        (in_array ("x3", $_GET['core']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">x3 ('.$count[0]["x3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x4" id="core_x4" class="ccc" style = "display:none" '.($count[0]["x4"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="core_x4" style="'.($count[0]["x4"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core']) ? 
                                        (in_array ("x4", $_GET['core']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">x4 ('.$count[0]["x4"].')</span></label>
                            </div>
                            <p>Частота процессора</p>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.0" id="frequency_1.0" class="ccc" style = "display:none" '.($count[0]["f10"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.0" style="'.($count[0]["f10"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.0", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.0 ГГц ('.$count[0]["f10"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.1" id="frequency_1.1" class="ccc" style = "display:none" '.($count[0]["f11"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.1" style="'.($count[0]["f11"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.1", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.1 ГГц ('.$count[0]["f11"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.2" id="frequency_1.2" class="ccc" style = "display:none" '.($count[0]["f12"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.2" style="'.($count[0]["f12"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.2", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.2 ГГц ('.$count[0]["f12"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.3" id="frequency_1.3" class="ccc" style = "display:none" '.($count[0]["f13"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.3" style="'.($count[0]["f13"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.3", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>    
                                <span class="selected_value">1.3 ГГц ('.$count[0]["f13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.4" id="frequency_1.4" class="ccc" style = "display:none" '.($count[0]["f14"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.4" style="'.($count[0]["f14"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.4", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.4 ГГц ('.$count[0]["f14"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.5" id="frequency_1.5" class="ccc" style = "display:none" '.($count[0]["f15"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.5" style="'.($count[0]["f15"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.5", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.5 ГГц ('.$count[0]["f15"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.6" id="frequency_1.6" class="ccc" style = "display:none" '.($count[0]["f16"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.6" style="'.($count[0]["f16"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.6", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>    
                                <span class="selected_value">1.6 ГГц ('.$count[0]["f16"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.7" id="frequency_1.7" class="ccc" style = "display:none" '.($count[0]["f17"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.7" style="'.($count[0]["f17"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.7", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.7 ГГц ('.$count[0]["f17"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.8" id="frequency_1.8" class="ccc" style = "display:none" '.($count[0]["f18"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.8" style="'.($count[0]["f18"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.8", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.8 ГГц ('.$count[0]["f18"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.9" id="frequency_1.9" class="ccc" style = "display:none" '.($count[0]["f19"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_1.9" style="'.($count[0]["f19"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("1.9", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1.9 ГГц ('.$count[0]["f19"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.0" id="frequency_2.0" class="ccc" style = "display:none" '.($count[0]["f20"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_2.0" style="'.($count[0]["f20"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("2.0", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">2.0 ГГц ('.$count[0]["f20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.1" id="frequency_2.1" class="ccc" style = "display:none" '.($count[0]["f21"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_2.1" style="'.($count[0]["f21"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("2.1", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">2.1 ГГц ('.$count[0]["f21"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.2" id="frequency_2.2" class="ccc" style = "display:none" '.($count[0]["f22"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_2.2" style="'.($count[0]["f22"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("2.2", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">2.2 ГГц ('.$count[0]["f22"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.3" id="frequency_2.3" class="ccc" style = "display:none" '.($count[0]["f23"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_2.3" style="'.($count[0]["f23"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("2.3", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">2.3 ГГц ('.$count[0]["f23"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.4" id="frequency_2.4" class="ccc" style = "display:none" '.($count[0]["f24"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_2.4" style="'.($count[0]["f24"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("2.4", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">2.4 ГГц ('.$count[0]["f24"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.5" id="frequency_2.5" class="ccc" style = "display:none" '.($count[0]["f25"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="frequency_2.5" style="'.($count[0]["f25"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['core_frequency']) ? 
                                        (in_array ("2.5", $_GET['core_frequency']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">2.5 ГГц ('.$count[0]["f25"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Камера, Mpx <span class="glyphicon glyphicon-triangle-bottom"></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Основная камера</p>
                            <div>
                                <input type="checkbox" name="camera[]" value="0-3" id="0-3" class="ccc" style = "display:none" '.($count[0]["cam_0_3"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="0-3" style="'.($count[0]["cam_0_3"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['camera']) ? 
                                        (in_array ("0-3", $_GET['camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">до 3 ('.$count[0]["cam_0_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="3-5" id="3-5" class="ccc" style = "display:none" '.($count[0]["cam_3_5"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="3-5" style="'.($count[0]["cam_3_5"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['camera']) ? 
                                        (in_array ("3-5", $_GET['camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">от 3 - до 5 ('.$count[0]["cam_3_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="5-8" id="5-8" class="ccc" style = "display:none" '.($count[0]["cam_5_8"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="5-8" style="'.($count[0]["cam_5_8"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['camera']) ? 
                                        (in_array ("5-8", $_GET['camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">от 5 - до 8 ('.$count[0]["cam_5_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="8-13" id="8-13" class="ccc" style = "display:none" '.($count[0]["cam_8_13"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="8-13" style="'.($count[0]["cam_8_13"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['camera']) ? 
                                        (in_array ("8-13", $_GET['camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">от 8 - до 13 ('.$count[0]["cam_8_13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="13-20" id="13-20" class="ccc" style = "display:none" '.($count[0]["cam_13_20"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="13-20" style="'.($count[0]["cam_13_20"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['camera']) ? 
                                        (in_array ("13-20", $_GET['camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">от 13 - до 20 ('.$count[0]["cam_13_20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="20-100" id="20-100" class="ccc" style = "display:none" '.($count[0]["cam_20_100"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="20-100" style="'.($count[0]["cam_20_100"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['camera']) ? 
                                        (in_array ("20-100", $_GET['camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">более 20 ('.$count[0]["cam_20_100"].')</span></label>
                            </div>
                            <p>Фронтальная камера</p>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="0.1-2" id="front_0-2" class="ccc" style = "display:none" '.($count[0]["front_cam_0_2"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="front_0-2" style="'.($count[0]["front_cam_0_2"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['front_camera']) ? 
                                        (in_array ("0.1-2", $_GET['front_camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">до 2 ('.$count[0]["front_cam_0_2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="2-5" id="front_2-5" class="ccc" style = "display:none" '.($count[0]["front_cam_2_5"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="front_2-5" style="'.($count[0]["front_cam_2_5"]==0?"opacity: 0.5; cursor:default;":"").'" >
                                <span class="'.(isset($_GET['front_camera']) ? 
                                        (in_array ("2-5", $_GET['front_camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">от 2 - до 5 ('.$count[0]["front_cam_2_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="5-100" id="front_5-100" class="ccc" style = "display:none" '.($count[0]["front_cam_5_100"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="front_5-100" style="'.($count[0]["front_cam_5_100"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['front_camera']) ? 
                                        (in_array ("5-100", $_GET['front_camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">более 5 ('.$count[0]["front_cam_5_100"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="нет" id="front_no" class="ccc" style = "display:none" '.($count[0]["front_cam_no"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="front_no" style="'.($count[0]["front_cam_no"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['front_camera']) ? 
                                        (in_array ("нет", $_GET['front_camera']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>
                                <span class="selected_value">нет ('.$count[0]["front_cam_no"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Память <span class="glyphicon glyphicon-triangle-bottom"></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Оперативная память</p>
                            <div>
                                <input type="checkbox" name="ram[]" value="0.1-0.512" id="ram_0.1-0.512" class="ccc" style = "display:none" '.($count[0]["ram_0_512"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="ram_0.1-0.512" style="'.($count[0]["ram_0_512"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['ram']) ? 
                                        (in_array ("0.1-0.512", $_GET['ram']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">до 512 мб ('.$count[0]["ram_0_512"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="0.512-1" id="ram_0.512-1" class="ccc" style = "display:none" '.($count[0]["ram_512_1"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="ram_0.512-1" style="'.($count[0]["ram_512_1"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['ram']) ? 
                                        (in_array ("0.512-1", $_GET['ram']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>    
                                <span class="selected_value">512 мб - 1 Гб ('.$count[0]["ram_512_1"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="1-2" id="ram_1-2" class="ccc" style = "display:none" '.($count[0]["ram_1_2"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="ram_1-2" style="'.($count[0]["ram_1_2"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['ram']) ? 
                                        (in_array ("1-2", $_GET['ram']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1Гб - 2 Гб ('.$count[0]["ram_1_2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="2-3" id="ram_2-3" class="ccc" style = "display:none" '.($count[0]["ram_2_3"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="ram_2-3" style="'.($count[0]["ram_2_3"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['ram']) ? 
                                        (in_array ("2-3", $_GET['ram']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">2Гб - 3Гб ('.$count[0]["ram_2_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="3-100" id="ram_3-100" class="ccc" style = "display:none" '.($count[0]["ram_3_100"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="ram_3-100" style="'.($count[0]["ram_3_100"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['ram']) ? 
                                        (in_array ("3-100", $_GET['ram']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">более 3Гб ('.$count[0]["ram_3_100"].')</span></label>
                            </div>
                            <p>Встроенная память</p>
                            <div>
                                <input type="checkbox" name="rom[]" value="0.1-4" id="rom_less4" class="ccc" style = "display:none" '.($count[0]["rom_0_4"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="rom_less4" style="'.($count[0]["rom_0_4"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['rom']) ? 
                                        (in_array ("0.1-4", $_GET['rom']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">до 4 Гб ('.$count[0]["rom_0_4"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="8" id="rom_8" class="ccc" style = "display:none" '.($count[0]["rom_8"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="rom_8" style="'.($count[0]["rom_8"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['rom']) ? 
                                        (in_array ("8", $_GET['rom']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">8 Гб ('.$count[0]["rom_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="16" id="rom_16" class="ccc" style = "display:none" '.($count[0]["rom_16"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="rom_16" style="'.($count[0]["rom_16"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['rom']) ? 
                                        (in_array ("16", $_GET['rom']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">16 Гб ('.$count[0]["rom_16"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="32" id="rom_32" class="ccc" style = "display:none" '.($count[0]["rom_32"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="rom_32" style="'.($count[0]["rom_32"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['rom']) ? 
                                        (in_array ("32", $_GET['rom']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">32 Гб ('.$count[0]["rom_32"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="64" id="rom_64" class="ccc" style = "display:none" '.($count[0]["rom_64"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="rom_64" style="'.($count[0]["rom_64"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['rom']) ? 
                                        (in_array ("64", $_GET['rom']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>    
                                <span class="selected_value">64 Гб ('.$count[0]["rom_64"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="128" id="rom_128" class="ccc" style = "display:none" '.($count[0]["rom_128"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="rom_128" style="'.($count[0]["rom_128"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['rom']) ? 
                                        (in_array ("128", $_GET['rom']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>     
                                <span class="selected_value">128 Гб ('.$count[0]["rom_128"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Беспроводные технологии <span class="glyphicon glyphicon-triangle-bottom"></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Wi-Fi</p>
                            <div>
                                <input type="checkbox" name="wifi[]" value="есть wifi" id="yes_wifi" class="ccc" style = "display:none" '.($count[0]["wifi_yes"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="yes_wifi" style="'.($count[0]["wifi_yes"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['wifi']) ? 
                                        (in_array ("есть wifi", $_GET['wifi']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">есть ('.$count[0]["wifi_yes"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="wifi[]" value="нет wifi" id="no_wifi" class="ccc" style = "display:none" '.($count[0]["wifi_no"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="no_wifi" style="'.($count[0]["wifi_no"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['wifi']) ? 
                                        (in_array ("нет wifi", $_GET['wifi']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">нет ('.$count[0]["wifi_no"].')</span></label>
                            </div>
                            <p>GPS</p>
                            <div>
                                <input type="checkbox" name="GPS[]" value="A-GPS" id="GPS_1" class="ccc" style = "display:none" '.($count[0]["gps_A_GPS"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="GPS_1" style="'.($count[0]["gps_A_GPS"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['GPS']) ? 
                                        (in_array ("A-GPS", $_GET['GPS']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>    
                                <span class="selected_value">A-GPS ('.$count[0]["gps_A_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="A-GPS/GPS" id="GPS_2" class="ccc" style = "display:none" '.($count[0]["gps_A_GPS_GPS"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="GPS_2" style="'.($count[0]["gps_A_GPS_GPS"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['GPS']) ? 
                                        (in_array ("A-GPS/GPS", $_GET['GPS']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">A-GPS/GPS ('.$count[0]["gps_A_GPS_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="GPS" id="GPS_3" class="ccc" style = "display:none" '.($count[0]["gps_GPS"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="GPS_3" style="'.($count[0]["gps_GPS"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['GPS']) ? 
                                        (in_array ("GPS", $_GET['GPS']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">GPS ('.$count[0]["gps_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="нет GPS" id="GPS_4" class="ccc" style = "display:none" '.($count[0]["gps_no"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="GPS_4" style="'.($count[0]["gps_no"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['GPS']) ? 
                                        (in_array ("нет GPS", $_GET['GPS']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">нет ('.$count[0]["gps_no"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Аккумулятор <span class="glyphicon glyphicon-triangle-bottom"></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Емкость аккумулятора</p>
                            <div>
                                <input type="checkbox" name="battery[]" value="0.1-1000" id="battery_0.1-1000" class="ccc" style = "display:none" '.($count[0]["batar_0_1000"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="battery_0.1-1000" style="'.($count[0]["batar_0_1000"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['battery']) ? 
                                        (in_array ("0.1-1000", $_GET['battery']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">до 1000 mAh ('.$count[0]["batar_0_1000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="1000-1500" id="battery_1000-1500" class="ccc" style = "display:none" '.($count[0]["batar_1000_1500"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="battery_1000-1500" style="'.($count[0]["batar_1000_1500"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['battery']) ? 
                                        (in_array ("1000-1500", $_GET['battery']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>   
                                <span class="selected_value">1000 - 1500 mAh ('.$count[0]["batar_1000_1500"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="1500-2000" id="battery_1500-2000" class="ccc" style = "display:none" '.($count[0]["batar_1500_2000"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="battery_1500-2000" style="'.($count[0]["batar_1500_2000"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['battery']) ? 
                                        (in_array ("1500-2000", $_GET['battery']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span> 
                                <span class="selected_value">1500 - 2000 mAh ('.$count[0]["batar_1500_2000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="2000-100000" id="battery_2000-100000" class="ccc" style = "display:none" '.($count[0]["batar_more_2000"]==0?"disabled":"").'>
                                <label class="goods_content_label" for="battery_2000-100000" style="'.($count[0]["batar_more_2000"]==0?"opacity: 0.5; cursor:default;":"").'">
                                <span class="'.(isset($_GET['battery']) ? 
                                        (in_array ("2000-100000", $_GET['battery']) ? 
                                        "glyphicon glyphicon-check":"glyphicon glyphicon-unchecked"):
                                        "glyphicon glyphicon-unchecked").'">
                                </span>  
                                <span class="selected_value">более 2000 mAh ('.$count[0]["batar_more_2000"].')</span></label>
                            </div>
                        </div>  
                    </div>      
                  ';
        }
        
        
        
        if($category == 2 && $count)
        {
            echo '<div class="goods_filter_selected">
                    <p>Общие параметры <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                  </div>
                  <div class="filter_selected">
                      <div class="selected_item">
                         <div>
                             <input type="checkbox" name="common[]" value="promotion" id="promotion" class="ccc" style = "display:none">
                             <label class="goods_content_label" for="promotion" style="'.($count_promotion==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>
                             <span class="selected_value">Акции ('.$count_promotion.')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="top" id="top" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="top" style="'.($count_top==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Хит продаж ('.$count_top.')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="novelty" id="novelty" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="novelty" style="'.($count_novelty==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Новинка ('.$count_novelty.')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="bestPrice" id="bestPrice" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="bestPrice" style="'.($count_bestPrice==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Лучшая цена ('.$count_bestPrice.')</span></label>
                         </div>
                         <p>Производитель</p>';
                         $arr = array();
                 foreach($model as $item)
                 {
                    $arr[] = $item->brandModel->brand;
                 }
                         
                 $m = array_unique($arr); 
                 $j = 0;      
                 foreach($m as $k=>$item)
                 {
                    $j++;
                    $i = 0;
                    foreach($count_maker as $n=>$maker)
                    {
                        $i++;
                        if($j == $i)
                        {
                            echo '<div>
                                 <input type="checkbox" name="brand[]" value="'.$item.'" id="brand_'.$item.'" class="ccc" style = "display:none" >
                                 <label class="goods_content_label" for="brand_'.$item.'" style="'.($maker==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                 <span class="selected_value">'.$item.' ('.$maker.')</span></label>
                             </div>';
                        }
                    }
                    
                 } 
                            
                 echo    '
                          <p>Тип</p>
                          <div>
                             <input type="checkbox" name="type[]" value="Планшет" id="type_smartphone" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="type_smartphone" style="'.($count[0]["smart"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Планшет ('.$count[0]["smart"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="type[]" value="трансформер" id="type_telephone" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="type_telephone" style="'.($count[0]["tel"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>    
                             <span class="selected_value">Трансформер ('.$count[0]["tel"].')</span></label>
                         </div>
                         
                         <p>Операционная система</p>
                         <div>
                             <input type="checkbox" name="os[]" value="Android" id="os_android" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_android" style="'.($count[0]["android"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Android ('.$count[0]["android"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="iOS" id="os_iOS" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_iOS" style="'.($count[0]["ios"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">iOS ('.$count[0]["ios"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows" id="os_windows" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_windows" style="'.($count[0]["windows"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows ('.$count[0]["windows"].')</span></label>
                         </div>
                         
                         <p>Количество Sim-карт</p>
                         <div>
                             <input type="checkbox" name="sim[]" value="1 sim" id="sim1" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="sim1" style="'.($count[0]["1sim"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">1 sim ('.$count[0]["1sim"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="sim[]" value="2 sim" id="sim2" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="sim2" style="'.($count[0]["2sim"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">2 sim ('.$count[0]["2sim"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="sim[]" value="нет" id="sim3" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="sim3" style="'.($count[0]["3sim"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">нет ('.$count[0]["3sim"].')</span></label>
                         </div>
                         
                     </div>  
                </div>
                <div class="goods_filter_selected">
                    <p>Экран <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                </div>
                <div class="filter_selected">
                      <div class="selected_item">
                         <p>Диагональ экрана</p>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="0-7.5" id="less75" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="less75" style="'.($count[0]["diagonal_0_75"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">до 7.5 ('.$count[0]["diagonal_0_75"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="7.6-8.0" id="76-80" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="76-80" style="'.($count[0]["diagonal_76_80"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">7.6-8.0 ('.$count[0]["diagonal_76_80"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="8.1-9.9" id="81-99" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="81-99" style="'.($count[0]["diagonal_81_99"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">8.1-9.9 ('.$count[0]["diagonal_81_99"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="10-100" id="10-100" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="10-100" style="'.($count[0]["diagonal_100_1000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">свыше 10 ('.$count[0]["diagonal_100_1000"].')</span></label>
                         </div>
                         
                         <p>Тип экрана</p>
                         <div>
                             <input type="checkbox" name="screen[]" value="TFT" id="screen_TFT" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_TFT" style="'.($count[0]["TFT"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">TFT ('.$count[0]["TFT"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="TN" id="screen_TTN" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_TTN" style="'.($count[0]["TN"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">TN ('.$count[0]["TN"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="Retina" id="screen_Retina" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_Retina" style="'.($count[0]["Retina"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Retina ('.$count[0]["Retina"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="IPS" id="screen_IPS" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_IPS" style="'.($count[0]["IPS"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">IPS ('.$count[0]["IPS"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="Amoled" id="screen_Amoled" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_Amoled" style="'.($count[0]["Amoled"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Amoled ('.$count[0]["Amoled"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="SuperAmoled" id="screen_SuperAmoled" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_SuperAmoled" style="'.($count[0]["SuperAmoled"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">SuperAmoled ('.$count[0]["SuperAmoled"].')</span></label>
                         </div>
                      </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Процессор <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Количесвтво ядер</p>
                            <div>
                                <input type="checkbox" name="core[]" value="x1" id="core_x1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x1" style="'.($count[0]["x1"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">x1 ('.$count[0]["x1"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x2" id="core_x2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x2" style="'.($count[0]["x2"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">x2 ('.$count[0]["x2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x3" id="core_x3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x3" style="'.($count[0]["x3"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">x3 ('.$count[0]["x3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x4" id="core_x4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x4" style="'.($count[0]["x4"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">x4 ('.$count[0]["x4"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x8" id="core_x8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x8" style="'.($count[0]["x8"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">x8 ('.$count[0]["x8"].')</span></label>
                            </div>
                            <p>Частота процессора</p>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.0" id="frequency_1.0" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.0" style="'.($count[0]["f10"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.0 ГГц ('.$count[0]["f10"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.1" id="frequency_1.1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.1" style="'.($count[0]["f11"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.1 ГГц ('.$count[0]["f11"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.2" id="frequency_1.2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.2" style="'.($count[0]["f12"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.2 ГГц ('.$count[0]["f12"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.3" id="frequency_1.3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.3" style="'.($count[0]["f13"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.3 ГГц ('.$count[0]["f13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.4" id="frequency_1.4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.4" style="'.($count[0]["f14"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.4 ГГц ('.$count[0]["f14"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.5" id="frequency_1.5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.5" style="'.($count[0]["f15"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.5 ГГц ('.$count[0]["f15"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.6" id="frequency_1.6" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.6" style="'.($count[0]["f16"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.6 ГГц ('.$count[0]["f16"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.7" id="frequency_1.7" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.7" style="'.($count[0]["f17"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.7 ГГц ('.$count[0]["f17"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.8" id="frequency_1.8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.8" style="'.($count[0]["f18"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.8 ГГц ('.$count[0]["f18"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.9" id="frequency_1.9" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.9" style="'.($count[0]["f19"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.9 ГГц ('.$count[0]["f19"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.0" id="frequency_2.0" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.0" style="'.($count[0]["f20"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>
                                <span class="selected_value">2.0 ГГц ('.$count[0]["f20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.1" id="frequency_2.1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.1" style="'.($count[0]["f21"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.1 ГГц ('.$count[0]["f21"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.2" id="frequency_2.2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.2" style="'.($count[0]["f22"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.2 ГГц ('.$count[0]["f22"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.3" id="frequency_2.3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.3" style="'.($count[0]["f23"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.3 ГГц ('.$count[0]["f23"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.4" id="frequency_2.4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.4" style="'.($count[0]["f24"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.4 ГГц ('.$count[0]["f24"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.5" id="frequency_2.5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.5" style="'.($count[0]["f25"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.5 ГГц ('.$count[0]["f25"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Камера, Mpx <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Основная камера</p>
                            <div>
                                <input type="checkbox" name="camera[]" value="0-3" id="0-3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="0-3" style="'.($count[0]["cam_0_3"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>
                                <span class="selected_value">до 3 ('.$count[0]["cam_0_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="3-5" id="3-5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="3-5" style="'.($count[0]["cam_3_5"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 3 - до 5 ('.$count[0]["cam_3_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="5-8" id="5-8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="5-8" style="'.($count[0]["cam_5_8"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 5 - до 8 ('.$count[0]["cam_5_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="8-13" id="8-13" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="8-13" style="'.($count[0]["cam_8_13"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 8 - до 13 ('.$count[0]["cam_8_13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="13-20" id="13-20" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="13-20" style="'.($count[0]["cam_13_20"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 13 - до 20 ('.$count[0]["cam_13_20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="20-100" id="20-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="20-100" style="'.($count[0]["cam_20_100"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">более 20 ('.$count[0]["cam_20_100"].')</span></label>
                            </div>
                            <p>Фронтальная камера</p>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="0.1-2" id="front_0-2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_0-2" style="'.($count[0]["front_cam_0_2"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">до 2 ('.$count[0]["front_cam_0_2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="2-5" id="front_2-5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_2-5" style="'.($count[0]["front_cam_2_5"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 2 - до 5 ('.$count[0]["front_cam_2_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="5-100" id="front_5-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_5-100" style="'.($count[0]["front_cam_5_100"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">более 5 ('.$count[0]["front_cam_5_100"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="нет" id="front_no" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_no" style="'.($count[0]["front_cam_no"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">нет ('.$count[0]["front_cam_no"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Память <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Оперативная память</p>
                            <div>
                                <input type="checkbox" name="ram[]" value="0.1-0.512" id="ram_0.1-0.512" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_0.1-0.512" style="'.($count[0]["ram_0_512"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">до 512 мб ('.$count[0]["ram_0_512"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="0.512-1" id="ram_0.512-1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_0.512-1" style="'.($count[0]["ram_512_1"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">512 мб - 1 Гб ('.$count[0]["ram_512_1"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="1-2" id="ram_1-2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_1-2" style="'.($count[0]["ram_1_2"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">1Гб - 2 Гб ('.$count[0]["ram_1_2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="2-3" id="ram_2-3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_2-3" style="'.($count[0]["ram_2_3"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">2Гб - 3Гб ('.$count[0]["ram_2_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="3-100" id="ram_3-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_3-100" style="'.($count[0]["ram_3_100"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">более 3Гб ('.$count[0]["ram_3_100"].')</span></label>
                            </div>
                            <p>Встроенная память</p>
                            <div>
                                <input type="checkbox" name="rom[]" value="0.1-4" id="rom_less4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_less4" style="'.($count[0]["rom_0_4"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">до 4 Гб ('.$count[0]["rom_0_4"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="8" id="rom_8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_8" style="'.($count[0]["rom_8"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">8 Гб ('.$count[0]["rom_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="16" id="rom_16" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_16" style="'.($count[0]["rom_16"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">16 Гб ('.$count[0]["rom_16"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="32" id="rom_32" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_32" style="'.($count[0]["rom_32"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">32 Гб ('.$count[0]["rom_32"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="64" id="rom_64" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_64" style="'.($count[0]["rom_64"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">64 Гб ('.$count[0]["rom_64"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="128" id="rom_128" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_128" style="'.($count[0]["rom_128"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">128 Гб ('.$count[0]["rom_128"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Беспроводные технологии <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            
                            <p>GPS</p>
                            <div>
                                <input type="checkbox" name="GPS[]" value="A-GPS" id="GPS_1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_1" style="'.($count[0]["gps_A_GPS"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">A-GPS ('.$count[0]["gps_A_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="A-GPS/GPS" id="GPS_2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_2" style="'.($count[0]["gps_A_GPS_GPS"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">A-GPS/GPS ('.$count[0]["gps_A_GPS_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="GPS" id="GPS_3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_3" style="'.($count[0]["gps_GPS"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">GPS ('.$count[0]["gps_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="нет GPS" id="GPS_4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_4" style="'.($count[0]["gps_no"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">нет ('.$count[0]["gps_no"].')</span></label>
                            </div>
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Аккумулятор <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Емкость аккумулятора</p>
                            <div>
                                <input type="checkbox" name="battery[]" value="0.1-3000" id="battery_0.1-3000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_0.1-3000" style="'.($count[0]["batar_0_3000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">до 3000 mAh ('.$count[0]["batar_0_3000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="3000-5000" id="battery_3000-5000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_3000-5000" style="'.($count[0]["batar_3000_5000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">3000 - 5000 mAh ('.$count[0]["batar_3000_5000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="5000-8000" id="battery_5000-8000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_5000-8000" style="'.($count[0]["batar_5000_8000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">5000-8000 mAh ('.$count[0]["batar_5000_8000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="8000-100000" id="battery_8000-100000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_8000-100000" style="'.($count[0]["batar_more_8000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">более 8000 mAh ('.$count[0]["batar_more_8000"].')</span></label>
                            </div>
                        </div>  
                    </div>      
                  ';
        }
        
        
        if($category == 3 && $count)
        {
            echo '<div class="goods_filter_selected">
                    <p>Общие параметры <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                  </div>
                  <div class="filter_selected">
                      <div class="selected_item">
                         <div>
                             <input type="checkbox" name="common[]" value="promotion" id="promotion" class="ccc" style = "display:none">
                             <label class="goods_content_label" for="promotion" style="'.($count_promotion==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>
                             <span class="selected_value">Акции ('.$count_promotion.')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="top" id="top" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="top" style="'.($count_top==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Хит продаж ('.$count_top.')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="novelty" id="novelty" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="novelty" style="'.($count_novelty==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Новинка ('.$count_novelty.')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="bestPrice" id="bestPrice" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="bestPrice" style="'.($count_bestPrice==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Лучшая цена ('.$count_bestPrice.')</span></label>
                         </div>
                         <p>Производитель</p>';
                         $arr = array();
                 foreach($model as $item)
                 {
                    $arr[] = $item->brandModel->brand;
                 }
                         
                 $m = array_unique($arr); 
                 $j = 0;      
                 foreach($m as $k=>$item)
                 {
                    $j++;
                    $i = 0;
                    foreach($count_maker as $n=>$maker)
                    {
                        $i++;
                        if($j == $i)
                        {
                            echo '<div>
                                     <input type="checkbox" name="brand[]" value="'.$item.'" id="brand_'.$item.'" class="ccc" style = "display:none" >
                                     <label class="goods_content_label" for="brand_'.$item.'" style="'.($maker==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                     <span class="selected_value">'.$item.' ('.$maker.')</span></label>
                                 </div>';
                        }
                    }
                    
                 } 
                            
                 echo    '
                          <p>Тип</p>
                          <div>
                             <input type="checkbox" name="type[]" value="Ноутбук" id="type_notebook" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="type_notebook" style="'.($count[0]["notebook"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Ноутбук ('.$count[0]["notebook"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="type[]" value="Нетбук" id="type_netbook" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="type_netbook" style="'.($count[0]["tel"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>    
                             <span class="selected_value">Нетбук ('.$count[0]["netbook"].')</span></label>
                         </div>
                         
                         <p>Операционная система</p>
                         <div>
                             <input type="checkbox" name="os[]" value="Linux" id="os_linux" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_linux" style="'.($count[0]["os_linux"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Linux ('.$count[0]["os_linux"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows 8" id="os_Windows_8" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Windows_8" style="'.($count[0]["os_Windows_8"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows 8 ('.$count[0]["os_Windows_8"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows 7" id="os_Windows_7" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Windows_7" style="'.($count[0]["os_Windows_7"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows 7 ('.$count[0]["os_Windows_7"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows 10" id="os_Windows_10" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Windows_10" style="'.($count[0]["os_Windows_10"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows 10 ('.$count[0]["os_Windows_10"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Mac OS" id="os_Mac_OS" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Mac_OS" style="'.($count[0]["os_Mac_OS"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Mac OS ('.$count[0]["os_Mac_OS"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows 8.1" id="os_Windows_81" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Windows_81" style="'.($count[0]["os_Windows_81"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows 8.1 ('.$count[0]["os_Windows_81"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows RT" id="os_Windows_RT" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Windows_RT" style="'.($count[0]["os_Windows_RT"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows RT ('.$count[0]["os_Windows_RT"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Linpus" id="os_Linpus" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_Linpus" style="'.($count[0]["os_Linpus"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Linpus ('.$count[0]["os_Linpus"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Без ОС" id="no_os" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="no_os" style="'.($count[0]["no_os"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Без ОС ('.$count[0]["no_os"].')</span></label>
                         </div>
                         
                         
                         
                     </div>  
                </div>
                <div class="goods_filter_selected">
                    <p>Экран <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                </div>
                <div class="filter_selected">
                      <div class="selected_item">
                         <p>Диагональ экрана</p>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="0-13" id="diagonal_0_13" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="diagonal_0_13" style="'.($count[0]["diagonal_0_13"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">до 13" ('.$count[0]["diagonal_0_13"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="13-16" id="diagonal_13_16" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="diagonal_13_16" style="'.($count[0]["diagonal_13_16"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">13"-16" ('.$count[0]["diagonal_13_16"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="16-18" id="diagonal_16_18" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="diagonal_16_18" style="'.($count[0]["diagonal_16_18"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">16"-18" ('.$count[0]["diagonal_16_18"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="18-100" id="diagonal_18_100" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="diagonal_18_100" style="'.($count[0]["diagonal_18_100"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">18" и более ('.$count[0]["diagonal_18_100"].')</span></label>
                         </div>
                         
                         
                      </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Процессор <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Процессор</p>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core i7" id="Intel_Core_i7" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_i7" style="'.($count[0]["Intel_Core_i7"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">Intel Core i7 ('.$count[0]["Intel_Core_i7"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core i5" id="Intel_Core_i5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_i5" style="'.($count[0]["Intel_Core_i5"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core i5 ('.$count[0]["Intel_Core_i5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core i3" id="Intel_Core_i3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_i3" style="'.($count[0]["Intel_Core_i3"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core i3 ('.$count[0]["Intel_Core_i3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core M3" id="Intel_Core_M3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_M3" style="'.($count[0]["Intel_Core_M3"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core M3 ('.$count[0]["Intel_Core_M3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core M" id="Intel_Core_M" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_M" style="'.($count[0]["Intel_Core_M"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core M ('.$count[0]["Intel_Core_M"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core Pentium" id="Intel_Core_Pentium" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_Pentium" style="'.($count[0]["Intel_Core_Pentium"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core Pentium ('.$count[0]["Intel_Core_Pentium"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core Celeron" id="Intel_Core_Celeron" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_Celeron" style="'.($count[0]["Intel_Core_Celeron"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core Celeron ('.$count[0]["Intel_Core_Celeron"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="Intel Core Atom" id="Intel_Core_Atom" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Intel_Core_Atom" style="'.($count[0]["Intel_Core_Atom"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">Intel Core Atom ('.$count[0]["Intel_Core_Atom"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="nVidia" id="nVidia" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="nVidia" style="'.($count[0]["nVidia"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">nVidia ('.$count[0]["nVidia"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="AMD FX" id="AMD_FX" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="AMD_FX" style="'.($count[0]["AMD_FX"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">AMD FX ('.$count[0]["AMD_FX"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="AMD E" id="AMD_E" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="AMD_E" style="'.($count[0]["AMD_E"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">AMD E ('.$count[0]["AMD_E"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="AMD A10" id="AMD_A10" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="AMD_A10" style="'.($count[0]["AMD_A10"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">AMD A10 ('.$count[0]["AMD_A10"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="AMD A8" id="AMD_A8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="AMD_A8" style="'.($count[0]["AMD_A8"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">AMD A8 ('.$count[0]["AMD_A8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="AMD A6" id="AMD_A6" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="AMD_A6" style="'.($count[0]["AMD_A6"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">AMD A6 ('.$count[0]["AMD_A6"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cpu_type[]" value="AMD A4" id="AMD_A4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="AMD_A4" style="'.($count[0]["AMD_A4"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">AMD A4 ('.$count[0]["AMD_A4"].')</span></label>
                            </div>
                        
                        
                            <p>Количесвтво ядер</p>
                            <div>
                                <input type="checkbox" name="core[]" value="x2" id="core_x2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x2" style="'.($count[0]["core_x2"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">x2 ('.$count[0]["core_x2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x4" id="core_x4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x4" style="'.($count[0]["core_x4"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">x4 ('.$count[0]["core_x4"].')</span></label>
                            </div>
                            
                            
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Графический адаптер <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Объем видеокарты</p>
                            <div>
                                <input type="checkbox" name="camera[]" value="0-3" id="0-3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="0-3" style="'.($count[0]["cam_0_3"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>
                                <span class="selected_value">... ('.$count[0]["cam_0_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="3-5" id="3-5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="3-5" style="'.($count[0]["cam_3_5"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">... ('.$count[0]["cam_3_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="5-8" id="5-8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="5-8" style="'.($count[0]["cam_5_8"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">... ('.$count[0]["cam_5_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="8-13" id="8-13" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="8-13" style="'.($count[0]["cam_8_13"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">... ('.$count[0]["cam_8_13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="13-20" id="13-20" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="13-20" style="'.($count[0]["cam_13_20"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">... ('.$count[0]["cam_13_20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="20-100" id="20-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="20-100" style="'.($count[0]["cam_20_100"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">... ('.$count[0]["cam_20_100"].')</span></label>
                            </div>
                            
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Память <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <p>Оперативная память</p>
                            <div>
                                <input type="checkbox" name="ram[]" value="0.1-3.99" id="ram_0_399" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_0_399" style="'.($count[0]["ram_0_399"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">до 4ГБ ('.$count[0]["ram_0_399"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="4-6" id="ram_4_6" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_4_6" style="'.($count[0]["ram_4_6"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">4-6 ГБ ('.$count[0]["ram_4_6"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="8-10" id="ram_8_10" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_8_10" style="'.($count[0]["ram_8_10"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">8-10ГБ ('.$count[0]["ram_8_10"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="12-100" id="ram_12_100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_12_100" style="'.($count[0]["ram_12_100"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">12 ГБ и более ('.$count[0]["ram_12_100"].')</span></label>
                            </div>
                            
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Накопители данных <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            
                            <p>Жесткий диск HDD</p>
                            <div>
                                <input type="checkbox" name="GPS[]" value="0-499.99" id="HDD_0_499" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="HDD_0_499" style="'.($count[0]["HDD_0_499"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">до 500 ГБ ('.$count[0]["HDD_0_499"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="500-749.99" id="HDD_500_750" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="HDD_500_750" style="'.($count[0]["HDD_500_750"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">500-750 ГБ ('.$count[0]["HDD_500_750"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="750-999.99" id="HDD_750_1000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="HDD_750_1000" style="'.($count[0]["HDD_750_1000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">750 ГБ - 1 ТБ ('.$count[0]["HDD_750_1000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="1000-1999.99" id="HDD_1000_2000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="HDD_1000_2000" style="'.($count[0]["HDD_1000_2000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">1ТБ - 2 ТБ ('.$count[0]["HDD_1000_2000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="2000-10000" id="HDD_2000_10000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="HDD_2000_10000" style="'.($count[0]["HDD_2000_10000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">2 ТБ и более ('.$count[0]["HDD_2000_10000"].')</span></label>
                            </div>
                            
                            <p>Жесткий диск SSD</p>
                            <div>
                                <input type="checkbox" name="GPS[]" value="0-255.99" id="SSD_0_256" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="SSD_0_256" style="'.($count[0]["SSD_0_256"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">до 256 ГБ ('.$count[0]["SSD_0_256"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="256-511.99" id="SSD_256_512" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="SSD_256_512" style="'.($count[0]["SSD_256_512"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">256-512 ГБ ('.$count[0]["SSD_256_512"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="512-999.99" id="SSD_512_1000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="SSD_512_1000" style="'.($count[0]["SSD_512_1000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">512ГБ-1ТБ ('.$count[0]["SSD_512_1000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="1000-10000" id="SSD_1000_10000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="SSD_1000_10000" style="'.($count[0]["SSD_1000_10000"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">1ТБ и более ('.$count[0]["SSD_1000_10000"].')</span></label>
                            </div>
                            
                        </div>  
                    </div>
                    <div class="goods_filter_selected">
                        <p>Оптический привод <span class="glyphicon glyphicon-triangle-bottom"></span></p>
                    </div>
                    <div class="filter_selected">
                        <div class="selected_item">
                            <div>
                                <input type="checkbox" name="battery[]" value="DVD" id="DVD" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="DVD" style="'.($count[0]["DVD"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">DVD ('.$count[0]["DVD"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="Blu-Ray" id="Blu_Ray" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="Blu_Ray" style="'.($count[0]["Blu_Ray"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">Blu-Ray ('.$count[0]["Blu_Ray"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="нет" id="no_DVD" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="no_DVD" style="'.($count[0]["no_DVD"]==0?"opacity: 0.5; cursor:default;":"").'"><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">нет ('.$count[0]["no_DVD"].')</span></label>
                            </div>
                            
                        </div>  
                    </div>      
                  ';
        }
        
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
        
        $id = array();
        foreach ($char as $item)
        {
            $id[] = $item['id'];
        }
        
        
        
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
