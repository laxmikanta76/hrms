<?php defined('BASEPATH') OR exit('No direct script access allowed');
$total_taxable = array_sum(array_column((array)$gst_data,'taxable'));
$total_cgst    = array_sum(array_column((array)$gst_data,'cgst'));
$total_sgst    = array_sum(array_column((array)$gst_data,'sgst'));
$total_igst    = array_sum(array_column((array)$gst_data,'igst'));
$total_gst     = array_sum(array_column((array)$gst_data,'total_gst'));
$total_grand   = array_sum(array_column((array)$gst_data,'total'));
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
    font-size: 20px;
    font-weight: 800;
}

.sum-card .l {
    font-size: 11px;
    opacity: .82;
    margin-top: 3px;
}

.gst-tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.gst-tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 10px 13px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.gst-tbl tbody td {
    padding: 10px 13px;
    border-bottom: 1px solid #f1f5f9;
}

.gst-tbl tbody tr:hover td {
    background: #f8fafc;
}

.gst-tbl tfoot td {
    padding: 10px 13px;
    font-weight: 800;
    background: #f0f4ff;
    border-top: 2px solid var(--p);
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
    font-size: 13px;
}
</style>

<!-- Year Filter Header -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-percent"></i> GST Report — <?= $year ?></span>
        <form method="get" action="<?= site_url('client_billing/Client_billing/gst_report') ?>" class="year-form">
            <input type="number" name="year" value="<?= $year ?>" min="2020" max="<?= date('Y') ?>">
            <button type="submit">Go</button>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row" style="margin-bottom:20px">
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#0b0895,#1e40af)">
            <div class="v">₹<?= number_format($total_taxable,0) ?></div>
            <div class="l">Total Taxable Amount</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
            <div class="v">₹<?= number_format($total_cgst,0) ?></div>
            <div class="l">Total CGST Collected</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#e0802d,#f59e0b)">
            <div class="v">₹<?= number_format($total_sgst,0) ?></div>
            <div class="l">Total SGST Collected</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)">
            <div class="v">₹<?= number_format($total_gst,0) ?></div>
            <div class="l">Total GST Collected</div>
        </div>
    </div>
</div>

<!-- GST Table -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-table"></i> Month-wise GST Breakdown — <?= $year ?></span>
        <button onclick="exportGST()" class="btn btn-xs"
            style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3)">
            <i class="fa fa-download"></i> Export CSV
        </button>
    </div>
    <table class="gst-tbl" id="gstTable">
        <thead>
            <tr>
                <th>Month</th>
                <th style="text-align:right">Taxable Amount (₹)</th>
                <th style="text-align:right">CGST (₹)</th>
                <th style="text-align:right">SGST (₹)</th>
                <th style="text-align:right">IGST (₹)</th>
                <th style="text-align:right">Total GST (₹)</th>
                <th style="text-align:right">Invoice Total (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($gst_data)): ?>
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:#94a3b8">
                    No invoice data for <?= $year ?>.
                </td>
            </tr>
            <?php endif; ?>
            <?php foreach($gst_data as $row): ?>
            <tr>
                <td style="font-weight:700"><?= $row->month_name ?></td>
                <td style="text-align:right">₹<?= number_format($row->taxable,2) ?></td>
                <td style="text-align:right;color:#16a34a;font-weight:600">₹<?= number_format($row->cgst,2) ?></td>
                <td style="text-align:right;color:#e0802d;font-weight:600">₹<?= number_format($row->sgst,2) ?></td>
                <td style="text-align:right;color:#7c3aed;font-weight:600">₹<?= number_format($row->igst,2) ?></td>
                <td style="text-align:right;font-weight:800;color:var(--p)">₹<?= number_format($row->total_gst,2) ?>
                </td>
                <td style="text-align:right;font-weight:700">₹<?= number_format($row->total,2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>ANNUAL TOTAL</td>
                <td style="text-align:right">₹<?= number_format($total_taxable,2) ?></td>
                <td style="text-align:right;color:#16a34a">₹<?= number_format($total_cgst,2) ?></td>
                <td style="text-align:right;color:#e0802d">₹<?= number_format($total_sgst,2) ?></td>
                <td style="text-align:right;color:#7c3aed">₹<?= number_format($total_igst,2) ?></td>
                <td style="text-align:right;color:var(--p)">₹<?= number_format($total_gst,2) ?></td>
                <td style="text-align:right">₹<?= number_format($total_grand,2) ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function exportGST() {
    var rows = [
        ['Month', 'Taxable Amount', 'CGST', 'SGST', 'IGST', 'Total GST', 'Invoice Total']
    ];
    document.querySelectorAll('#gstTable tbody tr').forEach(function(tr) {
        var cells = tr.querySelectorAll('td');
        if (cells.length > 1) rows.push(Array.from(cells).map(function(td) {
            return td.textContent.trim().replace(/₹|,/g, '');
        }));
    });
    var csv = rows.map(function(r) {
        return r.join(',');
    }).join('\n');
    var a = document.createElement('a');
    a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    a.download = 'GST_Report_<?= $year ?>.csv';
    a.click();
}
</script>