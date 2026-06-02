<?php
// Resultado compatible con MySQLi
class MySQLiResult {
    private $rows = [];
    private $pos  = 0;
    public  $num_rows = 0;

    public function __construct($stmt) {
        $this->rows     = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        $this->num_rows = count($this->rows);
    }
    public function fetch_assoc() {
        return $this->pos < $this->num_rows ? $this->rows[$this->pos++] : false;
    }
}

// PDO con interfaz compatible con MySQLi
class MyPDO extends PDO {
    public $insert_id = 0;

    public function real_escape_string($val) {
        return str_replace("'", "''", (string)$val);
    }
    public function query($sql, $fetchMode = null, ...$fetchModeArgs) {
        $sql  = preg_replace('/\bINSERT\s+IGNORE\b/i', 'INSERT OR IGNORE', $sql);
        $stmt = parent::query($sql);
        $this->insert_id = (int)$this->lastInsertId();
        return stripos(ltrim($sql), 'SELECT') === 0 ? new MySQLiResult($stmt) : ($stmt ?: true);
    }
}

// Inicializar SQLite (se crea la primera vez)
$dbFile   = '/tmp/shoponline.db';
$needInit = !file_exists($dbFile);
try {
    $conn = new MyPDO("sqlite:$dbFile");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("PRAGMA journal_mode=WAL; PRAGMA foreign_keys=ON;");
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
if ($needInit) {
    $conn->exec(file_get_contents(__DIR__ . '/schema_sqlite.sql'));
}
?>
