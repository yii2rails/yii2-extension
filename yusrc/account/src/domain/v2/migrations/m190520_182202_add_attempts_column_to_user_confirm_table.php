<?php

use yii2lab\db\domain\db\MigrationAddColumn as Migration;

class m190520_182202_add_attempts_column_to_user_confirm_table extends Migration {

    public  $table = 'user_confirm';

    public function getColumns()
    {
        return [
            'attempts' => $this->integer()->defaultValue(0)->after('is_activated'),
        ];
    }

}
