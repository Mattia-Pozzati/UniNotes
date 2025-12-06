<?php
namespace Core\ORM;

use App\Database;
use \PDO;
use \Exception;


// TODO: Capire se vogliamo mantenere le eccezioni, dichiarare un logger e fare log su file oppure mostrare messaggio a schermo

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
    private function validate($values) {
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
    public function select($columns = ['*']) {
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
    public function where($column, $operator, $value) {
        if ($this->validate([$column, $operator, $value])) {
            // Aggiunge la condizione WHERE "coloumn" "operator" "value"
            $this->conditions[] = [$column, $operator, $value];
        } else {
            
            throw new Exception("ERROR: Invalid where parameters");
        }
        return $this;
    }

    /**
     * Clausola ORDER BY SQL
     * 
     * @param coloumn -> campo indice
     * @param direction -> crescente o decrescente
     */
    public function order_by($column, $direction = "ASC") {
        $direction = strtoupper($direction);
        if ($this->validate([$column]) && ($direction === "ASC" || $direction === "DESC")) {
            $this->order = "ORDER BY {$column} {$direction}";
        } else {
            throw new Exception("ERROR: Invalid order_by parameters");
        }
        return $this;
    }

    /**
     * Clausola LIMIT SQL
     * 
     * @param number -> numero di entry da prendere
     */
    public function limit($number) {
        if ($this->validate([$number]) && is_numeric($number)) {
            $this->limit = "LIMIT {$number}";
        } else {
            throw new Exception("ERROR: Invalid limit parameter");
        }
        return $this;
    }

    /**
     * Performa la query composta finora
     */
    public function get() {
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

        return $stmt->fetchAll(PDO::FETCH_ASSOC, static::class);
    }

    /**
     * Prende il primo riscontro
     */
    public function first() {
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
    public function insert($data) {
        $pdo = Database::getInstance();
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(array_values($data));

        return $pdo->lastInsertId();
    }

    /**
     * Update SQL
     * 
     * @param data -> nuovi dati dell'istanza
     */
    public function update($data) {
        if (empty($this->conditions)) {
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
    public function delete() {
        // Evito di cancellare tutta la tabella per errore
        if (empty($this->conditions)) {
            throw new Exception("ERROR: WHERE conditions required for delete");
        }

        $pdo = Database::getInstance();
        $params = [];
        $conds = [];
        foreach ($this->conditions as $c) {
            $conds[] = "{$c[0]} {$c[1]} ?";
            $params[] = $c[2];
        }

        $query = "DELETE FROM {$this->table} WHERE " . implode(" AND ", $conds);
        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }

    public static function all() {
        return (new static())->get();
    }

    public static function find($id) {
        return (new static())->where('id', '=', $id)->first();
    }
}

?>
