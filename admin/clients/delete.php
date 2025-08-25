<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Client.php";
require_once "../../app/includes/functions.php";

$model = new Client($pdo);
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$client = $model->find($id);
if (!$client) {
    header("Location: index.php");
    exit;
}

$message = '';
$error = '';

if ($_POST && isset($_POST['confirm_delete'])) {
    if ($model->delete($id)) {
        header("Location: index.php?message=deleted");
        exit;
    } else {
        $error = '삭제 중 오류가 발생했습니다.';
    }
}

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">클라이언트 삭제</h4>
            </div>
        </div>
        <!-- // 제목 -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="header-title">클라이언트 삭제 확인</h4>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-arrow-left"></i> 목록으로
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <div class="alert alert-warning">
                                <h5 class="alert-heading">삭제 확인</h5>
                                <p class="mb-0">다음 클라이언트를 삭제하시겠습니까?</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="120" class="text-muted">클라이언트명</th>
                                            <td><?= htmlspecialchars($client['name']) ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">담당자</th>
                                            <td><?= htmlspecialchars($client['contact'] ?: '-') ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">이메일</th>
                                            <td><?= htmlspecialchars($client['email'] ?: '-') ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">전화번호</th>
                                            <td><?= htmlspecialchars($client['phone'] ?: '-') ?></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">등록일</th>
                                            <td><?= date('Y-m-d H:i', strtotime($client['created_at'])) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">메모</label>
                                        <div class="border rounded p-3 bg-light">
                                            <?= nl2br(htmlspecialchars($client['memo'] ?: '메모가 없습니다.')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" class="mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" name="confirm_delete" class="btn btn-danger" onclick="return confirm('정말 삭제하시겠습니까?')">
                                        <i class="mdi mdi-delete"></i> 삭제 확인
                                    </button>
                                    <a href="index.php" class="btn btn-outline-secondary">취소</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../app/includes/footer.php"; ?>
