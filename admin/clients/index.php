<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Client.php";
require_once "../../app/includes/functions.php";

$model = new Client($pdo);

$keyword = $_GET['keyword'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

[$list,$totalPages] = $model->paginate($keyword,$page,$perPage);

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">클라이언트 관리</h4>
            </div>
        </div>
        <!-- // 제목 -->

        <div class="container-fluid">
            <!-- 검색 폼 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                클라이언트가 삭제되었습니다.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>
                            
                            <form method="GET" class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">검색어</label>
                                    <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword) ?>" placeholder="클라이언트명 또는 담당자명으로 검색">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">검색</button>
                                    <a href="index.php" class="btn btn-outline-secondary ms-2">초기화</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 클라이언트 목록 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="header-title">클라이언트 목록</h4>
                                <a href="form.php" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> 새 클라이언트 등록
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>이름</th>
                                            <th>담당자</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>관리</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $c): ?>
                                        <tr>
                                            <td><?= $c['id'] ?></td>
                                            <td><?= htmlspecialchars($c['name']) ?></td>
                                            <td><?= htmlspecialchars($c['contact']) ?></td>
                                            <td><?= htmlspecialchars($c['email']) ?></td>
                                            <td><?= htmlspecialchars($c['phone']) ?></td>
                                                                                    <td>
                                            <a href="view.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-info">보기</a>
                                            <a href="form.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">수정</a>
                                            <a href="delete.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
                                        </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- 페이지네이션 -->
                            <?php if($totalPages > 1): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <nav>
                                        <ul class="pagination pagination-rounded justify-content-center mb-0">
                                            <?php for($i=1;$i<=$totalPages;$i++): ?>
                                            <li class="page-item <?= $i==$page?'active':'' ?>">
                                                <a class="page-link" href="?<?= qs(['page'=>$i]) ?>"><?= $i ?></a>
                                            </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../../app/includes/footer.php"; ?>
