<?php


use Phinx\Migration\AbstractMigration;

class EmailSending extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('email_sendings');

        $table->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('emails', 'json')
            ->addColumn('text', 'text')
            ->addColumn('created_at', 'datetime', ['null' => true])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addColumn('deleted_at', 'datetime', ['null' => true])
            ->save();
    }

    public function down()
    {
        $this->table('email_sendings')
            ->drop()
            ->save();
    }

}
