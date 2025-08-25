<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Client.php";
require_once "../../app/includes/functions.php";

$model = new Client($pdo);
$id = $_GET['id'] ?? null;
$client = null;
$message = '';

if ($id) {
    $client = $model->find($id);
    if (!$client) {
        header("Location: index.php");
        exit;
    }
}

if ($_POST) {
    $data = [
        'name' => $_POST['name'] ?? '',
        'contact' => $_POST['contact'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
        'memo' => $_POST['memo'] ?? ''
    ];
    
    if ($id) {
        // 수정
        if ($model->update($id, $data)) {
            $message = '클라이언트 정보가 수정되었습니다.';
            $client = $model->find($id);
        } else {
            $message = '수정 중 오류가 발생했습니다.';
        }
    } else {
        // 등록
        if ($model->create($data)) {
            $message = '클라이언트가 등록되었습니다.';
            $data = ['name' => '', 'contact' => '', 'email' => '', 'phone' => '', 'address' => '', 'memo' => ''];
        } else {
            $message = '등록 중 오류가 발생했습니다.';
        }
    }
}

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0"><?= $id ? '클라이언트 수정' : '클라이언트 등록' ?></h4>
            </div>
        </div>
        <!-- // 제목 -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="header-title"><?= $id ? '클라이언트 정보 수정' : '새 클라이언트 등록' ?></h4>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-arrow-left"></i> 목록으로
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($message): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">클라이언트명 <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="<?= htmlspecialchars($client['name'] ?? $data['name'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact" class="form-label">담당자</label>
                                            <input type="text" class="form-control" id="contact" name="contact" 
                                                   value="<?= htmlspecialchars($client['contact'] ?? $data['contact'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">이메일</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= htmlspecialchars($client['email'] ?? $data['email'] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">전화번호</label>
                                            <input type="text" class="form-control" id="phone" name="phone" 
                                                   value="<?= htmlspecialchars($client['phone'] ?? $data['phone'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">주소</label>
                                    <input type="text" class="form-control" id="address" name="address" 
                                           value="<?= htmlspecialchars($client['address'] ?? $data['address'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="memo" class="form-label">메모</label>
                                    <textarea class="form-control" id="memo" name="memo" rows="4"><?= htmlspecialchars($client['memo'] ?? $data['memo'] ?? '') ?></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> <?= $id ? '수정' : '등록' ?>
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
