<?php

use yii2lab\db\domain\db\MigrationCreateTable as Migration;

/**
 * Class m190815_052835_create_storage_policy_table
 *
 * @package
 */
class m190815_052835_create_storage_policy_table extends Migration {

    public $table = 'storage_policy';
    public $tableComment = 'Ограничения';

    /**
     * @inheritdoc
     */
    public function getColumns()
    {
        return [
            'id' => $this->primaryKey()->notNull()->comment('Идентификатор'),
            'service_id' => $this->integer()->notNull()->comment('Идентификатор севиса'),
            'role' => $this->string()->notNull(),
            'file_size' => $this->integer()->null()->comment(''),
            'space_size' => $this->integer()->null()->comment(''),
            'allow_extensions' => $this->json()->null()->comment('Разрешённые расширения файлов'),
            'allow_types' => $this->json()->null()->comment('Резрешённые типы файлов'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ];
    }

    public function afterCreate()
    {

    }

}