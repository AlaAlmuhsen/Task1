<?php

class QuestionsGateway
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM question";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function create(array $data): string
    {
        $sql = "INSERT INTO question (author_id, body, number_of_likes)
                VALUES (:author_id, :body, :number_of_likes)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":author_id", $data["author_id"], PDO::PARAM_INT);
        $stmt->bindValue(":body", $data["body"], PDO::PARAM_STR);
        $stmt->bindValue(":number_of_likes", 0, PDO::PARAM_INT);

        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM question
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE question
                SET number_of_likes = :number_of_likes
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":number_of_likes", $new["number_of_likes"] ?? $current["number_of_likes"], PDO::PARAM_INT);

        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    public function delete(string $id): int
    {
        $sql = "DELETE FROM question
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
}










