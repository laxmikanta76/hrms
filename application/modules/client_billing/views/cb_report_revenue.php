<?php defined('BASEPATH') OR exit('No direct script access allowed');
$total  = array_sum(array_column($monthly,'revenue'));
$peak   = !empty($monthly)?max(array_column($monthly,'revenue')):0;
$avg    = count($monthly)>0?$total/count($monthly):0;
?>
<style>
:root {
    --p: #0b0895;
    --s: #e0802d;
    --bd: #e2e8f0;
    --r: 10px;
    --sh: 0 4px 20px rgba(0, 0, 0, .07);
}

.bil-card {
    background: #fff;
    border-radius: var(--r);
    border: 1px solid var(--bd);
    box-shadow: var(--sh);
    margin-bottom: 20px;
    overflow: hidden;
}

.bil-head {
    background: linear-gradient(135deg, var(--p), #1e40af);
    color: #fff;
    padding: 13px 20px;
    font-size: 14px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: space-between;
}

.sum-card {
    border-radius: 10px;
    padding: 18px 20px;
    color: #fff;
    text-align: center;
}

.sum-card .v {
    font-size: 22px;
    font-weight: 800;
}

.sum-card .l {
    font-size: 11px;
    opacity: .82;
    margin-top: 3px;
}

.rev-tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.rev-tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 10px 13px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.rev-tbl tbody td {
    padding: 10px 13px;
    border-bottom: 1px solid #f1f5f9;
}

.rev-tbl tbody tr:hover td {
    background: #f8fafc;
}

.rev-tbl tfoot td {
    padding: 10px 13px;
    font-weight: 800;
    background: #f0f4ff;
    border-top: 2px solid var(--p);
}

.chart-wrap {
    height: 250px;
    padding: 16px;
}

.bar-vis {
    background: #f1f5f9;
    border-radius: 4px;
    height: 10px;
    overflow: hidden;
    margin-top: 4px;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--p), var(--s));
    border-radius: 4px;
}
</style>

<!-- Summary Cards -->
<div class="row" style="margin-bottom:20px">
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#0b0895,#1e40af)">
            <div class="v">₹<?= number_format($total,0) ?></div>
            <div class="l">Total Revenue (<?= count($monthly) ?> months)</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
            <div class="v">₹<?= number_format($peak,0) ?></div>
            <div class="l">Peak Month</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#e0802d,#f59e0b)">
            <div class="v">₹<?= number_format($avg,0) ?></div>
            <div class="l">Monthly Average</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)">
            <div class="v"><?= count($monthly) ?></div>
            <div class="l">Months Tracked</div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="bil-card">
    <div class="bil-head"><span><i class="fa fa-line-chart"></i> Monthly Revenue Trend</span></div>
    <div class="chart-wrap"><canvas id="revChart"></canvas></div>
</div>

<!-- Table -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-table"></i> Month-by-Month Breakdown</span>
        <button onclick="exportRev()" class="btn btn-xs"
            style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3)">
            <i class="fa fa-download"></i> Export CSV
        </button>
    </div>
    <table class="rev-tbl" id="revTable">
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align:right">Revenue (₹)</th>
                <th style="text-align:right">Growth</th>
                <th style="width:180px">Bar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($monthly as $i=>$row):
        $prev  = $i>0?($monthly[$i-1]['revenue']??0):0;
        $growth= $prev>0?(($row['revenue']-$prev)/$prev*100):0;
        $pct   = $peak>0?min(100,($row['revenue']/$peak*100)):0;
      ?>
            <tr>
                <td style="font-weight:600"><?= $row['month'] ?></td>
                <td style="text-align:right;font-weight:700;color:var(--p)">₹<?= number_format($row['revenue'],2) ?>
                </td>
                <td style="text-align:right">
                    <?php if($i>0): ?>
                    <span style="color:<?= $growth>=0?'#16a34a':'#dc2626' ?>;font-weight:700;font-size:12px">
                        <?= $growth>=0?'▲':'▼' ?> <?= abs(round($growth,1)) ?>%
                    </span>
                    <?php else: ?><span style="color:#94a3b8;font-size:12px">—</span><?php endif; ?>
                </td>
                <td>
                    <div class="bar-vis">
                        <div class="bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td style="text-align:right;color:var(--p)">₹<?= number_format($total,2) ?></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('revChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($monthly,'month')) ?>,
        datasets: [{
            label: 'Revenue (₹)',
            data: <?= json_encode(array_column($monthly,'revenue')) ?>,
            backgroundColor: 'rgba(11,8,149,.08)',
            borderColor: '#0b0895',
            borderWidth: 2.5,
            pointBackgroundColor: '#e0802d',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            tension: .35,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f1f5f9'
                },
                ticks: {
                    callback: v => '₹' + v.toLocaleString('en-IN')
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

function exportRev() {
    var rows = [
        ['Month', 'Revenue']
    ];
    <?php foreach($monthly as $row): ?>
    rows.push(['<?= $row['month'] ?>', '<?= $row['revenue'] ?>']);
    <?php endforeach; ?>
    var csv = rows.map(r => r.join(',')).join('\n');
    var a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = 'Revenue_Report.csv';
    a.click();
}
</script>