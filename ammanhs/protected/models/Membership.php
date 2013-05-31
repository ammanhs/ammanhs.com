<?php

/**
 * This is the model class for table "{{membership}}".
 *
 * The followings are the available columns in table '{{membership}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property integer $status
 * @property string $organization
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Membership extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Membership the static model class
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
		return '{{membership}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, type, organization, title', 'required'),
			array('user_id, status, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>6),
			array('organization', 'length', 'max'=>256),
			array('title', 'length', 'max'=>128),
			array('id, user_id, status, created_at, updated_at', 'unsafe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, type, status, organization, title, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'type' => Yii::t('core', 'Membership Type'),
			'status' => 'Status',
			'organization' => Yii::t('core', 'Organization'),
			'title' => Yii::t('core', 'Job Title'),
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
		$criteria->compare('organization',$this->organization,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function beforeValidate() {
		if(!$this->user_id){
        	$this->user_id=Yii::app()->user->id;
        }
        return true;
    }

    public function beforeSave()
	{
		if(!$this->created_at){
			$this->created_at=time();
        }else{
        	$this->updated_at=time();
        }
        if($this->type=='Free')
        	$this->status=10;
        return parent::beforeSave();
	}

	public function afterSave()
	{
		parent::afterSave();
		if($this->isNewRecord){
			$this->user->stat_points+=3;
			$this->user->save(false);
		}
	}
}