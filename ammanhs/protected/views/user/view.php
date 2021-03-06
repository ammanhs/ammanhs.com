<?php
$this->pageTitle=$model->name.' - '.Yii::t('core', 'Amman Hackerspace');
$this->breadcrumbs=array(
	$model->name,
);
$cs=Yii::app()->clientScript;
$cs->registerMetaTag($model->name, 'twitter:title');
$cs->registerMetaTag($model->name , 'og:title');

$description=Yii::t('core', 'Member since :date', array(':date'=>Date('Y/m/d', $model->created_at))).' | ';
if($model->about){
	$description.=$model->about;
}else{
	$description.=Yii::t('core', 'site description');
}
$cs->registerMetaTag($description, 'description', null, array(), 'metadescription');
$cs->registerMetaTag($description, 'twitter:description');
$cs->registerMetaTag($description, 'og:description');
$cs->registerCSSFile('/css/user.css?v=3.3');
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h2><?php echo $model->name; ?></h2>

<div class="row">
	<div class="span2">
		<div id="user-info-box">
			<?php echo $model->avatar(160, 160); ?>
		    <div id="user-stats">
		    	<h4 class="user-stat">
		    		<span class="octicons octicon-star" data-original-title="<?php echo Yii::t('core','Stat Points'); ?>"><?php echo '<br>'.$model->stat_points; ?></span>
		    	</h4>
		    	<h4 class="user-stat">
		    		<span class="octicons octicon-repo" data-original-title="<?php echo Yii::t('core','Stat Threads'); ?>"><?php echo '<br>'.$model->stat_threads; ?></span>
		    	</h4>
		    	<h4 class="user-stat">
		    		<span class="octicons octicon-comment" data-original-title="<?php echo Yii::t('core','Stat Replies'); ?>"><?php echo '<br>'.$model->stat_replies; ?></span>
		    	</h4>
		    </div>
		    <div id="user-info">
			    <?php if($model->country){ ?>
			    <div>
			    	<span class="octicons octicon-location muted"></span><span><?php echo $model->country; ?></span>
				</div>
			    <?php } ?>
			    <?php if($model->website){ ?>
			    <div id="user-website">
			    	<span class="octicons octicon-link muted"></span><a href="http://<?php echo $model->website; ?>" target="_blank" rel="nofollow" title="<?php echo $model->website; ?>"><?php echo $model->website; ?></a>
				</div>
			    <?php } ?>
			    <div>
			    	<span class="octicons octicon-clock muted"></span><?php echo Yii::t('core', 'Member since :time', array(':time'=>Date('Y/m/d', $model->created_at))); ?>
			    </div>
			    <?php if($model->about){ ?>
			    <div>
			    	<div id="about" <?php if(!Text::isArabic($model->about)) echo ' style="text-align: left;" class="english-field"'; ?>><?php echo $model->about; ?></div>
				</div>
			    <?php } ?>
		    </div>
		</div>
	</div>
	<div class="span7">
		<?php
		foreach($user_feed as $feed){
			switch ($feed->action) {
				case 'Add':
					$view='_added';
					break;
				case 'Join':
					$view='_joint';
					break;
			}
			if($feed->thread_id){
				$view.='_thread';
			}elseif($feed->thread_reply_id){
				$view.='_thread_reply';
			}elseif($feed->targeted_user_id){
				$view.='_user';
			}
			$this->renderPartial($view, array('feed'=>$feed));
		}
		?>
	</div>
</div>