<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190302_105313_create_storage_file_table
 * 
 * @package 
 */
class m190302_105313_create_storage_file_table extends Migration {

	public $table = 'storage_file';
    public $tableComment = 'Файл';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
            'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'service_id' => $this->integer()->notNull()->comment('Сервис'),
            'entity_id' => $this->integer()->notNull()->comment('ID внешней сущности'),
			'editor_id' => $this->integer()->notNull()->comment('Автор'),
			'hash' => $this->string(8)->notNull()->comment('Хэш содержимого'),
			'extension' => $this->string(32)->comment('Расширение файла'),
            'size' => $this->integer()->notNull()->comment('Размер файла'),
			'name' => $this->string()->comment('Имя'),
			'description' => $this->text()->comment('Описание'),
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
		/*$this->myAddForeignKey(
			'extension',
			'storage_file_extension',
			'code',
			'CASCADE',
			'CASCADE'
		);*/
		$this->myCreateIndexUnique(['service_id', 'hash', 'extension', 'entity_id']);
	}

}