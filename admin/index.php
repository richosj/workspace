<?php
require_once "../app/config/db.php";
require_once "../app/includes/auth.php";
require_once "../app/models/Client.php";
require_once "../app/models/Project.php";
require_once "../app/includes/functions.php";
$clientModel = new Client($pdo);
$projectModel = new Project($pdo);

// 통계 데이터
$totalClients  = $clientModel->count();
$totalProjects = $projectModel->count();
$totalAmount   = $projectModel->totalAmount();
$totalBalance  = $projectModel->totalBalance();
$totalProfit   = $projectModel->getTotalProfit();

// 최근 프로젝트
$recentProjects = $projectModel->recent(5);

require_once "../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">대시보드</h4>
            </div>
        </div>
        <!-- // 제목 -->

        <div class="container-fluid">
            <!-- 통계 카드 -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-account-multiple widget-icon"></i>
                            </div>
                            <h6 class="text-uppercase mt-0" title="Customers">총 클라이언트</h6>
                            <h3 class="my-3"><?= number_format($totalClients) ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span>
                                <span class="text-nowrap">총 등록된 클라이언트 수</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-briefcase widget-icon"></i>
                            </div>
                            <h6 class="text-uppercase mt-0" title="Projects">총 프로젝트</h6>
                            <h3 class="my-3"><?= number_format($totalProjects) ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 1.08%</span>
                                <span class="text-nowrap">진행 중인 프로젝트 수</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-currency-usd widget-icon"></i>
                            </div>
                            <h6 class="text-uppercase mt-0" title="Revenue">총 계약금액</h6>
                            <h3 class="my-3"><?= number_format($totalAmount) ?>원</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 8.02%</span>
                                <span class="text-nowrap">전체 프로젝트 계약금액</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-chart-line widget-icon"></i>
                            </div>
                            <h6 class="text-uppercase mt-0" title="Growth">총 영업이익</h6>
                            <h3 class="my-3"><?= number_format($totalProfit) ?>원</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 6.65%</span>
                                <span class="text-nowrap">전체 프로젝트 영업이익</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 메뉴 카드 -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">클라이언트 관리</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">클라이언트 정보를 관리합니다.</p>
                            <div class="d-flex gap-2">
                                <a href="clients/index.php" class="btn btn-primary">클라이언트 목록</a>
                                <a href="clients/form.php" class="btn btn-success">새 클라이언트 등록</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">프로젝트 관리</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">프로젝트 정보를 관리합니다.</p>
                            <div class="d-flex gap-2">
                                <a href="projects/index.php" class="btn btn-primary">프로젝트 목록</a>
                                <a href="projects/form.php" class="btn btn-success">새 프로젝트 등록</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 최근 프로젝트 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">최근 프로젝트</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>클라이언트</th>
                                            <th>제목</th>
                                            <th>총금액</th>
                                            <th>잔금</th>
                                            <th>영업이익</th>
                                            <th>상세</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($recentProjects)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">등록된 프로젝트가 없습니다.</td>
                                        </tr>
                                        <?php else: ?>
                                        <?php foreach($recentProjects as $p): ?>
                                        <tr>
                                            <td><?= $p['id'] ?></td>
                                            <td><?= htmlspecialchars($p['client_name']) ?></td>
                                            <td><?= htmlspecialchars($p['title']) ?></td>
                                            <td><?= number_format($p['amount']) ?>원</td>
                                            <td><?= number_format($p['balance']) ?>원</td>
                                            <td><?= number_format($p['profit']) ?>원</td>
                                            <td><a href="projects/view.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">보기</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../app/includes/footer.php"; ?>
