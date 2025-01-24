<?php
class Delete {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;   
    }

    public function deleteTaxi($taxi_id) {
        try {
            $this->pdo->beginTransaction();

            $check_sql = "SELECT * FROM taxis_tbl WHERE taxi_id = ?";
            $check_stmt = $this->pdo->prepare($check_sql);
            $check_stmt->execute([$taxi_id]);

            if ($check_stmt->rowCount() === 0) {
                return ["errmsg" => "Taxi not found.", "code" => 404];
            }

            $delete_sql = "DELETE FROM taxis_tbl WHERE taxi_id = ?";
            $delete_stmt = $this->pdo->prepare($delete_sql);
            $delete_stmt->execute([$taxi_id]);

            $this->pdo->commit();

            return ["data" => null, "message" => "Taxi deleted successfully.", "code" => 200];
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return ["errmsg" => "Failed to delete taxi: " . $e->getMessage(), "code" => 500];
        }
    }
    public function deleteAccount($id) {
        try {
            $this->pdo->beginTransaction();

            $check_sql = "SELECT * FROM account_tbl WHERE id = ?";
            $check_stmt = $this->pdo->prepare($check_sql);
            $check_stmt->execute([$id]);

            if ($check_stmt->rowCount() === 0) {
                return ["errmsg" => "Account not found.", "code" => 404];
            }

            $delete_sql = "DELETE FROM account_tbl WHERE id = ?";
            $delete_stmt = $this->pdo->prepare($delete_sql);
            $delete_stmt->execute([$id]);

            $this->pdo->commit();

            return ["data" => null, "message" => "Account deleted successfully.", "code" => 200];
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return ["errmsg" => "Failed to delete Account: " . $e->getMessage(), "code" => 500];
        }
    }
}
?>