<?php

class CommentController{
    public function __construct(private CommentsGateway $gateway)
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
        $comment = $this->gateway->get($id);
        
        if ( !$comment) {
            http_response_code(404);
            echo json_encode(["message" => "comment not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($comment);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                echo json_encode([
                    "message" => "comment $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET, DELETE");
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
                    "message" => "comment created",
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
        
        if ($is_new && empty($data["question_id"])) {
            $errors[] = "question_id is required";
        }
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

