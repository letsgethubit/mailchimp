<?php
use Phinx\Migration\AbstractMigration;
class TicketsTable extends AbstractMigration
{
    public function up()
    {
    }
    public function down() {
        $this->table('users')->drop()->save();
    }
}