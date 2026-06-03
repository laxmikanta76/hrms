<?php defined('BASEPATH') OR exit('No direct script access allowed');
$total_unpaid  = array_sum(array_column((array)$invoices,'balance_due'));
$total_overdue = array_sum(array_column((array)$overdue,'balance_due'));
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

.out-tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.out-tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 10px 13px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.out-tbl tbody td {
    padding: 10px 13px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.out-tbl tbody tr:hover td {
    background: #f8fafc;
}

.out-tbl tbody tr:last-child td {
    border-bottom: none;
}

.out-tbl tfoot td {
    padding: 10px 13px;
    font-weight: 800;
    background: #f0f4ff;
    border-top: 2px solid var(--p);
}

.tab-btns {
    display: flex;
    gap: 6px;
    margin-bottom: 16px;
}

.tab-btn {
    padding: 6px 20px;
    border-radius: 20px;
    border: 1.5px solid var(--bd);
    background: #fff;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: .15s;
}

.tab-btn.active {
    background: var(--p);
    color: #fff;
    border-color: var(--p);
}

.days-pill {
    background: #fee2e2;
    color: #991b1b;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-block;
}
</style>

<!-- Summary Cards -->
<div class="row" style="margin-bottom:20px">
    <div class="col-md-4 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#e0802d,#f59e0b)">
            <div class="v">₹<?= number_format($total_unpaid,0) ?></div>
            <div class="l"><?= count($invoices) ?> Unpaid Invoices</div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#dc2626,#ef4444)">
            <div class="v">₹<?= number_format($total_overdue,0) ?></div>
            <div class="l"><?= count($overdue) ?> Overdue Invoices</div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6" style="margin-bottom:12px">
        <div class="sum-card" style="background:linear-gradient(135deg,#0b0895,#1e40af)">
            <div class="v">₹<?= number_format($total_unpaid+$total_overdue,0) ?></div>
            <div class="l">Total Outstanding</div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tab-btns">
    <button class="tab-btn active" onclick="showTab('unpaid',this)">Unpaid (<?= count($invoices) ?>)</button>
    <button class="tab-btn" onclick="showTab('overdue',this)">Overdue (<?= count($overdue) ?>)</button>
</div>

<!-- Unpaid Tab -->
<div id="tab-unpaid">
    <div class="bil-card">
        <div class="bil-head"><i class="fa fa-clock-o"></i> Unpaid Invoices</div>
        <table class="out-tbl">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:right">Balance Due</th>
                    <th style="text-align:center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($invoices)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:30px;color:#94a3b8">🎉 No unpaid invoices!</td>
                </tr>
                <?php endif; ?>
                <?php foreach($invoices as $inv): ?>
                <tr>
                    <td style="font-weight:700;color:var(--p);font-family:monospace"><?= $inv->invoice_number ?></td>
                    <td>
                        <div style="font-weight:600"><?= htmlspecialchars($inv->company_name??'') ?></div>
                        <?php if($inv->contact_person): ?><div style="font-size:11px;color:#94a3b8">
                            <?= htmlspecialchars($inv->contact_person) ?></div><?php endif; ?>
                    </td>
                    <td><?= date('d M Y',strtotime($inv->invoice_date)) ?></td>
                    <td><?= $inv->due_date?date('d M Y',strtotime($inv->due_date)):'—' ?></td>
                    <td style="text-align:right">₹<?= number_format($inv->grand_total,2) ?></td>
                    <td style="text-align:right;font-weight:800;color:#e0802d">
                        ₹<?= number_format($inv->balance_due,2) ?></td>
                    <td style="text-align:center">
                        <a href="<?= site_url('client_billing/client_billing/view_invoice/'.$inv->id) ?>"
                            class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                        <a href="<?= site_url('client_billing/client_billing/record_payment?invoice_id='.$inv->id) ?>"
                            class="btn btn-xs btn-success"><i class="fa fa-usd"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right">Total Unpaid:</td>
                    <td style="text-align:right;color:#e0802d">₹<?= number_format($total_unpaid,2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Overdue Tab -->
<div id="tab-overdue" style="display:none">
    <div class="bil-card">
        <div class="bil-head" style="background:linear-gradient(135deg,#dc2626,#b91c1c)"><i
                class="fa fa-exclamation-triangle"></i> Overdue Invoices</div>
        <table class="out-tbl">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Due Date</th>
                    <th>Days Overdue</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:right">Balance Due</th>
                    <th style="text-align:center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($overdue)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:30px;color:#94a3b8">🎉 No overdue invoices!</td>
                </tr>
                <?php endif; ?>
                <?php foreach($overdue as $inv):
          $days = $inv->due_date ? max(0,(int)((time()-strtotime($inv->due_date))/86400)) : 0;
        ?>
                <tr>
                    <td style="font-weight:700;color:#dc2626;font-family:monospace"><?= $inv->invoice_number ?></td>
                    <td>
                        <div style="font-weight:600"><?= htmlspecialchars($inv->company_name??'') ?></div>
                        <?php if($inv->contact_person): ?><div style="font-size:11px;color:#94a3b8">
                            <?= htmlspecialchars($inv->contact_person) ?></div><?php endif; ?>
                    </td>
                    <td style="color:#dc2626;font-weight:600">
                        <?= $inv->due_date?date('d M Y',strtotime($inv->due_date)):'—' ?></td>
                    <td><span class="days-pill"><?= $days ?> days late</span></td>
                    <td style="text-align:right">₹<?= number_format($inv->grand_total,2) ?></td>
                    <td style="text-align:right;font-weight:800;color:#dc2626">
                        ₹<?= number_format($inv->balance_due,2) ?></td>
                    <td style="text-align:center">
                        <a href="<?= site_url('client_billing/client_billing/view_invoice/'.$inv->id) ?>"
                            class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                        <a href="<?= site_url('client_billing/client_billing/record_payment?invoice_id='.$inv->id) ?>"
                            class="btn btn-xs btn-success"><i class="fa fa-usd"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right">Total Overdue:</td>
                    <td style="text-align:right;color:#dc2626">₹<?= number_format($total_overdue,2) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
function showTab(name, btn) {
    ['unpaid', 'overdue'].forEach(function(t) {
        document.getElementById('tab-' + t).style.display = 'none';
    });
    document.getElementById('tab-' + name).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');
}
</script>