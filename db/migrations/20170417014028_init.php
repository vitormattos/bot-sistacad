<?php

use Phinx\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    public function change()
    {
        $this->table('userdata')
        ->addColumn('username', 'string', ['length' => 20])
        ->addColumn('password', 'string', ['length' => 31, 'null' => true])
        ->addColumn('name', 'string', ['length' => 255, 'null' => true])
        ->addColumn('telegram_id', 'integer')
        ->addTimestamps()
        ->addIndex(['telegram_id'], ['unique' => true])
        ->save();
    }
}
