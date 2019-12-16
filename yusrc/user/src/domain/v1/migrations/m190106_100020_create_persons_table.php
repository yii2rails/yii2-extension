<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

class m190106_100020_create_persons_table extends Migration {

	public $table = 'user_person';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'sex_id' => $this->integer()->comment('Пол (может быть не указан)'),
			'code' => $this->string(12)->comment('ИИН'),
			'first_name' => $this->string(32)->notNull()->comment('Имя'),
			'last_name' => $this->string(32)->notNull()->comment('Фамилия'),
			'middle_name' => $this->string(32)->comment('Отчество'),
			'phone' => $this->string(32)->notNull()->comment('Номер телефона'),
			'birthday' => $this->date()->comment('Дата рождения'),
			'email' => $this->string(32)->comment('Внешняя почта'),
			'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
		];
	}

	public function afterCreate()
	{
	    $this->myCreateIndexUnique(['phone']);
		$this->myAddForeignKey(
			'sex_id',
			'reference_item',
			'id',
			'CASCADE',
			'CASCADE'
		);
	}

}