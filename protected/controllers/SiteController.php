<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}


	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;


		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(array('site/GoogleAuth'));//Yii::app()->user->returnUrl
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

    public function actionRegistration()
    {
        $model      = new Users();

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='reg-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }


        if(isset($_POST['Users']))
        {
            $model->attributes  =  CHtml::encodeArray($_POST['Users']);

            if($model->save())
            {
                Yii::app()->user->setFlash('success','Qeydiyyat uğurla yerinə yetirildi');
                $this->refresh();
            }

        }


        $ga         = new GoogleAuthenticator();
        $secret     = $ga->createSecret();
        $qrCodeUrl  = $ga->getQRCodeGoogleUrl(Yii::app()->name, $secret);

        $this->render('registration',array('model'=>$model,'qrCodeUrl'=>$qrCodeUrl,'secret'=>$secret));
    }

	public function actionGoogleAuth()
	{
	
			if(!isset(Yii::app()->session['user_id']))
				throw new CHttpException(404,'Bad request');
			
			
			if (isset($_POST['key'])){
			
					$key = CHtml::encode($_POST['key']);
			
					$user  = Users::model()->findByPk(Yii::app()->session['user_id']);

					$ga = new GoogleAuthenticator();
					
					$checkResult = $ga->verifyCode($user->ga_secret_key, $key, 2);    // 2 = 2*30sec clock tolerance
					
					
					if ($checkResult)
					{
						$identity = new UserIdentity($user->login,$user->password);
						$identity->authenticate();
						if($identity->getIsAuthenticated())
						{
							Yii::app()->user->login($identity,Yii::app()->session['duration']);

							unset(Yii::app()->session['user_id']);
							unset(Yii::app()->session['duration']);

							$this->redirect(Yii::app()->homeUrl);
						
						}
					}
					

			}
			
			$this->render('googleauth');
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}