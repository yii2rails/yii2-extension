<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190214_043954_create_clients_table
 * 
 * @package 
 */
class m190214_043954_create_clients_table extends Migration {

	public $table = 'portal_client';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'person_id' => $this->integer()->notNull()->comment('Персона'),
			'balance' => $this->double()->notNull()->comment('Баланс клиента'),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'person_id',
			'user_person',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}