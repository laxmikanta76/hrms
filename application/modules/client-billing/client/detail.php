<?php defined('BASEPATH') OR exit('No direct script access allowed');
$c=$client; $s=$stats;
$bmap=['paid'=>'#15803d','unpaid'=>'#92400e','overdue'=>'#991b1b','draft'=>'#64748b','partial'=>'#1e40af','sent'=>'#4338ca','cancelled'=>'#4b5563'];
$bgmap=['paid'=>'#dcfce7','unpaid'=>'#fef3c7','overdue'=>'#fee2e2','draft'=>'#f1f5f9','partial'=>'#dbeafe','sent'=>'#e0e7ff','cancelled'=>'#f3f4f6'];
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
    margin-bottom: 18px;
    overflow: hidden;
}

.bil-head {
    background: linear-gradient(135deg, var(--p), #1e40af);
    color: #fff;
    padding: 12px 18px;
    font-size: 13.5px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 7px;
    justify-content: space-between;
}

.bil-body {
    padding: 16px 18px;
}

.stat-mini {
    text-align: center;
    padding: 14px;
    border: 1px solid var(--bd);
    border-radius: 8px;
}

.stat-mini .v {
    font-size: 19px;
    font-weight: 800;
    color: var(--p);
}

.stat-mini .l {
    font-size: 10.5px;
    color: #94a3b8;
    margin-top: 2px;
}

.tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 9px 12px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.tbl tbody td {
    padding: 9px 12px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.tbl tbody tr:last-child td {
    border-bottom: none;
}
</style>

<div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:18px">
    <a href="<?= site_url('client_billing/client_billing/clients') ?>" class="btn btn-default btn-sm"><i
            class="fa fa-arrow-left"></i> Clients</a>
    <a href="<?= site_url('client_billing/client_billing/edit_client/'.$c->id) ?>" class="btn btn-warning btn-sm"><i
            class="fa fa-pencil"></i> Edit</a>
    <a href="<?= site_url('client_billing/client_billing/create_invoice?client_id='.$c->id) ?>"
        class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Invoice</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="bil-card">
            <div style="background:linear-gradient(135deg,var(--p),#1e40af);padding:26px 18px;text-align:center">
                <div
                    style="width:66px;height:66px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:900;color:#fff;margin:0 auto 10px">
                    <?= strtoupper(substr($c->company_name,0,1)) ?>
                </div>
                <div style="font-size:17px;font-weight:800;color:#fff"><?= htmlspecialchars($c->company_name) ?></div>
                <?php if($c->contact_person): ?><div style="font-size:12px;color:rgba(255,255,255,.7);margin-top:3px">
                    <?= htmlspecialchars($c->contact_person) ?></div><?php endif; ?>
            </div>
            <div class="bil-body">
                <table style="width:100%;font-size:12.5px;border-collapse:collapse">
                    <?php if($c->email):   ?><tr>
                        <td style="color:#94a3b8;padding:4px 0;width:28%"><i class="fa fa-envelope-o"></i></td>
                        <td><?= htmlspecialchars($c->email) ?></td>
                    </tr><?php endif; ?>
                    <?php if($c->mobile||$c->phone): ?><tr>
                        <td style="color:#94a3b8;padding:4px 0"><i class="fa fa-phone"></i></td>
                        <td><?= $c->mobile?:$c->phone ?></td>
                    </tr><?php endif; ?>
                    <?php if($c->billing_address): ?><tr>
                        <td style="color:#94a3b8;padding:4px 0"><i class="fa fa-map-marker"></i></td>
                        <td style="font-size:11.5px"><?= nl2br(htmlspecialchars($c->billing_address)) ?>,
                            <?= implode(', ',array_filter([$c->billing_city,$c->billing_state,$c->billing_pincode])) ?>
                        </td>
                    </tr><?php endif; ?>
                    <?php if($c->gstin): ?><tr>
                        <td style="color:#94a3b8;padding:4px 0"><i class="fa fa-id-card-o"></i></td>
                        <td style="font-family:monospace;font-size:11.5px"><?= $c->gstin ?></td>
                    </tr><?php endif; ?>
                    <?php if($c->payment_terms): ?><tr>
                        <td style="color:#94a3b8;padding:4px 0"><i class="fa fa-calendar"></i></td>
                        <td><?= $c->payment_terms ?></td>
                    </tr><?php endif; ?>
                </table>
            </div>
        </div>

        <div class="bil-card">
            <div class="bil-head"><span><i class="fa fa-bar-chart"></i> Account Summary</span></div>
            <div class="bil-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <div class="stat-mini">
                        <div class="v"><?= $s->total_invoices ?></div>
                        <div class="l">Invoices</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" style="color:var(--s)">₹<?= number_format($s->total_billed,0) ?></div>
                        <div class="l">Total Billed</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" style="color:#16a34a">₹<?= number_format($s->total_paid,0) ?></div>
                        <div class="l">Total Paid</div>
                    </div>
                    <div class="stat-mini">
                        <div class="v" style="color:<?= $s->balance_due>0?'#dc2626':'#16a34a' ?>">
                            ₹<?= number_format($s->balance_due,0) ?></div>
                        <div class="l">Balance Due</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="bil-card">
            <div class="bil-head"><span><i class="fa fa-file-text-o"></i> Invoice History</span>
                <a href="<?= site_url('client_billing/client_billing/create_invoice?client_id='.$c->id) ?>"
                    class="btn btn-xs btn-light"><i class="fa fa-plus"></i> New</a>
            </div>
            <div style="overflow-x:auto">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($invoices)): ?><tr>
                            <td colspan="7" style="text-align:center;padding:24px;color:#94a3b8">No invoices yet.</td>
                        </tr><?php endif; ?>
                        <?php foreach($invoices as $inv): ?>
                        <tr>
                            <td style="font-weight:700;color:var(--p);font-family:monospace"><?= $inv->invoice_number ?>
                            </td>
                            <td><?= date('d M Y',strtotime($inv->invoice_date)) ?></td>
                            <td><?= $inv->due_date?date('d M Y',strtotime($inv->due_date)):'—' ?></td>
                            <td style="font-weight:700">₹<?= number_format($inv->grand_total,2) ?></td>
                            <td style="font-weight:600;color:<?= $inv->balance_due>0?'#dc2626':'#16a34a' ?>">
                                ₹<?= number_format($inv->balance_due,2) ?></td>
                            <td><span
                                    style="background:<?= $bgmap[$inv->status]??'#f1f5f9' ?>;color:<?= $bmap[$inv->status]??'#64748b' ?>;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:700"><?= ucfirst($inv->status) ?></span>
                            </td>
                            <td>
                                <a href="<?= site_url('client_billing/client_billing/view_invoice/'.$inv->id) ?>"
                                    class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                                <a href="<?= site_url('client_billing/client_billing/print_invoice/'.$inv->id) ?>"
                                    class="btn btn-xs btn-default" target="_blank"><i class="fa fa-print"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bil-card">
            <div class="bil-head"><i class="fa fa-credit-card"></i> Payment History</div>
            <div style="overflow-x:auto">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th>Payment #</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Ref</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($payments)): ?><tr>
                            <td colspan="6" style="text-align:center;padding:24px;color:#94a3b8">No payments yet.</td>
                        </tr><?php endif; ?>
                        <?php foreach($payments as $p): ?>
                        <tr>
                            <td style="font-family:monospace;font-size:12px"><?= $p->payment_number ?></td>
                            <td><?= $p->invoice_number ?></td>
                            <td><?= date('d M Y',strtotime($p->payment_date)) ?></td>
                            <td><?= $p->payment_method ?></td>
                            <td style="font-weight:700;color:#16a34a">₹<?= number_format($p->amount,2) ?></td>
                            <td style="font-size:11.5px;color:#94a3b8"><?= $p->transaction_ref?:'—' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>