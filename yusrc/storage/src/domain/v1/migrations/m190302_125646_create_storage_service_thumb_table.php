<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190302_125646_create_storage_service_thumb_table
 * 
 * @package 
 */
class m190302_125646_create_storage_service_thumb_table extends Migration {

	public $table = 'storage_service_thumb';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'service_id' => $this->integer()->notNull()->comment('Сервис'),
			'width' => $this->integer(5)->notNull()->comment('Ширина'),
			'height' => $this->integer(5)->notNull()->comment('Высота'),
			'extension' => $this->string(32)->notNull()->comment('Расширение файла'),
			'quality' => $this->integer(3)->notNull()->defaultValue(90)->comment('Качество'),
			'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'service_id',
			'storage_service',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myAddForeignKey(
			'extension',
			'storage_file_extension',
			'code',
			'CASCADE',
			'CASCADE'
		);
        $this->myCreateIndexUnique(['service_id', 'width', 'height', 'extension']);
	}

}