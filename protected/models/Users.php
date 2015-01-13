<?php

class Users extends CActiveRecord
{

    public $repeatpassword;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'users';
	}

	public function rules()
	{

		return array(
			array('login,repeatpassword, password, ga_secret_key', 'required'),
			array('login', 'length', 'max'=>30),
			array('login', 'unique'),
			array('password', 'length', 'max'=>50),
            array('repeatpassword', 'compare', 'compareAttribute'=>'password'),
			array('ga_secret_key', 'length', 'max'=>16),

			array('id, login, repeatpassword, password, ga_secret_key', 'safe'),
		);
	}

    public function beforeSave()
    {
        $this->password =  md5(md5($this->password));
        return parent::beforeSave();
    }

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'İstifadəçi adı',
			'password' => 'Parol',
			'repeatpassword' => 'Təkrar parol',
			'ga_secret_key' => 'Məxfi açar',
		);
	}


	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('ga_secret_key',$this->ga_secret_key,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}