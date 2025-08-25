<?php
require_once "../../app/config/db.php";
require_once "../../app/includes/auth.php";
require_once "../../app/models/Project.php";
require_once "../../app/includes/functions.php";

$model = new Project($pdo);

// 전체 누적 통계
$totalStats = $model->statsTotal();

// 연도별 통계
$yearStats = $model->statsByYear();

// 특정 연도 (선택 없으면 최근 연도)
$selectedYear = $_GET['year'] ?? ($yearStats[0]['year'] ?? date('Y'));
$monthStats = $model->statsByMonth($selectedYear);

// 차트 데이터 준비
$yearLabels = [];
$yearSales = [];
$yearProfit = [];
$yearBalance = [];

foreach($yearStats as $y) {
    $yearLabels[] = $y['year'] . '년';
    $yearSales[] = $y['total_sales'];
    $yearProfit[] = $y['total_profit'];
    $yearBalance[] = $y['total_balance'];
}

$monthLabels = [];
$monthSales = [];
$monthProfit = [];
$monthBalance = [];

foreach($monthStats as $m) {
    $monthLabels[] = $m['month'] . '월';
    $monthSales[] = $m['total_sales'];
    $monthProfit[] = $m['total_profit'];
    $monthBalance[] = $m['total_balance'];
}

require_once "../../app/includes/header.php";
?>

<div class="content-page">
    <div class="content">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">프로젝트 통계</h4>
            </div>
        </div>
        <!-- // 제목 -->

        <div class="container-fluid">
            <!-- 전체 통계 카드 -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-briefcase widget-icon"></i>
                            </div>
                            <h6 class="text-uppercase mt-0" title="Projects">총 프로젝트</h6>
                            <h3 class="my-3"><?= number_format($totalStats['project_count']) ?></h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 전체 누적</span>
                                <span class="text-nowrap">등록된 프로젝트 수</span>
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
                            <h6 class="text-uppercase mt-0" title="Sales">총 매출액</h6>
                            <h3 class="my-3"><?= number_format($totalStats['total_sales']) ?>원</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 전체 누적</span>
                                <span class="text-nowrap">총 계약금액</span>
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
                            <h6 class="text-uppercase mt-0" title="Profit">총 영업이익</h6>
                            <h3 class="my-3"><?= number_format($totalStats['total_profit']) ?>원</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 전체 누적</span>
                                <span class="text-nowrap">총 영업이익</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="mdi mdi-wallet widget-icon"></i>
                            </div>
                            <h6 class="text-uppercase mt-0" title="Balance">총 잔금</h6>
                            <h3 class="my-3"><?= number_format($totalStats['total_balance']) ?>원</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2"><i class="mdi mdi-arrow-up-bold"></i> 전체 누적</span>
                                <span class="text-nowrap">미수금액</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 연도별 차트 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">연도별 매출 및 이익 추이</h4>
                        </div>
                        <div class="card-body">
                            <div id="yearly-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 월별 차트 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="header-title"><?= $selectedYear ?>년 월별 통계</h4>
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" style="width: auto;" onchange="location.href='?year='+this.value">
                                        <?php foreach($yearStats as $y): ?>
                                        <option value="<?= $y['year'] ?>" <?= $selectedYear == $y['year'] ? 'selected' : '' ?>><?= $y['year'] ?>년</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="monthly-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 상세 통계 테이블 -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">연도별 상세 통계</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>연도</th>
                                            <th>프로젝트 수</th>
                                            <th>매출액</th>
                                            <th>영업이익</th>
                                            <th>잔금</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($yearStats as $y): ?>
                                        <tr>
                                            <td><strong><?= $y['year'] ?>년</strong></td>
                                            <td><?= number_format($y['project_count']) ?>개</td>
                                            <td><?= number_format($y['total_sales']) ?>원</td>
                                            <td class="text-success"><?= number_format($y['total_profit']) ?>원</td>
                                            <td class="text-warning"><?= number_format($y['total_balance']) ?>원</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title"><?= $selectedYear ?>년 월별 상세 통계</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>월</th>
                                            <th>프로젝트 수</th>
                                            <th>매출액</th>
                                            <th>영업이익</th>
                                            <th>잔금</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($monthStats as $m): ?>
                                        <tr>
                                            <td><strong><?= $m['month'] ?>월</strong></td>
                                            <td><?= number_format($m['project_count']) ?>개</td>
                                            <td><?= number_format($m['total_sales']) ?>원</td>
                                            <td class="text-success"><?= number_format($m['total_profit']) ?>원</td>
                                            <td class="text-warning"><?= number_format($m['total_balance']) ?>원</td>
                                        </tr>
                                        <?php endforeach; ?>
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

<script>
// 연도별 차트
var yearlyOptions = {
    series: [{
        name: '매출액',
        data: <?= json_encode($yearSales) ?>
    }, {
        name: '영업이익',
        data: <?= json_encode($yearProfit) ?>
    }, {
        name: '잔금',
        data: <?= json_encode($yearBalance) ?>
    }],
    chart: {
        type: 'area',
        height: 350,
        toolbar: {
            show: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 3
    },
    colors: ['#3b7ddd', '#28a745', '#ffc107'],
    fill: {
        type: 'gradient',
        gradient: {
            opacityFrom: 0.6,
            opacityTo: 0.1,
        }
    },
    xaxis: {
        categories: <?= json_encode($yearLabels) ?>
    },
    yaxis: {
        labels: {
            formatter: function (value) {
                return new Intl.NumberFormat('ko-KR').format(value) + '원';
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (value) {
                return new Intl.NumberFormat('ko-KR').format(value) + '원';
            }
        }
    },
    legend: {
        position: 'top'
    }
};

// 월별 차트
var monthlyOptions = {
    series: [{
        name: '매출액',
        data: <?= json_encode($monthSales) ?>
    }, {
        name: '영업이익',
        data: <?= json_encode($monthProfit) ?>
    }, {
        name: '잔금',
        data: <?= json_encode($monthBalance) ?>
    }],
    chart: {
        type: 'bar',
        height: 350,
        toolbar: {
            show: false
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    colors: ['#3b7ddd', '#28a745', '#ffc107'],
    xaxis: {
        categories: <?= json_encode($monthLabels) ?>
    },
    yaxis: {
        labels: {
            formatter: function (value) {
                return new Intl.NumberFormat('ko-KR').format(value) + '원';
            }
        }
    },
    tooltip: {
        y: {
            formatter: function (value) {
                return new Intl.NumberFormat('ko-KR').format(value) + '원';
            }
        }
    },
    legend: {
        position: 'top'
    }
};

// 차트 렌더링
document.addEventListener('DOMContentLoaded', function() {
    var yearlyChart = new ApexCharts(document.querySelector("#yearly-chart"), yearlyOptions);
    yearlyChart.render();
    
    var monthlyChart = new ApexCharts(document.querySelector("#monthly-chart"), monthlyOptions);
    monthlyChart.render();
});
</script>

<?php require_once "../../app/includes/footer.php"; ?>
