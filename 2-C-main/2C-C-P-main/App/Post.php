<?php
include_once "Common.php";

class Post extends Common {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function postTaxi($body) {
        $result = $this->postData("taxis_tbl", [
            "license_plate" => $body->license_plate,
            "driver_name" => $body->driver_name,
            "status" => $body->status
        ], $this->pdo);
    
        if ($result['code'] == 200) {
            $checkQuery = "SELECT * FROM taxis_tbl WHERE license_plate = :license_plate AND driver_name = :driver_name";
            $stmt = $this->pdo->prepare($checkQuery);
            $stmt->execute([
                'license_plate' => $body->license_plate,
                'driver_name' => $body->driver_name
            ]);
            $checkResult = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Inserted taxi: " . json_encode($checkResult));
    
            $this->logger("admin", "POST", "Created a new taxi record.");
            return $this->generateResponse($result['data'], "success", "Taxi created successfully.", 201);
        }
        error_log("Taxi creation failed: " . ($result['errmsg'] ?? "Unknown error"));
        return $this->generateResponse(null, "failed", $result['errmsg'] ?? "Unknown error occurred.", $result['code']);
    }
    
    public function postAccount($body) {
        $hashedPassword = password_hash($body->password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO account_tbl (username, password) VALUES (?, ?)";
        $params = [$body->username, $hashedPassword];

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $id = $this->pdo->lastInsertId();

            return $this->generateResponse(
                ['id' => $id, 'username' => $body->username],
                "success",
                "User created successfully.",
                201
            );
        } catch (\PDOException $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 400);
        }
    }

    public function postData($table, $data, $pdo) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            
            $stmt = $pdo->prepare($sql);
            error_log("Executing query: $sql with data: " . json_encode($data));
            
            $stmt->execute($data);
            
            return [
                'data' => ['id' => $pdo->lastInsertId()],
                'code' => 200
            ];
        } catch (\PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            error_log("Failed Query: $sql with data: " . json_encode($data));
    
            return [
                'errmsg' => $e->getMessage(),
                'code' => 500
            ];
        }
    }    
}
?>
