<?php
class Common {
    protected $pdo;


    protected function logger($user, $method, $action) {
        $filename = "./logs/" . date("Y-m-d") . ".log";
        $datetime = date("Y-m-d H:i:s");
        $logMessage = "$datetime, $method, $user, $action" . PHP_EOL;
        error_log($logMessage, 3, $filename);
    }
    private function generateInsertString($tablename, $body) {
        $keys = array_keys($body);
        $fields = implode(", ", $keys);
        $parameters = implode(", ", array_fill(0, count($keys), "?"));
        return "INSERT INTO $tablename ($fields) VALUES ($parameters)";
    }
    protected function getDataByTable($tableName, $condition, \PDO $pdo) {
        $sqlString = "SELECT * FROM $tableName WHERE $condition";
        return $this->getDataBySQL($sqlString, $pdo);
    }

    protected function getDataBySQL($sqlString, \PDO $pdo) {
        try {
            $stmt = $pdo->query($sqlString);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($data) {
                return ["code" => 200, "data" => $data];
            }
            return ["code" => 404, "errmsg" => "No data found"];
        } catch (\PDOException $e) {
            return ["code" => 500, "errmsg" => $e->getMessage()];
        }
    }
   protected function fetchData($sql, $params, $logData) {
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $this->logger($logData['user'], $logData['method'], $logData['action']);

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'code' => 200
        ];
    } catch (\PDOException $e) {
        return [
            'errmsg' => $e->getMessage(),
            'code' => 500
        ];
    }
}
    protected function generateResponse($data, $remark, $message, $statusCode) {
        http_response_code($statusCode);
        return [
            "payload" => $data,
            "status" => [
                "remark" => $remark,
                "message" => $message
            ],
            "prepared_by" => "CyPhe",
            "date_generated" => date("Y-m-d H:i:s")
        ];
    }
    public function postData($tableName, $body, \PDO $pdo) {
        $values = array_values($body);

        try {
            $sqlString = $this->generateInsertString($tableName, $body);
            $stmt = $pdo->prepare($sqlString);
            $stmt->execute($values);

            return ["code" => 201, "data" => null];
        } catch (\PDOException $e) {
            return ["code" => 400, "errmsg" => $e->getMessage()];
        }
    }
}
?>
