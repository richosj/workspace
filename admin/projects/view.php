<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Project.php";

$model = new Project($pdo);
$id = $_GET['id'] ?? null;
$project = $id ? $model->find($id) : null;
if (!$project) {
    header("Location: index.php");
    exit;
}

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3">
            <h4 class="fs-18 fw-semibold">프로젝트 상세</h4>
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="header-title">프로젝트 정보</h4>
                    <div class="d-flex gap-2">
                        <a href="form.php?id=<?=$project['id']?>" class="btn btn-primary">수정</a>
                        <a href="delete.php?id=<?=$project['id']?>" class="btn btn-danger"
                           onclick="return confirm('삭제하시겠습니까?')">삭제</a>
                        <a href="index.php" class="btn btn-outline-secondary">목록</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th>ID</th><td><?=$project['id']?></td></tr>
                        <tr><th>클라이언트</th><td><?=htmlspecialchars($project['client_name'])?></td></tr>
                        <tr><th>프로젝트명</th><td><?=htmlspecialchars($project['title'])?></td></tr>
                        <tr><th>총 계약금액</th><td><?=number_format($project['amount'])?> 원</td></tr>
                        <tr><th>선금</th><td><?=number_format($project['deposit'])?> 원</td></tr>
                        <tr><th>중도금</th><td><?=number_format($project['middle_payment'])?> 원</td></tr>
                        <tr><th>잔금</th><td><?=number_format($project['balance'])?> 원</td></tr>
                        <tr><th>외주비용</th><td><?=number_format($project['outsourcing_cost'])?> 원</td></tr>
                        <tr><th>기타비용</th><td><?=number_format($project['misc_cost'])?> 원</td></tr>
                        <tr><th>영업이익</th><td><?=number_format($project['profit'])?> 원</td></tr>
                        <tr><th>상태</th><td><?=$project['status']?></td></tr>
                        <tr><th>시작일</th><td><?=$project['start_date']?></td></tr>
                        <tr><th>종료일</th><td><?=$project['end_date']?></td></tr>
                        <tr><th>메모</th><td><?=nl2br(htmlspecialchars($project['memo']))?></td></tr>
                        <tr><th>등록일</th><td><?=$project['created_at']?></td></tr>
                        <tr><th>수정일</th><td><?=$project['updated_at']?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../app/includes/footer.php"; ?>
