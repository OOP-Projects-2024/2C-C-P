<?php

class Authentication {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getToken(){
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        if(!isset($headers['x-auth-user'])) {
            return "";
        }
        $sqlString = "SELECT token FROM account_tbl WHERE username = ?";
        try {
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$headers['x-auth-user']]);
            $result = $stmt->fetch();
            return $result['token'] ?? "";
        } catch (\PDOException $e) {
            return "";
        }
    }
    private function generateHeader() {
        $header = [
            "typ" => "JWT",
            "alg" => "HS256",
            "app" => "TaxiManagement",
            "dev" => "Cyrynne and Phenelopy"
        ];
        return base64_encode(json_encode($header));
    }

    private function generatePayload($id, $username) {
        $payload = [
            "uid" => $id,
            "uc" => $username,
            "email" => "taxitaxi@gmail.com",
            "iat" => time(),
            "exp" => time() + 3600
        ];
        return base64_encode(json_encode($payload));
    }

    private function generateToken($id, $username) {
        $header = $this->generateHeader();
        $payload = $this->generatePayload($id, $username);

        $signature = hash_hmac("sha256", "$header.$payload", TOKEN_KEY, true);
        $signature = base64_encode($signature);

        return "$header.$payload.$signature";
    }

    private function encryptPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function isSamePassword($inputPassword, $existingHash) {
        return password_verify($inputPassword, $existingHash);
    }

    public function saveToken($token, $username) {
        try {
            $sqlString = "UPDATE account_tbl SET token = ? WHERE username = ?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$token, $username]);
            return ["code" => 200, "message" => "Token saved successfully."];
        } catch (\PDOException $e) {
            error_log("Failed to save token: " . $e->getMessage());
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }

    public function login($body) {
        $username = $body->username ?? '';
        $password = $body->password ?? '';
        $sqlString = "SELECT enrolleeid, username, password FROM account_tbl WHERE username = ?";

        try {
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$username]);

            if ($stmt->rowCount() === 0) {
                return [
                    "code" => 401,
                    "message" => "Username does not exist.",
                    "payload" => null
                ];
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($this->isSamePassword($password, $result['password'])) {
                $token = $this->generateToken($result['enrolleeid'], $result['username']);
                $token_arr = explode('.', $token);
                $this->saveToken($token_arr[2], $result['username']);

                return [
                    "code" => 200,
                    "message" => "Logged in successfully.",
                    "payload" => [
                        "enrolleeid" => $result['enrolleeid'],
                        "username" => $result['username'],
                        "token" => $token_arr[2]
                    ]
                ];
            }

            return [
                "code" => 401,
                "message" => "Incorrect password.",
                "payload" => null
            ];
        } catch (\PDOException $e) {
            return [
                "code" => 400,
                "message" => $e->getMessage(),
                "payload" => null
            ];
        }
    }

    public function addAccount($body) {
        $body->password = $this->encryptPassword($body->password ?? '');
        $values = [$body->enrolleeid ?? null, $body->username ?? '', $body->password];

        try {
            $sqlString = "INSERT INTO account_tbl (enrolleeid, username, password) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute($values);
            return ["code" => 200, "message" => "Account created successfully."];
        } catch (\PDOException $e) {
            return ["code" => 400, "message" => $e->getMessage()];
        }
    }
    }
?>
