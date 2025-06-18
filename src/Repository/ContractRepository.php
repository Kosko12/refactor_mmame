<?php

namespace App\Repository;

use PDO;

class ContractRepository
{
    public function __construct(private PDO $pdo) {}

    public function findContracts(int $id, ?int $sort = null, bool $withAmountOver10 = false): array
    {
        $where = "id = :id";
        $params = [':id' => $id];

        if ($withAmountOver10) {
            $where .= " AND kwota > 10";
        }

        $orderBy = "id";
        if ($sort === 1) {
            $orderBy = "nazwa_przedsiebiorcy, NIP DESC";
        } elseif ($sort === 2) {
            $orderBy = "kwota";
        }

        $sql = "SELECT * FROM contracts WHERE $where ORDER BY $orderBy";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

