<?php

use yii\db\Migration;

/**
 * Class m231023_112245_virtual_assistant_model
 */
class m231023_112245_virtual_assistant_model extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%virtual_assistant_model}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'dialogue_model' => $this->integer()->notNull(),
            'target_model' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'virtual_assistant_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("virtual_assistant_model_dialogue_model_fk", "{{%virtual_assistant_model}}", "dialogue_model",
            "{{%diagram}}", "id", 'CASCADE');

        $this->addForeignKey("virtual_assistant_model_target_model_fk", "{{%virtual_assistant_model}}", "target_model",
            "{{%diagram}}", "id", 'CASCADE');

        $this->addForeignKey("virtual_assistant_model_virtual_assistant_id_fk", "{{%virtual_assistant_model}}", "virtual_assistant_id",
            "{{%virtual_assistant}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%virtual_assistant_model}}');
    }
}
