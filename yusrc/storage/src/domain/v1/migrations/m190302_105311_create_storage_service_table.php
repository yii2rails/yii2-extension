<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190302_105311_create_storage_service_table
 * 
 * @package 
 */
class m190302_105311_create_storage_service_table extends Migration {

	public $table = 'storage_service';
	public $tableComment = 'Профиль хранилища';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
            'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'name' => $this->string()->notNull()->comment('Имя сервиса'),
            'code' => $this->string()->notNull()->comment('Код сервиса'),
			'path' => $this->string()->notNull()->comment('Базовый путь'),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		
	}

}