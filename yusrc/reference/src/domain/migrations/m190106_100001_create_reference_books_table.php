<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

class m190106_100001_create_reference_books_table extends Migration {

	public $table = 'reference_book';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'name' => $this->string(128)->notNull()->comment('Название справочника'),
			'levels' => $this->integer()->notNull()->defaultValue(1)->comment('Колличество уровней'),
			'entity' => $this->string(64)->notNull()->comment('Сущность'),
			'owner_id' => $this->integer()->notNull()->comment('Организация владелец'),
			'props' => $this->json()->null()->comment('Индивидуальные параметры'),
			'created_at' => $this->timestamp()->defaultValue(null),
			'updated_at' => $this->timestamp()->defaultValue(null),
			'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус справочника'),
		];
	}

	public function afterCreate()
	{
        $this->myAddForeignKey(
            'owner_id',
            'company',
            'id',
            'CASCADE',
            'CASCADE'
        );
	}

}