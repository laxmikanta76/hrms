<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
:root {
    --p: #0b0895;
    --s: #e0802d;
    --bd: #e2e8f0;
    --r: 10px;
    --sh: 0 4px 20px rgba(0, 0, 0, .07);
}

.toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.toolbar .sp {
    flex: 1;
}

.filter-bar {
    background: #fff;
    border-radius: 9px;
    border: 1px solid var(--bd);
    padding: 13px 16px;
    margin-bottom: 16px;
    display: flex;
    gap: 10px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.tbl-wrap {
    background: #fff;
    border-radius: var(--r);
    box-shadow: var(--sh);
    border: 1px solid var(--bd);
    overflow: hidden;
}

.tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 10px 13px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
    white-space: nowrap;
}

.tbl tbody td {
    padding: 10px 13px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.tbl tbody tr:hover td {
    background: #f8fafc;
}

.tbl tbody tr:last-child td {
    border-bottom: none;
}

.tbl tfoot td {
    padding: 10px 13px;
    font-weight: 800;
    background: #f0f4ff;
    border-top: 2px solid var(--p);
}

.method-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: #f0f4ff;
    color: var(--p);
}

.empty-st {
    text-align: center;
    padding: 50px;
    color: #94a3b8;
}

.empty-st i {
    font-size: 44px;
    display: block;
    margin-bottom: 14px;
    opacity: .22;
}

/* Summary cards */
.sum-row {
    display: flex;
    gap: 14px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.sum-card {
    flex: 1;
    min-width: 160px;
    border-radius: 10px;
    padding: 16px 18px;
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
</style>

<?php
$total_collected = array_sum(array_column((array)$payments, 'amount'));
$count = count($payments);
?>

<!-- Summary -->
<div class="sum-row">
    <div class="sum-card" style="background:linear-gradient(135deg,#16a34a,#22c55e)">
        <div class="v">₹<?= number_format($total_collected,2) ?></div>
        <div class="l">Total Collected</div>
    </div>
    <div class="sum-card" style="background:linear-gradient(135deg,#0b0895,#1e40af)">
        <div class="v"><?= $count ?></div>
        <div class="l">Total Payments</div>
    </div>
</div>

<div class="toolbar">
    <a href="<?= site_url('client_billing/Client_billing/record_payment') ?>" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i> Record Payment
    </a>
    <div class="sp"></div>
    <span style="font-size:13px;color:#94a3b8"><?= $count ?> payments</span>
</div>

<!-- Filter -->
<div class="filter-bar">
    <form method="get" action="<?= site_url('client_billing/Client_billing/payments') ?>"
        style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;width:100%">
        <div style="flex:1.5;min-width:150px">
            <label
                style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;text-transform:uppercase">Client</label>
            <select name="client_id" class="form-control input-sm">
                <option value="">All Clients</option>
                <?php foreach($clients as $c): ?>
                <option value="<?=$c->id?>" <?= ($filters['client_id']??'')==$c->id?'selected':'' ?>>
                    <?= htmlspecialchars($c->company_name) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="flex:1;min-width:120px">
            <label
                style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;text-transform:uppercase">From</label>
            <input type="date" name="date_from" value="<?= $filters['date_from']??'' ?>" class="form-control input-sm">
        </div>
        <div style="flex:1;min-width:120px">
            <label
                style="font-size:11px;font-weight:700;color:#64748b;display:block;margin-bottom:4px;text-transform:uppercase">To</label>
            <input type="date" name="date_to" value="<?= $filters['date_to']??'' ?>" class="form-control input-sm">
        </div>
        <div style="display:flex;align-items:flex-end;gap:6px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Filter</button>
            <a href="<?= site_url('client_billing/Client_billing/payments') ?>" class="btn btn-default btn-sm">Reset</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="tbl-wrap">
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Payment #</th>
                <th>Invoice</th>
                <th>Client</th>
                <th>Date</th>
                <th>Method</th>
                <th style="text-align:right">Amount (₹)</th>
                <th>Ref / TXN</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($payments)): ?>
            <tr>
                <td colspan="9">
                    <div class="empty-st">
                        <i class="fa fa-credit-card"></i>
                        <div style="font-size:15px;font-weight:600;margin-bottom:8px">No payments recorded yet</div>
                        <a href="<?= site_url('client_billing/Client_billing/record_payment') ?>"
                            class="btn btn-success btn-sm">Record First Payment</a>
                    </div>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach($payments as $i=>$p):
        $sc=['success'=>['#dcfce7','#15803d'],'pending'=>['#fef3c7','#92400e'],'failed'=>['#fee2e2','#991b1b'],'refunded'=>['#f3f4f6','#4b5563']];
        $s=$sc[$p->status]??$sc['success'];
      ?>
            <tr>
                <td style="color:#94a3b8"><?= $i+1 ?></td>
                <td style="font-family:monospace;font-size:12px;font-weight:700;color:var(--p)">
                    <?= $p->payment_number ?></td>
                <td>
                    <a href="<?= site_url('client_billing/Client_billing/view_invoice/'.$p->invoice_id) ?>"
                        style="font-weight:600;color:var(--p)">
                        <?= $p->invoice_number ?>
                    </a>
                </td>
                <td style="font-weight:600"><?= htmlspecialchars($p->company_name??'') ?></td>
                <td><?= date('d M Y',strtotime($p->payment_date)) ?></td>
                <td><span class="method-pill"><?= $p->payment_method ?></span></td>
                <td style="text-align:right;font-weight:800;font-size:14px;color:#16a34a">
                    ₹<?= number_format($p->amount,2) ?>
                </td>
                <td
                    style="font-size:12px;color:#64748b;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                    <?= $p->transaction_ref ?: '—' ?>
                </td>
                <td>
                    <span
                        style="background:<?=$s[0]?>;color:<?=$s[1]?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700">
                        <?= ucfirst($p->status) ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if(!empty($payments)): ?>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right;color:#64748b">Total Collected:</td>
                <td style="text-align:right;color:#16a34a">₹<?= number_format($total_collected,2) ?></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div>