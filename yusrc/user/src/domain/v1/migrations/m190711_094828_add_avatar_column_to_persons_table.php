<?php

use yii2lab\db\domain\db\MigrationAddColumn as Migration;

/**
 * Handles adding avatar_hash to table `{{%persons}}`.
 */
class m190711_094828_add_avatar_column_to_persons_table extends Migration
{

    public  $table = 'user_person';

    public function getColumns()
    {
        return [
            'avatar' => $this->string()->null(),
        ];
    }

}
