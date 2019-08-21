<?php


use Phinx\Migration\AbstractMigration;

class PostsMigration extends AbstractMigration
{


    /**
     *
     */
    public function up()
    {
        $table = $this->table('posts');

        $table->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('message', 'text')
            ->addColumn('user_id', 'integer')
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('deleted_at', 'datetime', ['null' => true])
            ->addIndex('user_id')
            ->save();
    }

    /**
     *
     */
    public function down()
    {
        $this->table('posts')
            ->drop()
            ->save();
    }
}
