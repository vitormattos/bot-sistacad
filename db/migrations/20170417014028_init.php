<?php

use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    public function change()
    {
        $this->table('userdata')
        ->addColumn('username', 'string', ['length' => 20])
        ->addColumn('password', 'string', ['length' => 31])
        ->addColumn('telegram_id', 'integer')
        ->addTimestamps()
        ->addIndex(['telegram_id'], ['unique' => true])
        ->save();
    }
}
