<?php


use Phinx\Migration\AbstractMigration;

class CdrMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('cdr');

        $table->addColumn('calldate', 'datetime', ['null' => true])
            ->addColumn('clid', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('src', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('dst', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('dcontext', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('channel', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('dstchannel', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('lastapp', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('lastdata', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('duration', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('billsec', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('disposition', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('amaflag', 'integer', ['limit' => 11, 'null' => true])
            ->addColumn('accountcode', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('uniqueid', 'string', ['limit' => 100,'null' => true])
            ->addColumn('userfield', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('did', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('recordingfile', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('cnum', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('cnam', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('outbound_cnum', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('outbound_cnam', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('dst_cnam', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('linkedid', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('peeraccount', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('sequence', 'integer', ['limit' => 11, 'null' => true])
            ->save();
    }

    /**
     *
     */
    public function down()
    {
        $this->table('cdr')
            ->drop()
            ->save();
    }
}
