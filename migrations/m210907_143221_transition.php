<?php

use yii\db\Migration;

/**
 * Class m210907_143221_transition
 */
class m210907_143221_transition extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%transition}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'state_from' => $this->integer()->notNull(),
            'state_to' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("transition_state_from_fk", "{{%transition}}", "state_from",
            "{{%state}}", "id", 'CASCADE');
        $this->addForeignKey("transition_state_to_fk", "{{%transition}}", "state_to",
            "{{%state}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%transition}}');
    }
}