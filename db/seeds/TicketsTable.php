<?php


use Phinx\Seed\AbstractSeed;

class TicketsTable extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $users_table = $this->table('users');
        $users_table->addColumn('id', 'integer')
            ->addColumn('email', 'string')
            ->addColumn('name', 'string')
            ->addColumn('lastname', 'string')
            ->addColumn('adress', 'string')
            ->addColumn('telephone', 'string')
            ->addColumn('dateBirth', 'date')
            ->addColumn('dateRegister', 'date')
            ->addColumn('dateLastLogin', 'date')
            ->addColumn('user', 'string')
            ->addColumn('password', 'string')
            ->addColumn('mailchimp', 'boolean')
            ->addColumn('KeyConfirm', 'string')
            ->addColumn('userConfirm', 'boolean')
            ->create();
            
    }
}
