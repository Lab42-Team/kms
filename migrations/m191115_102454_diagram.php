<?php

use yii\db\Migration;

/**
 * Class m191115_102454_diagram
 */
class m191115_102454_diagram extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%diagram}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'correctness' => $this->smallInteger()->notNull()->defaultValue(0),
            'author' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("diagram_user_fk", "{{%diagram}}", "author", "{{%user}}",
            "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%diagram}}');
    }
}