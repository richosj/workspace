<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Project.php";
require_once "../../app/models/Client.php";
require_once "../../app/includes/functions.php";

$projectModel = new Project($pdo);
$clientModel  = new Client($pdo);
$clients = $clientModel->all();

$id = $_GET['id'] ?? null;
$project = $id ? $projectModel->find($id) : null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'client_id' => $_POST['client_id'],
        'title' => $_POST['title'],
        'description' => $_POST['description'] ?? '',
        'amount' => $_POST['amount'] ?? 0,
        'deposit' => $_POST['deposit'] ?? 0,
        'middle_payment' => $_POST['middle_payment'] ?? 0,
        'outsourcing_cost' => $_POST['outsourcing_cost'] ?? 0,
        'misc_cost' => $_POST['misc_cost'] ?? 0,
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'status' => $_POST['status'] ?? '진행중',
        'memo' => $_POST['memo'] ?? ''
    ];

    if ($id) {
        $projectModel->update($id, $data);
        header("Location: view.php?id=".$id);
        exit;
    } else {
        $projectModel->create($data);
        header("Location: index.php");
        exit;
    }
}

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3">
            <h4 class="fs-18 fw-semibold"><?= $id ? '프로젝트 수정' : '프로젝트 등록' ?></h4>
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="header-title"><?= $id ? '프로젝트 정보 수정' : '새 프로젝트 등록' ?></h4>
                    <a href="index.php" class="btn btn-outline-secondary">목록으로</a>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">클라이언트</label>
                                <select name="client_id" class="form-select" required>
                                    <option value="">선택</option>
                                    <?php foreach($clients as $c): ?>
                                    <option value="<?=$c['id']?>" <?=($project['client_id']??'')==$c['id']?'selected':''?>>
                                        <?=htmlspecialchars($c['name'])?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">프로젝트명</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?=htmlspecialchars($project['title']??'')?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">설명</label>
                            <textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($project['description']??'')?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">총 계약금액</label>
                                <input type="number" name="amount" class="form-control" value="<?=$project['amount']??0?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">선금</label>
                                <input type="number" name="deposit" class="form-control" value="<?=$project['deposit']??0?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">중도금</label>
                                <input type="number" name="middle_payment" class="form-control" value="<?=$project['middle_payment']??0?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">외주비용</label>
                                <input type="number" name="outsourcing_cost" class="form-control" value="<?=$project['outsourcing_cost']??0?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">기타비용</label>
                                <input type="number" name="misc_cost" class="form-control" value="<?=$project['misc_cost']??0?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">시작일</label>
                                <input type="date" name="start_date" class="form-control" value="<?=$project['start_date']??''?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">종료일</label>
                                <input type="date" name="end_date" class="form-control" value="<?=$project['end_date']??''?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">상태</label>
                                <select name="status" class="form-select">
                                    <?php foreach(['진행중','완료','보류','취소'] as $s): ?>
                                    <option value="<?=$s?>" <?=($project['status']??'')==$s?'selected':''?>><?=$s?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">메모</label>
                            <textarea name="memo" class="form-control" rows="3"><?=htmlspecialchars($project['memo']??'')?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= $id?'수정':'등록' ?></button>
                            <a href="index.php" class="btn btn-outline-secondary">취소</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../app/includes/footer.php"; ?>
