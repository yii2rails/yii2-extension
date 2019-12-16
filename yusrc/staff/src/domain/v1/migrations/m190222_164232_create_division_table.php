<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190222_164232_create_staff.divisions_table
 * 
 * @package 
 */
class m190222_164232_create_division_table extends Migration {

	public $table = 'staff_division';
	public $tableComment = 'Подразделения в компания';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'company_id' => $this->integer()->notNull()->comment('Компания'),
			'parent_id' => $this->integer()->comment('Вышестоящее подразделение'),
			'name' => $this->string(256)->notNull()->comment('Наименование подразделения'),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'company_id',
			'company',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myAddForeignKey(
			'parent_id',
			'staff_division',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}