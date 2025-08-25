<?php
class Client {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function all() {
        return $this->pdo->query("SELECT * FROM clients ORDER BY name ASC")->fetchAll();
    }

    // 총 클라이언트 수
    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
    }

    

    public function paginate($keyword, $page, $perPage) {
        $offset = ($page-1)*$perPage;
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM clients WHERE name LIKE :kw");
        $stmt->execute(['kw'=>"%$keyword%"]);
        $total = $stmt->fetchColumn();
        $totalPages = ceil($total/$perPage);

        $sql = "SELECT * FROM clients WHERE name LIKE :kw ORDER BY id DESC LIMIT :l OFFSET :o";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':kw', "%$keyword%");
        $stmt->bindValue(':l', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return [$stmt->fetchAll(), $totalPages];
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO clients (name,contact,email,phone) VALUES (?,?,?,?)");
        $stmt->execute([$data['name'],$data['contact'],$data['email'],$data['phone']]);
    }

    public function update($id,$data) {
        $stmt = $this->pdo->prepare("UPDATE clients SET name=?, contact=?, email=?, phone=? WHERE id=?");
        $stmt->execute([$data['name'],$data['contact'],$data['email'],$data['phone'],$id]);
    }
}
