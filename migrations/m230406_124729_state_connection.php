<?php

use yii\db\Migration;

/**
 * Class m230406_124729_state_connection
 */
class m230406_124729_state_connection extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%state_connection}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'start_to_end' => $this->integer()->notNull(),
            'state' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("state_connection_start_to_end_fk", "{{%state_connection}}",
            "start_to_end","{{%start_to_end}}", "id", 'CASCADE');

        $this->addForeignKey("state_connection_state_fk", "{{%state_connection}}",
            "state", "{{%state}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%state_connection}}');
    }
}
