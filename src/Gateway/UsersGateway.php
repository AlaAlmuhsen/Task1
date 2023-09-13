<?php

class UsersGateway{

    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function login(array $data):array{
        $sql = "SELECT * FROM user WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt-> fetch(PDO::FETCH_ASSOC);

        if ($result == null) {
            return (["error" => "no user with this email try to register"]);
        }
        else{
            if ($data["password"] == $result["password"]) {
                return array_filter(
                    $result,
                    fn ($key) => $key != "password",
                    ARRAY_FILTER_USE_KEY
                );
            }
            else{
                return (["error" => "user name or password not correct"]);
            }

        }
    }
    public function register(array $data):array{
        $sql = "SELECT * FROM user WHERE email = :email";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);

        $stmt->execute();
        $result = $stmt-> fetch(PDO::FETCH_ASSOC);

        if ($result != null) {
            return (["error" => "this email already have an account try to login"]);
        }
        else{
            $sql = "INSERT INTO user (user_name, email, password)
                    VALUES (:user_name, :email, :password)";
            
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(":user_name", $data["user_name"], PDO::PARAM_STR);
            $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);
            $stmt->bindValue(":password", $data["password"], PDO::PARAM_STR);
            
            $stmt->execute();

            $data['id']=$this->conn->lastInsertId();
            return([$data]);

        }
    }
    

}