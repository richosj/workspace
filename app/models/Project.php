<?php
class Project {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function all() {
        return $this->pdo->query("SELECT * FROM projects ORDER BY title ASC")->fetchAll();
    }

    // Project.php
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM projects");
        return (int)$stmt->fetchColumn();
    }

    public function totalAmount() {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(amount),0) FROM projects");
        return (int)$stmt->fetchColumn();
    }

    public function totalBalance() {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(balance),0) FROM projects");
        return (int)$stmt->fetchColumn();
    }

    public function getTotalProfit() {
        $stmt = $this->pdo->query("SELECT COALESCE(SUM(profit),0) FROM projects");
        return (int)$stmt->fetchColumn();
    }


    // 전체(누적) 통계
    public function statsTotal() {
        $sql = "SELECT 
                    COUNT(*) AS project_count,
                    COALESCE(SUM(amount),0) AS total_sales,
                    COALESCE(SUM(profit),0) AS total_profit,
                    COALESCE(SUM(balance),0) AS total_balance
                FROM projects";
        return $this->pdo->query($sql)->fetch();
    }

    // 연도별 통계
    public function statsByYear() {
        $sql = "SELECT 
                    YEAR(start_date) AS year,
                    COUNT(*) AS project_count,
                    COALESCE(SUM(amount),0) AS total_sales,
                    COALESCE(SUM(profit),0) AS total_profit,
                    COALESCE(SUM(balance),0) AS total_balance
                FROM projects
                WHERE start_date IS NOT NULL
                GROUP BY YEAR(start_date)
                ORDER BY year DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    // 특정 연도 월별 통계 (1~12월)
    public function statsByMonth($year) {
        $sql = "SELECT 
                    MONTH(start_date) AS month,
                    COUNT(*) AS project_count,
                    COALESCE(SUM(amount),0) AS total_sales,
                    COALESCE(SUM(profit),0) AS total_profit,
                    COALESCE(SUM(balance),0) AS total_balance
                FROM projects
                WHERE YEAR(start_date) = ?
                GROUP BY MONTH(start_date)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$year]);
        $rows = $stmt->fetchAll(PDO::FETCH_UNIQUE);

        $result = [];
        for ($m=1; $m<=12; $m++) {
            $result[$m] = [
                'month' => $m,
                'project_count' => $rows[$m]['project_count'] ?? 0,
                'total_sales'   => $rows[$m]['total_sales'] ?? 0,
                'total_profit'  => $rows[$m]['total_profit'] ?? 0,
                'total_balance' => $rows[$m]['total_balance'] ?? 0,
            ];
        }
        return $result;
    }

    // 전체 월별 통계 (연도 무시)
    public function statsByMonthAll() {
        $sql = "SELECT 
                    MONTH(start_date) AS month,
                    COUNT(*) AS project_count,
                    COALESCE(SUM(amount),0) AS total_sales,
                    COALESCE(SUM(profit),0) AS total_profit,
                    COALESCE(SUM(balance),0) AS total_balance
                FROM projects
                WHERE start_date IS NOT NULL
                GROUP BY MONTH(start_date)";
        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_UNIQUE);

        $result = [];
        for ($m=1; $m<=12; $m++) {
            $result[$m] = [
                'month' => $m,
                'project_count' => $rows[$m]['project_count'] ?? 0,
                'total_sales'   => $rows[$m]['total_sales'] ?? 0,
                'total_profit'  => $rows[$m]['total_profit'] ?? 0,
                'total_balance' => $rows[$m]['total_balance'] ?? 0,
            ];
        }
        return $result;
    }

    // 최근 프로젝트 N개
    public function recent($limit=5) {
        $sql = "SELECT p.*, c.name AS client_name
                FROM projects p
                JOIN clients c ON p.client_id=c.id
                ORDER BY p.id DESC
                LIMIT ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1,$limit,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id) {
        $sql = "SELECT p.*, c.name AS client_name
                FROM projects p
                JOIN clients c ON p.client_id=c.id
                WHERE p.id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // ✅ paginate 고침 (목록 + totalPages 같이 반환)
    public function paginate($keyword, $page, $perPage) {
        $offset = ($page-1)*$perPage;

        // 전체 개수
        $countSql = "SELECT COUNT(*) 
                     FROM projects p
                     JOIN clients c ON p.client_id=c.id
                     WHERE p.title LIKE :kw OR c.name LIKE :kw";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute([':kw' => "%$keyword%"]);
        $total = $stmt->fetchColumn();
        $totalPages = ceil($total / $perPage);

        // 데이터
        $sql = "SELECT p.*, c.name AS client_name
                FROM projects p
                JOIN clients c ON p.client_id=c.id
                WHERE p.title LIKE :kw OR c.name LIKE :kw
                ORDER BY p.id DESC
                LIMIT :l OFFSET :o";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':kw', "%$keyword%");
        $stmt->bindValue(':l', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $list = $stmt->fetchAll();

        return [$list, $totalPages];
    }

    public function create($data) {
        $balance = $data['amount'] - $data['deposit'] - $data['middle_payment'];
        $profit  = $data['amount'] - $data['outsourcing_cost'] - $data['misc_cost'];

        $stmt = $this->pdo->prepare("INSERT INTO projects 
            (client_id, title, amount, deposit, middle_payment, balance,
             outsourcing_cost, misc_cost, profit,
             description, start_date, end_date, status, memo) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        return $stmt->execute([
            $data['client_id'], $data['title'], $data['amount'],
            $data['deposit'], $data['middle_payment'], $balance,
            $data['outsourcing_cost'], $data['misc_cost'], $profit,
            $data['description'], $data['start_date'], $data['end_date'],
            $data['status'], $data['memo']
        ]);
    }

    public function update($id, $data) {
        $balance = $data['amount'] - $data['deposit'] - $data['middle_payment'];
        $profit  = $data['amount'] - $data['outsourcing_cost'] - $data['misc_cost'];

        $stmt = $this->pdo->prepare("UPDATE projects SET
            client_id=?, title=?, amount=?, deposit=?, middle_payment=?, balance=?,
            outsourcing_cost=?, misc_cost=?, profit=?,
            description=?, start_date=?, end_date=?, status=?, memo=?
            WHERE id=?");

        return $stmt->execute([
            $data['client_id'], $data['title'], $data['amount'],
            $data['deposit'], $data['middle_payment'], $balance,
            $data['outsourcing_cost'], $data['misc_cost'], $profit,
            $data['description'], $data['start_date'], $data['end_date'],
            $data['status'], $data['memo'], $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM projects WHERE id=?");
        $stmt->execute([$id]);
    }
}
