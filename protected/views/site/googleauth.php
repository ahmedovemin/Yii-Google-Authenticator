<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Addım 2';
$this->breadcrumbs=array(
	'Addım 2',
);
?>

<h1>Addım 2</h1>


<div class="form">
<?php echo CHtml::beginForm(); ?>

<div class="row">
<?php echo CHtml::label('Kod','key'); ?>
<?php echo CHtml::textField('key'); ?>
</div>
<div class="row submit">
<?php echo CHtml::submitButton('Daxil ol'); ?>
</div>
<?php echo CHtml::endForm(); ?>
</div><!-- form -->