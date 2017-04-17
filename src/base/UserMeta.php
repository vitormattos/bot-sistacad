<?php
namespace Base;

use Aura\SqlQuery\QueryFactory;

class UserMeta
{
    /**
     *
     * @param int $telegram_id
     */
    public function getUser($telegram_id = null)
    {
        $query_factory = new QueryFactory(getenv('DB_ADAPTER'));
        $select = $query_factory->newSelect();
        $select->cols(['username', 'password'])
               ->from('userdata')
               ->where('telegram_id = :telegram_id')
               ->bindValue('telegram_id', $telegram_id);

        $db = DB::getInstance();
        $sth = $db->prepare($select->getStatement());
        return $sth->execute($update->getBindValues());
    }
}