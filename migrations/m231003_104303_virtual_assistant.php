<?php

use yii\db\Migration;

/**
 * Class m231003_104303_virtual_assistant
 */
class m231003_104303_virtual_assistant extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%virtual_assistant}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'author' => $this->integer()->notNull(),
            'dialogue_model' => $this->integer()->notNull(),
            'knowledge_base_model' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("virtual_assistant_dialogue_model_fk", "{{%virtual_assistant}}", "dialogue_model",
            "{{%diagram}}", "id", 'CASCADE');

        $this->addForeignKey("virtual_assistant_knowledge_base_model_fk", "{{%virtual_assistant}}", "knowledge_base_model",
            "{{%diagram}}", "id", 'CASCADE');

        $this->addForeignKey("virtual_assistant_user_fk", "{{%virtual_assistant}}", "author", "{{%user}}",
            "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%virtual_assistant}}');
    }
}
