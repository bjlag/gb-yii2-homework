<?php

use yii\db\Migration;

/**
 * Class m180415_121942_foreign_keys
 */
class m180415_121942_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey( 'fk_note_user', 'note', 'creator_id', 'user', 'id' );
        $this->addForeignKey( 'fk_access_note', 'access', 'note_id', 'note', 'id' );
        $this->addForeignKey( 'fk_access_user', 'access', 'user_id', 'user', 'id' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey( 'fk_note_user', 'note' );
        $this->dropForeignKey( 'fk_access_note', 'access' );
        $this->dropForeignKey( 'fk_access_user', 'access' );

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180415_121942_foreign_keys cannot be reverted.\n";

        return false;
    }
    */
}
