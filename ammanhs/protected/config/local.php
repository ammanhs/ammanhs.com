<?php
return array(
'components'=>array(
		'db'=>array(
			'class'=>'CDbConnection',
			'tablePrefix'=>'tbl_',
			'connectionString'=>'mysql:host=localhost;dbname=ammanhs',
			'emulatePrepare'=>true,
			'username'=>'root',
			'password'=>'',
			'charset'=>'utf8',
			//'schemaCachingDuration'=>3600,
			'enableProfiling'=>true,
		),
	),
);