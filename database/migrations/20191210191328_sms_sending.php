<?php

use Phinx\Migration\AbstractMigration;

class SmsSending extends AbstractMigration
{
  public function up()
  {
    $table = $this->table('sms_sendings');

    $table->addColumn('name', 'string', ['limit' => 100])
      ->addColumn('phone', 'text', ['limit' => 15])
      ->addColumn('text', 'text')
      ->addColumn('created_at', 'datetime', ['null' => true])
      ->addColumn('updated_at', 'datetime', ['null' => true])
      ->addColumn('deleted_at', 'datetime', ['null' => true])
      ->addColumn('status', 'string', ['limit' => '40', 'null' => true])
      ->save();
  }

  public function down()
  {
    $this->table('sms_sendings')
      ->drop()
      ->save();
  }
}
