<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190302_105312_create_storage_file_extension_table
 * 
 * @package 
 */
class m190302_105312_create_storage_file_extension_table extends Migration {

	public $table = 'storage_file_extension';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
            'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'code' => $this->string(32)->notNull(),
			'type_id' => $this->integer()->notNull(),
			'mime' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'type_id',
			'storage_file_type',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myCreateIndexUnique(['code']);
	}

}