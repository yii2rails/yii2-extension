<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190222_164654_create_company_table
 * 
 * @package 
 */
class m190104_100001_create_company_table extends Migration {

	public $table = 'company';
    public $tableComment = 'Компании';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
			'code' => $this->integer()->comment('Код (ИИН/БИН)'),
			'name' => $this->string()->notNull()->comment('Название организации'),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
		];
	}

	public function afterCreate()
	{
		
	}

}