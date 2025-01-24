<?php
include_once "Common.php";

class Get extends Common {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getLogs($date) {
        $filename = "./logs/" . $date . ".log";
        $logs = [];

        try {
            $file = new SplFileObject($filename);
            while (!$file->eof()) {
                array_push($logs, $file->fgets());
            }
            return $this->generateResponse(["logs" => $logs], "success", "Logs retrieved successfully.", 200);
        } catch (Exception $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }
    public function getTaxi(){
        $sql = "SELECT * FROM taxis_tbl";
        return $this->fetchData($sql, [],[
            'user' => 'taxi_id',
            'method' => 'getTaxi',
            'action' => 'retrieve all taxis'
        ]);
    }
    public function getTaxiById($taxi_id) {
        $sql = "SELECT * FROM taxis_tbl WHERE taxi_id = ?";
        return $this->fetchData($sql, [$taxi_id], [
            'user' => 'taxi_' . $taxi_id,
            'method' => 'getTaxiById',
            'action' => 'retrieve taxi'
        ]);
    }
    public function getAccountById($id) {
        $sql = "SELECT * FROM account_tbl WHERE id = ?";
        return $this->fetchData($sql, [$id], [
            'user' => 'admin',
            'method' => 'getAccountById',
            'action' => 'retrieve Account'
        ]);
    }

    public function getAllAccount() {
        $sql = "SELECT * FROM account_tbl";
        return $this->fetchData($sql, [], [
            'user' => 'admin',
            'method' => 'getAllAccount',
            'action' => 'retrieve all Accounts'
        ]);
    }
}
?>
