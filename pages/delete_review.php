<?php
class Review {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM review WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
