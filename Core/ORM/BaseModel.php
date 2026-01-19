<?php
namespace Core\ORM;

use Core\Database\Database;
use Core\Helper\Logger;
use \PDO;
use \Exception;



/**
 * Query builder
 * 
 * Esempio:
 * $user = (new User)
 *      -> select(...)
 *      -> where(...)
 *      -> order_by(...)
 *      -> limit(...)
 *      -> get()
 * 
 * Restituisce il risultato della query
 */
class BaseModel implements BaseInterface
{

    protected $table;
    private $selects = ["*"];
    private $conditions = [];
    private $order = "";
    private $limit = "";
    private $group = "";
    private $joins = [];

    /**
     * Trova un record per ID
     * 
     * @param int $id
     * @return array|null
     */
    public static function find(int $id): ?array
    {
        $instance = new static();
        return $instance->where('id', '=', $id)->first();
    }

    /**
     * Ottieni tutti i record della tabella
     * 
     * @return array
     */
    public function getAll(): array
    {
        return $this->get();
    }

    /**
     * Validazione valori generici (non identifier)
     */
    private function validate(array $values): bool
    {
        foreach ($values as $value) {
            if (!isset($value))
                return false;
            if (is_string($value) && trim($value) === "")
                return false;
        }
        return true;
    }

