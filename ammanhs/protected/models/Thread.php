<?php

/**
 * This is the model class for table "{{thread}}".
 *
 * The followings are the available columns in table '{{thread}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property integer $status
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $stat_replies
 * @property integer $stat_votes
 * @property integer $stat_views
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $publish_status
 *
 * The followings are the available model relations:
 * @property User $user
 * @property ThreadReply[] $threadReplys
 */
class Thread extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Thread the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{thread}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, title, content, type', 'required'),
			array('user_id, status, stat_replies, stat_votes, stat_views, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>12),
			array('title', 'length', 'max'=>256),
			array('tags', 'length', 'max'=>128),
			array('content', 'safe'),
			array('user_id, publish_status', 'unsafe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, type, status, title, content, tags, stat_replies, stat_votes, stat_views, created_at, updated_at, publish_status', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'replies' => array(self::HAS_MANY, 'ThreadReply', 'thread_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'type' => 'Type',
			'status' => 'Status',
			'title' => 'Title',
			'content' => 'Content',
			'tags' => 'Tags',
			'stat_replies' => 'Stat Replies',
			'stat_votes' => 'Stat Votes',
			'stat_votes' => 'Stat Views',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('stat_replies',$this->stat_replies);
		$criteria->compare('stat_votes',$this->stat_votes);
		$criteria->compare('stat_views',$this->stat_views);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeValidate() {
		if(!$this->user_id){
        	$this->user_id = Yii::app()->user->id;
        }
        return true;
    }

	public function beforeSave()
	{
		if(!$this->created_at){
			$this->created_at = time();
        }else{
        	$this->updated_at = time();
        }
        return parent::beforeSave();
	}

	public function afterSave()
	{
		parent::afterSave();
		if($this->isNewRecord){
			UserLog::addActivity('Create', $this, 3);
			$this->user->stat_points+=3;
			$this->user->stat_threads++;
			$this->user->save();
		}
	}

	public function updateStatVotes(){
		$positive_votes = COUNT(ThreadVote::model()->findByAttributes(array('thread_id'=>$this->id, 'vote_type'=>1)));
		$negative_votes = COUNT(ThreadVote::model()->findByAttributes(array('thread_id'=>$this->id, 'vote_type'=>-1)));
		$this->stat_votes = (int)($positive_votes - $negative_votes);
		return $this->save();
	}

	public function getLink($absolute=false){
		if ($absolute || !(Yii::app() instanceof CWebApplication))
			return Yii::app()->urlManager->createAbsoluteUrl('thread/show', array('id' => $this->id));
		return Yii::app()->urlManager->createUrl('thread/show', array('id' => $this->id));
	}
}