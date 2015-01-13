<?php if(Yii::app()->user->hasFlash('success')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>

<?php else: ?>


<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'reg-form',
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <?= $form->errorSummary($model);?>


    <div class="row">
        <?php echo $form->labelEx($model,'login'); ?>
        <?php echo $form->textField($model,'login'); ?>
        <?php echo $form->error($model,'login'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>

    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'repeatpassword'); ?>
        <?php echo $form->passwordField($model,'repeatpassword'); ?>
        <?php echo $form->error($model,'repeatpassword'); ?>

    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'ga_secret_key'); ?>
        <?php echo $form->hiddenField($model,'ga_secret_key',array('value'=>$secret)); ?>
        <img src="<?=$qrCodeUrl?>" />
        <br>
        <p class="hint">
          Məxfi açarı Google Auth-a manual olaraq əlavə etmək istəyirsinizsə aşağıdakı kodu daxil edib yadda saxlayın.
          Məxfi açar hər submit-də yenilənir diqqət olunması xahiş olunur
        </p>
        <br>
        <p><?=$secret?></p>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Qeyd ol'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->

<?php endif; ?>