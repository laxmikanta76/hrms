<?php defined('BASEPATH') OR exit('No direct script access allowed');
$total_rev = array_sum(array_column((array)$data,'total'));
$peak_svc  = !empty($data)?max(array_column((array)$data,'total')):0;
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

.svc-tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.svc-tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 10px 13px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.svc-tbl tbody td {
    padding: 10px 13px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.svc-tbl tbody tr:hover td {
    background: #f8fafc;
}

.svc-tbl tbody tr:last-child td {
    border-bottom: none;
}

.svc-tbl tfoot td {
    padding: 10px 13px;
    font-weight: 800;
    background: #f0f4ff;
    border-top: 2px solid var(--p);
}

.bar-vis {
    background: #f1f5f9;
    border-radius: 10px;
    height: 9px;
    overflow: hidden;
    min-width: 100px;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--p), var(--s));
    border-radius: 10px;
}

.year-form {
    display: flex;
    gap: 7px;
    align-items: center;
}

.year-form input {
    border: 1.5px solid rgba(255, 255, 255, .35);
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 13px;
    color: #fff;
    background: rgba(255, 255, 255, .15);
    width: 80px;
}

.year-form button {
    background: var(--s);
    border: none;
    border-radius: 6px;
    padding: 5px 14px;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
}
</style>

<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-cogs"></i> Service-wise Revenue — <?= $year ?></span>
        <form method="get" action="<?= site_url('client_billing/Client_billing/service_report') ?>" class="year-form">
            <input type="number" name="year" value="<?= $year ?>" min="2020" max="<?= date('Y') ?>">
            <button type="submit">Go</button>
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row" style="margin-bottom:20px">
    <div class="col-md-4 col-sm-6" style="margin-bottom:12px">
        <div
            style="background:linear-gradient(135deg,#0b0895,#1e40af);border-radius:10px;padding:18px 20px;color:#fff;text-align:center">
            <div style="font-size:22px;font-weight:800">₹<?= number_format($total_rev,0) ?></div>
            <div style="font-size:11px;opacity:.82;margin-top:3px">Total Service Revenue — <?= $year ?></div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6" style="margin-bottom:12px">
        <div
            style="background:linear-gradient(135deg,#16a34a,#22c55e);border-radius:10px;padding:18px 20px;color:#fff;text-align:center">
            <div style="font-size:22px;font-weight:800"><?= count($data) ?></div>
            <div style="font-size:11px;opacity:.82;margin-top:3px">Unique Services Billed</div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6" style="margin-bottom:12px">
        <div
            style="background:linear-gradient(135deg,#e0802d,#f59e0b);border-radius:10px;padding:18px 20px;color:#fff;text-align:center">
            <div style="font-size:22px;font-weight:800">₹<?= number_format($peak_svc,0) ?></div>
            <div style="font-size:11px;opacity:.82;margin-top:3px">Top Service Revenue</div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-table"></i> Service Revenue Breakdown</span>
        <button onclick="exportSvc()" class="btn btn-xs"
            style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3)">
            <i class="fa fa-download"></i> Export CSV
        </button>
    </div>
    <table class="svc-tbl" id="svcRptTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Service / Item Description</th>
                <th>HSN/SAC</th>
                <th style="text-align:center">Invoices</th>
                <th style="text-align:right">Total Qty</th>
                <th style="text-align:right">Taxable (₹)</th>
                <th style="text-align:right">Total Revenue (₹)</th>
                <th style="min-width:140px">Share</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data)): ?>
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8">
                    No service data for <?= $year ?>.
                </td>
            </tr>
            <?php endif; ?>
            <?php foreach($data as $i=>$row):
        $pct = $total_rev>0?min(100,($row->total/$total_rev)*100):0;
        $bar = $peak_svc>0?min(100,($row->total/$peak_svc)*100):0;
      ?>
            <tr>
                <td style="color:#94a3b8;font-weight:700"><?= $i+1 ?></td>
                <td style="font-weight:600;max-width:220px"><?= htmlspecialchars($row->item_description) ?></td>
                <td style="font-family:monospace;font-size:12px;color:var(--p);font-weight:700">
                    <?= $row->hsn_sac_code?:'—' ?></td>
                <td style="text-align:center">
                    <span
                        style="background:#e0e7ff;color:#4338ca;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:700"><?= $row->invoices ?></span>
                </td>
                <td style="text-align:right;font-weight:600">
                    <?= rtrim(rtrim(number_format($row->total_qty,3,'.',','),'0'),'.') ?></td>
                <td style="text-align:right">₹<?= number_format($row->taxable,2) ?></td>
                <td style="text-align:right;font-weight:800;color:var(--p);font-size:14px">
                    ₹<?= number_format($row->total,2) ?></td>
                <td>
                    <div style="font-size:11px;color:#64748b;margin-bottom:3px"><?= round($pct,1) ?>%</div>
                    <div class="bar-vis">
                        <div class="bar-fill" style="width:<?= $bar ?>%"></div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right">Total Revenue:</td>
                <td style="text-align:right">₹<?= number_format(array_sum(array_column((array)$data,'taxable')),2) ?>
                </td>
                <td style="text-align:right;color:var(--p)">₹<?= number_format($total_rev,2) ?></td>
                <td>100%</td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function exportSvc() {
    var rows = [
        ['#', 'Service', 'HSN/SAC', 'Invoices', 'Total Qty', 'Taxable', 'Total Revenue', 'Share%']
    ];
    document.querySelectorAll('#svcRptTable tbody tr').forEach(function(tr, i) {
        var cells = tr.querySelectorAll('td');
        if (cells.length > 1) rows.push(Array.from(cells).map(function(td) {
            return '"' + td.textContent.trim().replace(/₹|,/g, '').replace(/"/g, '""') + '"';
        }));
    });
    var csv = rows.map(function(r) {
        return r.join(',');
    }).join('\n');
    var a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = 'Service_Revenue_<?= $year ?>.csv';
    a.click();
}
</script>