    /**
     * Validazione identificatori SQL (table, column, alias)
     * Permette lettere, numeri, underscore, punto, backtick e alias con punto
     */
    private function validateIdentifier(string $identifier): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9_\.`]+$/', $identifier);
    }

    /**
     * Validazione operatori supportati
     */
    private function validateOperator(string $op): bool
    {
        $op = strtoupper(trim($op));
        $allowed = ["=", "<>", "!=", "<", ">", "<=", ">=", "IN", "NOT IN", "IS", "IS NOT", "LIKE", "BETWEEN"];
        return in_array($op, $allowed, true);
    }

    /**
     * Select SQL
     * 
     * ESEMPIO:
     * SELECT nome cognome FROM USER
     *      coloumns = ["nome", "cognome"]
     * 
     * @param array columns -> Campi da selezionare
     * @return BaseInterface
     */
    public function select($columns = ['*']): BaseInterface
    {
        if (!empty($columns)) {
            $this->selects = $columns;
        }
        return $this;
    }

    /**
     * Where SQL
     * Aggiunge una condizione coloumn op value
     * 
     * Esempio:
     * SELECT * FROM USER
     * WHERE id = 1;
     *      
     *      coloum = id;
     *      op = "=";
     *      value = 1;
     * 
     * 
     * @param string column -> campo da verificare
     * @param string op -> operatore di verifica (==, >, <, ...)
     * @param string value -> valore da valutare
     */
    public function where($column, $operator, $value, $and = true): BaseInterface
    {
        if (!$this->validateIdentifier((string) $column) || !$this->validateOperator((string) $operator)) {
            Logger::getInstance()->error(
                "Invalid where parameters",
                ["column" => $column, "operator" => $operator, "value" => $value]
            );
            throw new Exception("ERROR: Invalid where parameters");
        }

        // accetta array per IN/BETWEEN, null per IS NULL, string per gli altri
        $this->conditions[] = ['column' => $column, 'operator' => strtoupper($operator), 'value' => $value, 'and' => $and];

        Logger::getInstance()->info("Aggiunta condizione where con successo", ["condition" => end($this->conditions)]);
        return $this;
    }

    /**
     * Clausola ORDER BY SQL
     * 
     * @param string column -> campo indice
     * @param string direction -> crescente o decrescente
     */
    public function order_by($column, $direction = "ASC"): BaseInterface
    {
        $direction = strtoupper($direction);
        if ($this->validate([$column]) && ($direction === "ASC" || $direction === "DESC")) {
            $this->order = "ORDER BY {$column} {$direction}";
        } else {
            Logger::getInstance()->error(
                "Invalid order_by parameters",
                [
                    "column" => $column,
                    "direction" => $direction
                ]
            );
            throw new Exception("ERROR: Invalid order_by parameters");
        }
        Logger::getInstance()->info("Aggiunta condizione order_by con successo", ["condition" => $this->order]);
        return $this;
    }

    /**
     * Clausola GROUP BY SQL
     *
     * @param string column -> campo su cui raggruppare
     * @return BaseInterface
     */
    public function group_by(string $column): BaseInterface
    {
        if ($this->validate([$column])) {
            $this->group = "GROUP BY {$column}";
        } else {
            Logger::getInstance()->error(
                "Invalid group_by parameters",
                [
                    "column" => $column
                ]
            );
            throw new Exception("ERROR: Invalid group_by parameters");
        }

        Logger::getInstance()->info("Aggiunta condizione group_by con successo", ["condition" => $this->group]);
        return $this;
    }

    /**
     * Clausola LIMIT SQL
     * 
     * @param int number -> numero di entry da prendere
     */
    public function limit($number): BaseInterface
    {
        if ($this->validate([$number]) && is_numeric($number)) {
            $this->limit = "LIMIT {$number}";
        } else {
            Logger::getInstance()->error(
                "Invalid limit parameters",
                [
                    "number" => $number
                ]
            );
            throw new Exception("ERROR: Invalid limit parameter");
        }
        Logger::getInstance()->info("Aggiunta condizione where con successo", ["condition" => $this->limit]);
        return $this;
    }


    /**
     * Clausola JOIN SQL
     *
     * Esempio:
     * ->join('profiles', 'users.id', '=', 'profiles.user_id', 'LEFT')
     *
     * @param string $table
     * @param string $first  left side identifier (es. users.id)
     * @param string $operator  operatore (es. =, <, >)
     * @param string $second right side identifier (es. profiles.user_id)
     * @param string $type JOIN type: INNER, LEFT, RIGHT
     * @return BaseInterface
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = "INNER"): BaseInterface
    {
        $type = strtoupper($type);

        if (!in_array($type, ["INNER", "LEFT", "RIGHT"], true)) {
            Logger::getInstance()->error("Invalid join type", ["type" => $type]);
            throw new Exception("ERROR: Invalid join type");
        }

        if (!$this->validateIdentifier($table) || !$this->validateIdentifier($first) || !$this->validateIdentifier($second)) {
            Logger::getInstance()->error("Invalid identifiers for join", ["table" => $table, "first" => $first, "second" => $second]);
            throw new Exception("ERROR: Invalid identifiers for join");
        }

        $this->joins[] = [
            'type' => $type,
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second
        ];

        Logger::getInstance()->info("Aggiunta join con successo", ["join" => end($this->joins)]);
        return $this;
    }

    /**
     * Clausola LEFT JOIN SQL
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return BaseInterface
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): BaseInterface
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }


    /** 
     * Clausola LEFT JOIN SQL
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return BaseInterface
     */
    public function rightJoin(string $table, string $first, string $operator, string $second): BaseInterface
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }


    /**
     * Costruisce query SQL + params riutilizzabile per get() e count()
     *
     * @param string $select SQL select clause (es. "SELECT a, b FROM ...")
     * @param bool $forCount se true rimuove order/limit e adatta select a COUNT(...)
     * @return array [query, params]
     */
    private function buildQuery(string $select = '', bool $forCount = false): array
    {
        $table = $this->table;
        $params = [];

        if ($forCount) {
            $select = !empty($this->joins)
                ? "SELECT COUNT(DISTINCT {$table}.id) as c FROM {$table}"
                : "SELECT COUNT(*) as c FROM {$table}";
        } else {
            $select = $select ?: "SELECT " . implode(', ', $this->selects) . " FROM {$table}";
        }

        $query = $select;

        if (!empty($this->joins)) {
            foreach ($this->joins as $j) {
                $query .= " {$j['type']} JOIN {$j['table']} ON {$j['first']} {$j['operator']} {$j['second']}";
            }
        }

        if (!empty($this->conditions)) {
            $parts = [];
            $connectors = [];
            $total = count($this->conditions);

            foreach ($this->conditions as $i => $c) {

                $col = $c['column'] ?? ($c[0] ?? null);
                $op = strtoupper($c['operator'] ?? ($c[1] ?? '='));
                $val = array_key_exists('value', $c) ? $c['value'] : ($c[2] ?? null);

                if ($op === "IN" || $op === "NOT IN") {
                    if (!is_array($val) || empty($val)) {

                        Logger::getInstance()->error("IN operator requires non-empty array");
                        throw new Exception("ERROR: IN operator requires non-empty array");
                    }
                    $placeholders = implode(", ", array_fill(0, count($val), "?"));
                    $parts[] = "{$col} {$op} ({$placeholders})";
                    foreach ($val as $v)
                        $params[] = $v;
                } elseif ($op === "IS" || $op === "IS NOT") {
                    if ($val === null) {
                        $parts[] = "{$col} {$op} NULL";
                    } else {
                        $parts[] = "{$col} {$op} ?";
                        $params[] = $val;
                    }
                } elseif ($op === "BETWEEN") {
                    if (!is_array($val) || count($val) !== 2) {

                        Logger::getInstance()->error("BETWEEN requires array with two values");
                        throw new Exception("ERROR: BETWEEN requires array with two values");
                    }
                    $parts[] = "{$col} BETWEEN ? AND ?";
                    $params[] = $val[0];
                    $params[] = $val[1];
                } else {
                    $parts[] = "{$col} {$op} ?";
                    $params[] = $val;
                }

                // Aggiungi connettore se non è l'ultimo
                if ($i < $total - 1) {
                    $connectors[] = !empty($c['and']) ? 'AND' : 'OR';
                }
            }

            // Combina le parti con i connettori
            $where = $parts[0];
            for ($i = 1; $i < count($parts); $i++) {
                $where .= ' ' . $connectors[$i - 1] . ' ' . $parts[$i];
            }

            $query .= " WHERE " . $where;
        }

        if (!$forCount) {
            if (!empty($this->group))
                $query .= " " . $this->group;
            if (!empty($this->order))
                $query .= " " . $this->order;
            if (!empty($this->limit))
                $query .= " " . $this->limit;
        }

        return [$query, $params];
    }

    
    /**
     * 
     * 
     * @return array risultati della query
     */
    public function get(): array
    {
        $pdo = Database::getInstance();
        list($query, $params) = $this->buildQuery();

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        Logger::getInstance()->info("Query eseguita con successo", ["query" => $query]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return BaseInterface|null prima riga del risultato
     */
    public function first(): array|null
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    /**
     * Insert SQL
     * 
     * @param array data -> istanza da inserire
     * @return string|bool ID inserito o false in caso di errore
     */
    public function insert($data): string | bool
    {
        $pdo = Database::getInstance();
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array_values($data));

        $last_id = $pdo->lastInsertId();

        Logger::getInstance()->warning(
            $last_id ? "Entry inserita con successo" : "Entry non inserita con successo",
            [
                "id" => $last_id
            ]
        );

        return $last_id;
    }

    /**
     * @return int il conteggio totale delle righe corrispondenti alle condizioni correnti.
     */
    public function count(): int
    {
        $pdo = Database::getInstance();
        list($query, $params) = $this->buildQuery('', true);

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($res['c'] ?? 0);
    }


    /**
     * Paginate results according to page size and page number.
     * Modifica lo stato interno `limit` per la query e restituisce array [items, total, per_page, current_page, last_page]
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage = 15, int $page = 1): array
    {
        if ($perPage <= 0) $perPage = 15;
        if ($page <= 0) $page = 1;


        // Calcola offset e imposta limite temporaneamente (usiamo assignment diretto per bypassare la validation di limit())
        $offset = ($page - 1) * $perPage;
        $oldLimit = $this->limit;
        $this->limit = "LIMIT {$perPage} OFFSET {$offset}";

        // Ottieni items con il limite impostato
        $items = $this->get();

        // Conta totale (buildQuery con forCount=true ignora limit/order)
        $total = $this->count();

        // Ripristina stato precedente del limit
        $this->limit = $oldLimit;

        $total_pages = (int) ceil($total / $perPage);

        return [
            'data' => $items,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => $total_pages,
            ]];
    }


    /**
     * Update SQL
     * 
     * @param array data -> nuovi dati dell'istanza
     */
    public function update($data): bool
    {
        if (empty($this->conditions)) {
            Logger::getInstance()->error("WHERE conditions required for update");
            throw new Exception("ERROR: WHERE conditions required for update");
        }

        $pdo = Database::getInstance();
        $setParts = [];
        $params = [];

        foreach ($data as $col => $val) {
            $setParts[] = "$col = ?";
            $params[] = $val;
        }

        $query = "UPDATE {$this->table} SET " . implode(", ", $setParts);

        // aggiungo condizioni (compatibile con formato associativo)
        $conds = [];
        foreach ($this->conditions as $c) {
            $col = $c['column'] ?? ($c[0] ?? null);
            $op = $c['operator'] ?? ($c[1] ?? '=');
            $val = array_key_exists('value', $c) ? $c['value'] : ($c[2] ?? null);

            $conds[] = "{$col} {$op} ?";
            $params[] = $val;
        }
        $query .= " WHERE " . implode(" AND ", $conds);

        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Delete SQL
     * 
     */
    public function delete(): bool
    {
        if (empty($this->conditions)) {
            Logger::getInstance()->error("WHERE conditions required for delete");
            throw new Exception("ERROR: WHERE conditions required for delete");
        }

        $pdo = Database::getInstance();
        $params = [];
        $conds = [];
        foreach ($this->conditions as $c) {
            $col = $c['column'] ?? ($c[0] ?? null);
            $op = $c['operator'] ?? ($c[1] ?? '=');
            $val = array_key_exists('value', $c) ? $c['value'] : ($c[2] ?? null);

            $conds[] = "{$col} {$op} ?";
            $params[] = $val;
        }

        $query = "DELETE FROM {$this->table} WHERE " . implode(" AND ", $conds);
        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }

}

?>