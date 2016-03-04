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
        if($category == 1)
        {
            echo '<div class="goods_filter_selected">
                    <p>Общие параметры <span class="glyphicon glyphicon-triangle-bottom"></p>
                  </div>
                  <div class="filter_selected">
                      <div class="selected_item">
                         <div>
                             <input type="checkbox" name="common[]" value="promotion" id="promotion" class="ccc" style = "display:none">
                             <label class="goods_content_label" for="promotion" disabled><span class="glyphicon glyphicon-unchecked"></span>
                             <span class="selected_value">Акции ('.$count_promotion.')</span>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="top" id="top" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="top" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Хит продаж ('.$count_top.')</span>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="novelty" id="novelty" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="novelty" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Новинка ('.$count_novelty.')</span>
                         </div>
                         <div>
                             <input type="checkbox" name="common[]" value="bestPrice" id="bestPrice" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="bestPrice" disabled><span class="glyphicon glyphicon-unchecked"></span> 
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
                                 <input type="checkbox" name="brand[]" value="'.$item.'" id="brand_'.$item.'" class="ccc" style = "display:none" >
                                 <label class="goods_content_label" for="brand_'.$item.'" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                 <span class="selected_value">'.$item.' ('.$maker.')</span>
                             </div>';
                        }
                    }
                    
                 } 
                            
                 echo    '
                          <p>Тип</p>
                          <div>
                             <input type="checkbox" name="type[]" value="Смартфон" id="type_smartphone" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="type_smartphone" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Смартфон ('.$count[0]["smart"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="type[]" value="Телефон" id="type_telephone" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="type_telephone" disabled><span class="glyphicon glyphicon-unchecked"></span>    
                             <span class="selected_value">Телефон ('.$count[0]["tel"].')</span></label>
                         </div>
                         <p>Форм-фактор</p>
                         <div>
                             <input type="checkbox" name="form[]" value="Кнопочный моноблок" id="form_mono" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="form_mono" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Кнопочный моноблок ('.$count[0]["button_mono"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="form[]" value="Раскладушка" id="form_transf" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="form_transf" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                             <span class="selected_value">Раскладушка ('.$count[0]["trasformer"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="form[]" value="Сенсорный моноблок" id="form_sensor" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="form_sensor" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                             <span class="selected_value">Сенсорный моноблок ('.$count[0]["sensor_mono"].')</span></label>
                         </div>
                         <p>Операционная система</p>
                         <div>
                             <input type="checkbox" name="os[]" value="Android" id="os_android" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_android" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Android ('.$count[0]["android"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="iOS" id="os_iOS" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_iOS" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">iOS ('.$count[0]["ios"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="Windows" id="os_windows" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_windows" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">Windows ('.$count[0]["windows"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="os[]" value="без ОС" id="os_no" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="os_no" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                             <span class="selected_value">без ОС ('.$count[0]["no_os"].')</span></label>
                         </div>
                         <p>Количество Sim-карт</p>
                         <div>
                             <input type="checkbox" name="sim[]" value="1 sim" id="sim1" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="sim1" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">1 sim ('.$count[0]["1sim"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="sim[]" value="2 sim" id="sim2" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="sim2" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">2 sim ('.$count[0]["2sim"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="sim[]" value="3 sim" id="sim3" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="sim3" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">3 sim ('.$count[0]["3sim"].')</span></label>
                         </div>
                         <p>Степень защиты</p>
                         <div>
                             <input type="checkbox" name="protection[]" value="нет" id="protection_no" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="protection_no" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">нет ('.$count[0]["no_protect"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="protection[]" value="ip67" id="protection_ip67" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="protection_ip67" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">ip67 ('.$count[0]["ip67"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="protection[]" value="ip68" id="protection_ip68" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="protection_ip68" disabled><span class="glyphicon glyphicon-unchecked"></span>
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
                             <input type="checkbox" name="diagonal[]" value="0-3.9" id="less39" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="less39" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">до 3.9 ('.$count[0]["diagonal_0_39"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="4.0-4.5" id="40-45" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="40-45" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">4.0-4.5 ('.$count[0]["diagonal_40_45"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="4.6-5.0" id="46-50" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="46-50" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">4.6-5.0 ('.$count[0]["diagonal_46_50"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="5.1-5.5" id="51-55" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="51-55" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">5.1-5.5 ('.$count[0]["diagonal_51_55"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="diagonal[]" value="5,5-100,0" id="more55" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="more55" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">свыше 5.5 ('.$count[0]["diagonal_55_1000"].')</span></label>
                         </div>
                         <p>Тип экрана</p>
                         <div>
                             <input type="checkbox" name="screen[]" value="TFT" id="screen_TFT" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_TFT" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">TFT ('.$count[0]["TFT"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="TN" id="screen_TTN" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_TTN" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">TN ('.$count[0]["TN"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="Retina" id="screen_Retina" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_Retina" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Retina ('.$count[0]["Retina"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="IPS" id="screen_IPS" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_IPS" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">IPS ('.$count[0]["IPS"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="Amoled" id="screen_Amoled" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_Amoled" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Amoled ('.$count[0]["Amoled"].')</span></label>
                         </div>
                         <div>
                             <input type="checkbox" name="screen[]" value="SuperAmoled" id="screen_SuperAmoled" class="ccc" style = "display:none" >
                             <label class="goods_content_label" for="screen_SuperAmoled" disabled><span class="glyphicon glyphicon-unchecked"></span> 
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
                                <input type="checkbox" name="core[]" value="x1" id="core_x1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x1" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">x1 ('.$count[0]["x1"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x2" id="core_x2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x2" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">x2 ('.$count[0]["x2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x3" id="core_x3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x3" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">x3 ('.$count[0]["x3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core[]" value="x4" id="core_x4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="core_x4" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">x4 ('.$count[0]["x4"].')</span></label>
                            </div>
                            <p>Частота процессора</p>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.0" id="frequency_1.0" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.0" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.0 ГГц ('.$count[0]["f10"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.1" id="frequency_1.1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.1" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.1 ГГц ('.$count[0]["f11"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.2" id="frequency_1.2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.2" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.2 ГГц ('.$count[0]["f12"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.3" id="frequency_1.3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.3" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.3 ГГц ('.$count[0]["f13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.4" id="frequency_1.4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.4" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.4 ГГц ('.$count[0]["f14"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.5" id="frequency_1.5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.5" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.5 ГГц ('.$count[0]["f15"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.6" id="frequency_1.6" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.6" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.6 ГГц ('.$count[0]["f16"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.7" id="frequency_1.7" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.7" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.7 ГГц ('.$count[0]["f17"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.8" id="frequency_1.8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.8" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.8 ГГц ('.$count[0]["f18"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="1.9" id="frequency_1.9" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_1.9" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1.9 ГГц ('.$count[0]["f19"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.0" id="frequency_2.0" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.0" disabled><span class="glyphicon glyphicon-unchecked"></span>
                                <span class="selected_value">2.0 ГГц ('.$count[0]["f20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.1" id="frequency_2.1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.1" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.1 ГГц ('.$count[0]["f21"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.2" id="frequency_2.2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.2" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.2 ГГц ('.$count[0]["f22"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.3" id="frequency_2.3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.3" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.3 ГГц ('.$count[0]["f23"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.4" id="frequency_2.4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.4" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">2.4 ГГц ('.$count[0]["f24"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="core_frequency[]" value="2.5" id="frequency_2.5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="frequency_2.5" disabled><span class="glyphicon glyphicon-unchecked"></span> 
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
                                <input type="checkbox" name="camera[]" value="0-3" id="0-3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="0-3" disabled><span class="glyphicon glyphicon-unchecked"></span>
                                <span class="selected_value">до 3 ('.$count[0]["cam_0_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="3-5" id="3-5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="3-5" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 3 - до 5 ('.$count[0]["cam_3_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="5-8" id="5-8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="5-8" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 5 - до 8 ('.$count[0]["cam_5_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="8-13" id="8-13" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="8-13" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 8 - до 13 ('.$count[0]["cam_8_13"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="13-20" id="13-20" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="13-20" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 13 - до 20 ('.$count[0]["cam_13_20"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="camera[]" value="20-100" id="20-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="20-100" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">более 20 ('.$count[0]["cam_20_100"].')</span></label>
                            </div>
                            <p>Фронтальная камера</p>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="0.1-2" id="front_0-2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_0-2" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">до 2 ('.$count[0]["front_cam_0_2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="2-5" id="front_2-5" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_2-5" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">от 2 - до 5 ('.$count[0]["front_cam_2_5"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="5-100" id="front_5-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_5-100" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">более 5 ('.$count[0]["front_cam_5_100"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="front_camera[]" value="нет" id="front_no" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="front_no" disabled><span class="glyphicon glyphicon-unchecked"></span> 
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
                                <input type="checkbox" name="ram[]" value="0.1-0.512" id="ram_0.1-0.512" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_0.1-0.512" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">до 512 мб ('.$count[0]["ram_0_512"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="0.512-1" id="ram_0.512-1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_0.512-1" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">512 мб - 1 Гб ('.$count[0]["ram_512_1"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="1-2" id="ram_1-2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_1-2" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">1Гб - 2 Гб ('.$count[0]["ram_1_2"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="2-3" id="ram_2-3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_2-3" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">2Гб - 3Гб ('.$count[0]["ram_2_3"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="ram[]" value="3-100" id="ram_3-100" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="ram_3-100" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">более 3Гб ('.$count[0]["ram_3_100"].')</span></label>
                            </div>
                            <p>Встроенная память</p>
                            <div>
                                <input type="checkbox" name="rom[]" value="0.1-4" id="rom_less4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_less4" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">до 4 Гб ('.$count[0]["rom_0_4"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="8" id="rom_8" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_8" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">8 Гб ('.$count[0]["rom_8"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="16" id="rom_16" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_16" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">16 Гб ('.$count[0]["rom_16"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="32" id="rom_32" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_32" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">32 Гб ('.$count[0]["rom_32"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="64" id="rom_64" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_64" disabled><span class="glyphicon glyphicon-unchecked"></span>   
                                <span class="selected_value">64 Гб ('.$count[0]["rom_64"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="rom[]" value="128" id="rom_128" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="rom_128" disabled><span class="glyphicon glyphicon-unchecked"></span>   
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
                                <input type="checkbox" name="wifi[]" value="есть wifi" id="yes_wifi" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="yes_wifi" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">есть ('.$count[0]["wifi_yes"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="wifi[]" value="нет wifi" id="no_wifi" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="no_wifi" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">нет ('.$count[0]["wifi_no"].')</span></label>
                            </div>
                            <p>GPS</p>
                            <div>
                                <input type="checkbox" name="GPS[]" value="A-GPS" id="GPS_1" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_1" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">A-GPS ('.$count[0]["gps_A_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="A-GPS/GPS" id="GPS_2" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_2" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">A-GPS/GPS ('.$count[0]["gps_A_GPS_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="GPS" id="GPS_3" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_3" disabled><span class="glyphicon glyphicon-unchecked"></span>  
                                <span class="selected_value">GPS ('.$count[0]["gps_GPS"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="GPS[]" value="нет GPS" id="GPS_4" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="GPS_4" disabled><span class="glyphicon glyphicon-unchecked"></span>  
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
                                <input type="checkbox" name="battery[]" value="0.1-1000" id="battery_0.1-1000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_0.1-1000" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">до 1000 mAh ('.$count[0]["batar_0_1000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="1000-1500" id="battery_1000-1500" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_1000-1500" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1000 - 1500 mAh ('.$count[0]["batar_1000_1500"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="1500-2000" id="battery_1500-2000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_1500-2000" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">1500 - 2000 mAh ('.$count[0]["batar_1500_2000"].')</span></label>
                            </div>
                            <div>
                                <input type="checkbox" name="battery[]" value="2000-100000" id="battery_2000-100000" class="ccc" style = "display:none" >
                                <label class="goods_content_label" for="battery_2000-100000" disabled><span class="glyphicon glyphicon-unchecked"></span> 
                                <span class="selected_value">более 2000 mAh ('.$count[0]["batar_more_2000"].')</span></label>
                            </div>
                        </div>  
                    </div>      
                  ';
        }
        
        
        
        if($category == 2)
        {
            echo '<div class="goods_filter_selected">
                    <p>Общие параметры <span class="glyphicon glyphicon-triangle-bottom"></p>
                  </div>
                  <div class="filter_selected">
                      <div class="selected_item">
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Акции</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Хит продаж</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Новинка</span>
                         </div>
                         <div>
                             <span class="glyphicon glyphicon-unchecked"></span> 
                             <span class="selected_value">Лучшая цена</span>
                         </div>
                         <p>Производитель</p>';
                         
             foreach($brands as $item)
             {
                echo '<div>
                         <span class="glyphicon glyphicon-unchecked"></span> 
                         <span class="selected_value">'.$item->brand.'</span>
                     </div>';
             }            
             echo        '</div>
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
