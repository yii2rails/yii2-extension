<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190302_124730_create_storage_image_table
 * 
 * @package 
 */
class m190302_124730_create_storage_image_table extends Migration {

	public $table = 'storage_image';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'file_id' => $this->integer()->notNull()->comment('Файл'),
			'width' => $this->integer()->notNull()->comment('Ширина'),
			'height' => $this->integer()->notNull()->comment('Высота'),
			'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'file_id',
			'storage_file',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myCreateIndexUnique(['file_id', 'width', 'height']);
	}

}