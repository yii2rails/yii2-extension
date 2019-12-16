<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190217_103809_create_common.localizations_table
 * 
 * @package 
 */
class m190217_103809_create_localizations_table extends Migration {

    public $table = 'reference_item_localization';

	/**
	 * @inheritdoc
	 */
	public function getColumns()
	{
		return [
			'id' => $this->primaryKey()->notNull(),
			'reference_item_id' => $this->integer()->notNull(),
			'language_code' => $this->string(4)->notNull(),
			'value' => $this->string()->notNull(),
			'short_value' => $this->string()->notNull(),
			'created_at' => $this->timestamp(),
			'updated_at' => $this->timestamp(),
		];
	}

	public function afterCreate()
	{
		$this->myAddForeignKey(
			'reference_item_id',
			'reference_item',
			'id',
			'CASCADE',
			'CASCADE'
		);
		$this->myAddForeignKey(
			'language_code',
			'language',
			'code',
			'CASCADE',
			'CASCADE'
		);
	}

}