<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190302_105310_create_storage_file_type_table
 * 
 * @package 
 */
class m190302_105310_create_storage_file_type_table extends Migration {

	public $table = 'storage_file_type';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
            'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
            'code' => $this->string(32)->notNull(),
			'name' => $this->string()->notNull(),
			'icon_file' => $this->string()->notNull(),
			'handler_name' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		
	}

}