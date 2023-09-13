<?php

class UserController{
    public function __construct(private UsersGateway $gateway)
    {
        
    }
    public function processRequest(string $method,string $path) {
        if ($method != "POST") {
            http_response_code(404);
            header("Allow: POST");
        }
        else{
            $data = (array) json_decode(file_get_contents("php://input"),true);
            switch($path){
                case 'login':
                    
                    $errors = $this->getValidationErrors($data,false);
                    
                    if (!empty($errors)) {
                        http_response_code(422);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
                    
                    echo json_encode($this->gateway->login($data));
                    
                    break;

                case 'register':
                    $errors = $this->getValidationErrors($data,true);
                    if (!empty($errors)) {
                        http_response_code(422);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
                    echo json_encode($this->gateway->register($data));
                    
                    break;
            }
        }
    }




        private function getValidationErrors(array $data, bool $register = true):array {
        $errors =[];

        if ($register && empty($data["user_name"])) {
            $errors[] = "user_name is required";
        }
        if (empty($data["email"])) {
            $errors[] = "email is required";
        }
        else {
            if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) === false) {
                $errors[] = "enter a valid email";
            }
        }
        if ( empty($data["password"])) {
            $errors[] = "password is required";
        }

        return $errors;
    }
}

