<?php
$this->breadcrumbs=array(
	Yii::t('core', 'Memberships')=>array('index'),
	Yii::t('core', 'Edit Membership'),
);
?>

<h1><?php echo Yii::t('core', 'Edit Membership'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'user'=>$user)); ?>