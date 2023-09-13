<?php

class QuestionController{
    public function __construct(private QuestionsGateway $gateway)
    {
        
    }
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            
            $this->processResourceRequest($method, $id);
            
        } else {
            
            $this->processCollectionRequest($method);
            
        }
    }
    private function processResourceRequest(string $method, string $id): void
    {
        $question = $this->gateway->get($id);
        
        if ( !$question) {
            http_response_code(404);
            echo json_encode(["message" => "Question not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($question);
                break;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->update($question, $data);
                
                echo json_encode([
                    "message" => "Question $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                echo json_encode([
                    "message" => "Question $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }
    
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                $id = $this->gateway->create($data);
                
                http_response_code(201);
                echo json_encode([
                    "message" => "Post created",
                    "id" => $id
                ]);
                break;
            
            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
    
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if ($is_new && empty($data["author_id"])) {
            $errors[] = "author_id is required";
        }
        if ($is_new && empty($data["body"])) {
            $errors[] = "body is required";
        }
        
        
        return $errors;
    }
}

?>

