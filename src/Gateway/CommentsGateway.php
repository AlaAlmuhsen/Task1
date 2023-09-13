<?php

class CommentsGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM comment";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO comment (question_id, author_id, body)
                VALUES (:question_id, :author_id, :body)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":question_id", $data["question_id"], PDO::PARAM_INT);
        $stmt->bindValue(":author_id", $data["author_id"], PDO::PARAM_INT);
        $stmt->bindValue(":body", $data["body"], PDO::PARAM_STR);

        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM comment
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
    
    
    
    public function delete(string $id): int
    {
        $sql = "DELETE FROM comment
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}










