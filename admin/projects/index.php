<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Project.php";
require_once "../../app/includes/functions.php";

$model = new Project($pdo);
$keyword = $_GET['keyword'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

[$list, $totalPages] = $model->paginate($keyword,$page,$perPage);

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">프로젝트 관리</h4>
            </div>
        </div>

        <div class="container-fluid">
            <!-- 검색 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">검색어</label>
                                    <input type="text" name="keyword" class="form-control" 
                                           value="<?= htmlspecialchars($keyword) ?>" 
                                           placeholder="프로젝트명 또는 클라이언트명">
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

            <!-- 목록 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="header-title">프로젝트 목록</h4>
                            <a href="form.php" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> 새 프로젝트 등록
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>클라이언트</th>
                                            <th>제목</th>
                                            <th>총금액</th>
                                            <th>선금</th>
                                            <th>중도금</th>
                                            <th>잔금</th>
                                            <th>외주비용</th>
                                            <th>기타비용</th>
                                            <th>영업이익</th>
                                            <th>관리</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $p): ?>
                                        <tr>
                                            <td><?= $p['id'] ?></td>
                                            <td><?= htmlspecialchars($p['client_name']) ?></td>
                                            <td><?= htmlspecialchars($p['title']) ?></td>
                                            <td><?= number_format($p['amount']) ?></td>
                                            <td><?= number_format($p['deposit']) ?></td>
                                            <td><?= number_format($p['middle_payment']) ?></td>
                                            <td><?= number_format($p['balance']) ?></td>
                                            <td><?= number_format($p['outsourcing_cost']) ?></td>
                                            <td><?= number_format($p['misc_cost']) ?></td>
                                            <td><?= number_format($p['profit']) ?></td>
                                            <td>
                                                <a href="view.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-info">보기</a>
                                                <a href="form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">수정</a>
                                                <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
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
                                        <ul class="pagination justify-content-center mb-0">
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

<?php include "../../app/includes/footer.php"; ?>
