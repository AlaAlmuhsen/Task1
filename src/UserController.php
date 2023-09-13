<?php

// class UserController{

//     public function __construct(private UsersGateway $gateway)
//     {
        
//     }

//     public function processRequest(string $method, ?string $id):void {
//         if($id){
//             $this->processResourceRequest($method,$id);
//         }
//         else{
//             $this->processCollectionRequest($method);
//         }
//     }


//     private function processResourceRequest(string $method, string $id):void {
//         $user = $this->gateway->get($id);

//         if (!$user) {
//             http_response_code(404);
//             echo json_encode(["message" => "User Not Found"]);
//             return;
//         }

//         switch($method){
//             case "GET": 
//             echo json_encode($user);
//             break;

//             case "PATCH":
//                 $data = (array) json_decode(file_get_contents("php://input"),true);
                
//                 $errors = $this->getValidationErrors($data,false);

//                 if (!empty($errors)) {
//                     http_response_code(422);
//                     echo json_encode(["errors" => $errors]);
//                     break;
//                 }

//                 $rows = $this->gateway->update($user,$data);
       
//                     echo json_encode([
//                         "message" => "User $id Updated",
//                         "rows" => $rows
//                     ]);
                
//                 break;
//             case "DELETE":
//                 $rows = $this->gateway->delete($id);

//                 echo json_encode([
//                     "message" => "User $id Deleted",
//                     "rows" => $rows
//                 ]);
//                 break;
//             default:
//                 http_response_code(405);
//                 header("Allow: GET, PATCH, DELETE");
//         }
//     }
//     private function processCollectionRequest(string $method):void {
//         switch($method){
//             case "GET":
//                 echo json_encode([$this->gateway->getAll()]);
//                 break;

//             case "POST":
//                 $data = (array) json_decode(file_get_contents("php://input"),true);
                
//                 $errors = $this->getValidationErrors($data);

//                 if (!empty($errors)) {
//                     http_response_code(422);
//                     echo json_encode(["errors" => $errors]);
//                     break;
//                 }

//                 $id = $this->gateway->create($data);
//                 if ($id == 0) {
//                     http_response_code(422);
//                     echo json_encode(["errors" => "User Already Have An Account try to login"]);
//                 }
//                 else{
//                     http_response_code(201);
//                     echo json_encode([
//                         "message" => "User Created",
//                         "id" => $id
//                     ]);
//                 }
//                 break;

//             default:
//                 http_response_code(405);
//                 header("Allow: GET, POST");
                
                
//         }
//     }
//     private function getValidationErrors(array $data, bool $is_new = true):array {
//         $errors =[];

//         if ($is_new && empty($data["user_name"])) {
//             $errors[] = "user_name is required";
//         }
//         if ($is_new && empty($data["email"])) {
//             $errors[] = "email is required";
//         }
//         else {
//             if (filter_var($data["email"], FILTER_VALIDATE_EMAIL) === false) {
//                 $errors[] = "enter a valid email";
//             }
//         }
//         if ($is_new && empty($data["password"])) {
//             $errors[] = "password is required";
//         }

//         return $errors;
//     }
// };