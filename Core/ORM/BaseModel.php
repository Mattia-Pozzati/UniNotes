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
Class BaseModel implements BaseInterface{
    
    protected $table;
    protected $selects = ["*"];
    protected $conditions = [];
    protected $order = "";
    protected $limit = "";

    /**
     * Validazione valori
     * @param values -> Array di valori da valutare
     * @return true se tutte le variabili sono settate
     */
    private function validate($values) : bool {
        foreach ($values as $value) {
            if (!isset($value)) return false;
        }
        return true;
    }

    /**
     * Select SQL
     * 
     * ESEMPIO:
     * SELECT nome cognome FROM USER
     *      coloumns = ["nome", "cognome"]
     * 
     * @param columns -> Campi da selezionare
     * @return this
     */
    public function select($columns = ['*']) : BaseInterface {
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
     * @param coloumn -> campoo da verificare
     * @param op -> operatore di verifica (==, >, <, ...)
     * @param value -> valore da valutare
     */
    public function where($column, $operator, $value) : BaseInterface {
        if ($this->validate([$column, $operator, $value])) {
            // Aggiunge la condizione WHERE "coloumn" "operator" "value"
            $this->conditions[] = [$column, $operator, $value];
        } else {
            Logger::getInstance()->error(
                "Invalid where parameters", 
                [
                    "coloumn" => $column, 
                    "operator" => $operator, 
                    "value" => $value
                ]);
            throw new Exception("ERROR: Invalid where parameters");
        }
        Logger::getInstance()->info("Aggiunta condizione where con successo", ["condition" => $this->conditions[-1]]);
        return $this;
    }

    /**
     * Clausola ORDER BY SQL
     * 
     * @param coloumn -> campo indice
     * @param direction -> crescente o decrescente
     */
    public function order_by($column, $direction = "ASC") : BaseInterface{
        $direction = strtoupper($direction);
        if ($this->validate([$column]) && ($direction === "ASC" || $direction === "DESC")) {
            $this->order = "ORDER BY {$column} {$direction}";
        } else {
            Logger::getInstance()->error(
                "Invalid order_by parameters", 
                [
                    "column" => $column, 
                    "direction" => $direction
                ]);
            throw new Exception("ERROR: Invalid order_by parameters");
        }
        Logger::getInstance()->info("Aggiunta condizione order_by con successo", ["condition" => $this->order]);
        return $this;
    }

    /**
     * Clausola LIMIT SQL
     * 
     * @param number -> numero di entry da prendere
     */
    public function limit($number) : BaseInterface {
        if ($this->validate([$number]) && is_numeric($number)) {
            $this->limit = "LIMIT {$number}";
        } else {
            Logger::getInstance()->error(
                "Invalid limit parameters", 
                [
                    "number" => $number
                ]);
            throw new Exception("ERROR: Invalid limit parameter");
        }
        Logger::getInstance()->info("Aggiunta condizione where con successo", ["condition" => $this->limit]);
        return $this;
    }

    /**
     * Performa la query composta finora
     */
    public function get() : array {
        $pdo = Database::getInstance();
        $fields = implode(', ', $this->selects);
        $query = "SELECT $fields FROM {$this->table}";

        $params = [];
        if (!empty($this->conditions)) {
            $conds = [];
            foreach ($this->conditions as $c) {
                $conds[] = "{$c[0]} {$c[1]} ?";
                $params[] = $c[2];
            }
            $query .= " WHERE " . implode(' AND ', $conds);
        }

        $query .= " " . $this->order . " " . $this->limit;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        Logger::getInstance()->info("Query eseguita con successo", ["query" => $query]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC, static::class);
    }

    /**
     * Prende il primo riscontro
     */
    public function first() : BaseInterface | null {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    /**
     * Insert SQL
     * 
     * @param data -> istanza da inserire
     * @return #l'istanza inserita
     */
    public function insert($data) : string
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
     * Update SQL
     * 
     * @param data -> nuovi dati dell'istanza
     */
    public function update($data) : bool
    {
        if (empty($this->conditions)) {
            Logger::getInstance()->error("WHERE conditions required for update");
            throw new Exception("ERROR: WHERE conditions required for update");
        }

        // SingleTone
        $pdo = Database::getInstance();
        $setParts = [];
        $params = [];

        foreach ($data as $col => $val) {
            $setParts[] = "$col = ?";
            $params[] = $val;
        }

        $query = "UPDATE {$this->table} SET " . implode(", ", $setParts);

        // aggiungo condizioni
        $conds = [];
        foreach ($this->conditions as $c) {
            $conds[] = "{$c[0]} {$c[1]} ?";
            $params[] = $c[2];
        }
        $query .= " WHERE " . implode(" AND ", $conds);

        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Delete SQL
     * 
     */
    public function delete() : bool
    {
        // Evito di cancellare tutta la tabella per errore
        if (empty($this->conditions)) {
            Logger::getInstance()->error("WHERE conditions required for delete");
            throw new Exception("ERROR: WHERE conditions required for delete");
        }

        $pdo = Database::getInstance();
        $params = []; // 
        $conds = [];
        foreach ($this->conditions as $c) {
            $conds[] = "{$c[0]} {$c[1]} ?";
            $params[] = $c[2];
        }

        $query = "DELETE FROM {$this->table} WHERE " . implode(" AND ", $conds);
        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }

    public static function all() : array | BaseInterface
    { 
        return (new static())->get();
    }

    public static function find($id) : BaseInterface
    {
        return (new static())->where("id", "=", $id)-> first();
    }
}

?>
