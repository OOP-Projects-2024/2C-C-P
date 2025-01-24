<?php

class Patch extends Common {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function patchTaxi($body, $taxi_id) {
        if (!isset($body->driver_name) || !isset($body->status)) {
            return $this->generateResponse(
                null,
                "failed",
                "Driver name and status are required for update.",
                400
            );
        }

        $sql = "UPDATE taxis_tbl SET driver_name = :driver_name, status = :status WHERE taxi_id = :taxi_id";
        $params = [
            'driver_name' => $body->driver_name,
            'status' => $body->status,
            'taxi_id' => $taxi_id
        ];

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            if ($stmt->rowCount()) {
                return $this->generateResponse(
                    null,
                    "success",
                    "Taxi record updated successfully.",
                    200
                );
            } else {
                return $this->generateResponse(
                    null,
                    "failed",
                    "Taxi record not found or no changes made.",
                    404
                );
            }
        } catch (\PDOException $e) {
            return $this->generateResponse(
                null,
                "failed",
                "Database error: " . $e->getMessage(),
                500
            );
        }
    }

    public function patchAccount($body, $id) {
        if (!isset($body->username) || !isset($body->password) || !isset($body->isdeleted)) {
            return $this->generateResponse(
                null,
                "failed",
                "Username, password, and deletion status are required for update.",
                400
            );
        }

        $sqlString = "UPDATE account_tbl SET username = :username, password = :password, isdeleted = :isdeleted WHERE id = :id";
        $params = [
            'username' => $body->username,
            'password' => $body->password,
            'isdeleted' => $body->isdeleted,
            'id' => $id
        ];

        try {
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute($params);

            if ($stmt->rowCount()) {
                return $this->generateResponse(
                    null,
                    "success",
                    "Account record updated successfully.",
                    200
                );
            } else {
                return $this->generateResponse(
                    null,
                    "failed",
                    "Account record not found or no changes made.",
                    404
                );
            }
        } catch (\PDOException $e) {
            return $this->generateResponse(
                null,
                "failed",
                "Database error: " . $e->getMessage(),
                500
            );
        }
    }
}
?>