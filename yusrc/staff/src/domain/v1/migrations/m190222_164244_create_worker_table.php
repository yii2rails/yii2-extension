<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190222_164244_create_staff.workers_table
 * 
 * @package 
 */
class m190222_164244_create_worker_table extends Migration {

	public $table = 'staff_worker';
	public $tableComment = 'Работники компаний';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'company_id' => $this->integer()->notNull()->comment('Компания'),
			'person_id' => $this->integer()->notNull()->comment('Персона'),
			'email' => $this->string(128)->comment('Почта'),
			'post_id' => $this->integer()->notNull()->comment('Должность из справочника'),
			'division_id' => $this->integer()->notNull()->comment('Подразделение'),
			'office' => $this->string(32)->comment('Номер кабинета'),
			'phone' => $this->string(64)->comment('Вн. номер телефона'),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'post_id',
			'reference_item',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myAddForeignKey(
			'company_id',
			'company',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myAddForeignKey(
			'person_id',
			'user_person',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myAddForeignKey(
			'division_id',
			'staff_division',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}