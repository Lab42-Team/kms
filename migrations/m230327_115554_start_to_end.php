<?php

use yii\db\Migration;

/**
 * Class m230327_115554_start_to_end
 */
class m230327_115554_start_to_end extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%start_to_end}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'indent_x' => $this->integer(),
            'indent_y' => $this->integer(),
            'diagram' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("start_to_end_diagram_fk", "{{%start_to_end}}", "diagram",
            "{{%diagram}}", "id", 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%start_to_end}}');
    }

}
