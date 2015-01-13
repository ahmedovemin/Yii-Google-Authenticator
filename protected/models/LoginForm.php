<?php

class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
	private $_identity;


	public function rules()
	{
		return array(

			array('username, password', 'required'),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Yadda saxla',
			'username'=>'İstifadəçi adı',
			'password'=>'Şifrə',
		);
	}

	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{	
			$this->password = md5(md5($this->password));
			
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','İstifadəçi adı və ya Şifrə yalnışdır');
		}
	}


	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username, $this->password );
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			
		    $session            =new CHttpSession;
		    $session->open();
		    $session['user_id'] = $this->_identity->getId();
		    $session['duration']= $duration;

			return true;
		}
		else
			return false;
	}
}
