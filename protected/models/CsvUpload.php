<?php

class CsvUpload extends CFormModel
{
    //public $csvFile;
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('csv','file','types'=>'csv', 'message'=>'Загрузите файл', 'allowEmpty'=>'true'),
		);
	}
    
    public function attributeLabels()
    {
      return array(
        'csvFile'=>'Выберите файл формата csv',
      );
    }


}    