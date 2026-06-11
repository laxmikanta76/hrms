<?php defined('BASEPATH') OR exit('No direct script access allowed');
$inv=$invoice; $c=$invoice->client; $co=$invoice->company; $bank=$invoice->bank;
$bmap=['paid'=>['#dcfce7','#15803d'],'unpaid'=>['#fef3c7','#92400e'],'overdue'=>['#fee2e2','#991b1b'],'draft'=>['#f1f5f9','#64748b'],'partial'=>['#dbeafe','#1e40af'],'sent'=>['#e0e7ff','#4338ca'],'cancelled'=>['#f3f4f6','#4b5563']];
$sc=$bmap[$inv->status]??['#f1f5f9','#64748b'];
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

.meta-lbl {
    font-size: 10.5px;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: .4px;
    margin-bottom: 2px;
}

.meta-val {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
}

.it {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}

.it thead th {
    background: var(--p);
    color: #fff;
    padding: 8px 11px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.it tbody td {
    padding: 8px 11px;
    border-bottom: 1px solid #f1f5f9;
}

.it tfoot td {
    padding: 8px 11px;
    font-weight: 700;
    background: #f0f4ff;
}

.sum-tbl {
    width: 100%;
    font-size: 13px;
    border-collapse: collapse;
}

.sum-tbl td {
    padding: 5px 0;
}

.sum-tbl td:last-child {
    text-align: right;
    font-weight: 600;
}

.sum-tbl .grand td {
    font-size: 15px;
    font-weight: 800;
    color: var(--p);
    border-top: 2px solid var(--p);
    padding-top: 9px;
}

.tl-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--p);
    flex-shrink: 0;
    margin-top: 4px;
}

.tl-body {
    flex: 1;
    font-size: 12.5px;
}

.tl-time {
    font-size: 11px;
    color: #94a3b8;
}

.tl-item {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    padding: 9px 0;
    border-bottom: 1px dashed var(--bd);
}

.tl-item:last-child {
    border-bottom: none;
}
</style>

<!-- Toolbar -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;align-items:center">
    <a href="<?= site_url('client_billing/Client_billing/edit_invoice/'.$inv->id) ?>" class="btn btn-warning btn-sm"><i
            class="fa fa-pencil"></i> Edit</a>
    <a href="<?= site_url('client_billing/Client_billing/print_invoice/'.$inv->id) ?>" class="btn btn-default btn-sm"
        target="_blank"><i class="fa fa-print"></i> Print</a>
    <a href="<?= site_url('client_billing/Client_billing/download_pdf/'.$inv->id) ?>" class="btn btn-danger btn-sm"><i
            class="fa fa-file-pdf-o"></i> PDF</a>
    <a href="<?= site_url('client_billing/Client_billing/record_payment?invoice_id='.$inv->id) ?>"
        class="btn btn-success btn-sm"
        <?= in_array($inv->status,['paid','cancelled'])?'style="opacity:.45;pointer-events:none"':'' ?>><i
            class="fa fa-credit-card"></i> Record Payment</a>
    <a href="<?= site_url('client_billing/Client_billing/duplicate_invoice/'.$inv->id) ?>"
        class="btn btn-primary btn-sm" onclick="return confirm('Duplicate this invoice?')"><i class="fa fa-copy"></i>
        Duplicate</a>

    <div class="dropdown" style="margin-left:auto">
        <button class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-exchange"></i>
            Status</button>
        <ul class="dropdown-menu dropdown-menu-right">
            <?php foreach(['draft'=>'Draft','sent'=>'Sent','unpaid'=>'Unpaid','paid'=>'Paid','partial'=>'Partial','overdue'=>'Overdue','cancelled'=>'Cancelled'] as $v=>$l): ?>
            <li>
                <form method="post" action="<?= site_url('client_billing/Client_billing/update_status/'.$inv->id) ?>"
                    style="display:inline">
                    <?= csrf_field() ?><input type="hidden" name="status" value="<?=$v?>">
                    <button type="submit"
                        style="background:none;border:none;width:100%;text-align:left;padding:4px 18px;cursor:pointer"><?=$l?></button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <a href="<?= site_url('client_billing/Client_billing/invoices') ?>" class="btn btn-default btn-sm"><i
            class="fa fa-arrow-left"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-8">

        <!-- Header Card -->
        <div class="bil-card">
            <div class="bil-head">
                <span><i class="fa fa-file-text-o"></i> <?= $inv->invoice_number ?></span>
                <span
                    style="background:<?=$sc[0]?>;color:<?=$sc[1]?>;padding:4px 14px;border-radius:20px;font-size:11.5px;font-weight:800"><?= strtoupper($inv->status) ?></span>
            </div>
            <div class="bil-body">
                <div class="row">
                    <div class="col-md-6">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div>
                                <div class="meta-lbl">Invoice Date</div>
                                <div class="meta-val"><?= date('d M Y',strtotime($inv->invoice_date)) ?></div>
                            </div>
                            <div>
                                <div class="meta-lbl">Due Date</div>
                                <div class="meta-val" style="color:<?= $inv->status==='overdue'?'#dc2626':'' ?>">
                                    <?= $inv->due_date?date('d M Y',strtotime($inv->due_date)):'—' ?></div>
                            </div>
                            <div>
                                <div class="meta-lbl">Invoice Type</div>
                                <div class="meta-val"><?= ucwords(str_replace('_',' ',$inv->invoice_type)) ?></div>
                            </div>
                            <div>
                                <div class="meta-lbl">Payment Terms</div>
                                <div class="meta-val"><?= $inv->payment_terms ?></div>
                            </div>
                            <?php if($inv->reference_number): ?><div>
                                <div class="meta-lbl">Reference #</div>
                                <div class="meta-val"><?= $inv->reference_number ?></div>
                            </div><?php endif; ?>
                            <?php if($inv->po_number): ?><div>
                                <div class="meta-lbl">PO Number</div>
                                <div class="meta-val"><?= $inv->po_number ?></div>
                            </div><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="meta-lbl" style="margin-bottom:5px">Bill To</div>
                                <div style="font-weight:700;font-size:13px">
                                    <?= htmlspecialchars($c->company_name??'') ?></div>
                                <div style="font-size:12px;color:#64748b;line-height:1.7">
                                    <?= $c->contact_person?'Attn: '.htmlspecialchars($c->contact_person).'<br>':'' ?>
                                    <?= $c->billing_address?nl2br(htmlspecialchars($c->billing_address)).'<br>':'' ?>
                                    <?= implode(', ',array_filter([$c->billing_city??null,$c->billing_state??null,$c->billing_pincode??null])) ?>
                                    <?= $c->gstin?'<br>GSTIN: '.$c->gstin:'' ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="meta-lbl" style="margin-bottom:5px">Ship To</div>
                                <div style="font-weight:700;font-size:13px">
                                    <?= htmlspecialchars($c->company_name??'') ?></div>
                                <div style="font-size:12px;color:#64748b;line-height:1.7">
                                    <?= ($c->shipping_address||$c->billing_address)?nl2br(htmlspecialchars($c->shipping_address?:$c->billing_address)).'<br>':'' ?>
                                    <?= implode(', ',array_filter([($c->shipping_city??null)?:($c->billing_city??null),($c->shipping_state??null)?:($c->billing_state??null)])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bil-card">
            <div class="bil-head"><span><i class="fa fa-list-ul"></i> Services / Items</span></div>
            <div style="overflow-x:auto">
                <table class="it">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Description</th>
                            <th>HSN/SAC</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th style="text-align:right">Rate (₹)</th>
                            <th style="text-align:right">Discount (₹)</th>
                            <th style="text-align:right">Taxable (₹)</th>
                            <?php if(!$inv->is_igst): ?><th style="text-align:right">CGST</th>
                            <th style="text-align:right">SGST</th>
                            <?php else: ?><th style="text-align:right">IGST</th><?php endif; ?>
                            <th style="text-align:right">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($inv->items as $it): ?>
                        <tr>
                            <td><?= $it->sl_no ?></td>
                            <td style="font-weight:600"><?= htmlspecialchars($it->item_description) ?></td>
                            <td style="font-family:monospace;font-size:11.5px"><?= $it->hsn_sac_code?:'—' ?></td>
                            <td style="text-align:center"><?= rtrim(rtrim($it->quantity,'0'),'.') ?></td>
                            <td><?= $it->unit ?></td>
                            <td style="text-align:right">₹<?= number_format($it->rate,2) ?></td>
                            <td style="text-align:right;color:#dc2626">₹<?= number_format($it->discount_amount,2) ?>
                            </td>
                            <td style="text-align:right">₹<?= number_format($it->taxable_amount,2) ?></td>
                            <?php if(!$inv->is_igst): ?>
                            <td style="text-align:right">
                                <?= $it->cgst_rate ?>%<br><small>₹<?= number_format($it->cgst_amount,2) ?></small></td>
                            <td style="text-align:right">
                                <?= $it->sgst_rate ?>%<br><small>₹<?= number_format($it->sgst_amount,2) ?></small></td>
                            <?php else: ?>
                            <td style="text-align:right">
                                <?= $it->igst_rate ?>%<br><small>₹<?= number_format($it->igst_amount,2) ?></small></td>
                            <?php endif; ?>
                            <td style="text-align:right;font-weight:700">₹<?= number_format($it->total_amount,2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="<?= $inv->is_igst?7:8 ?>" style="text-align:right">Total Discount:</td>
                            <td style="text-align:right;color:#dc2626">₹<?= number_format($inv->total_discount,2) ?>
                            </td>
                            <td colspan="<?= $inv->is_igst?2:3 ?>"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php if($inv->notes||$inv->terms_conditions): ?>
        <div class="bil-card">
            <div class="bil-head"><i class="fa fa-sticky-note-o"></i> Notes &amp; Terms</div>
            <div class="bil-body">
                <div class="row">
                    <?php if($inv->notes): ?><div class="col-md-6">
                        <div class="meta-lbl" style="margin-bottom:5px">Notes</div>
                        <div style="font-size:13px;line-height:1.6"><?= nl2br(htmlspecialchars($inv->notes)) ?></div>
                    </div><?php endif; ?>
                    <?php if($inv->terms_conditions): ?><div class="col-md-6">
                        <div class="meta-lbl" style="margin-bottom:5px">Terms &amp; Conditions</div>
                        <div style="font-size:13px;line-height:1.6">
                            <?= nl2br(htmlspecialchars($inv->terms_conditions)) ?></div>
                    </div><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /col-md-8 -->

    <div class="col-md-4">

        <!-- GST Summary -->
        <div class="bil-card">
            <div class="bil-head"><i class="fa fa-percent"></i> GST Summary</div>
            <div class="bil-body">
                <table class="sum-tbl">
                    <tr>
                        <td style="color:#64748b">Taxable Amount</td>
                        <td>₹<?= number_format($inv->taxable_amount,2) ?></td>
                    </tr>
                    <tr>
                        <td style="color:#64748b">Total Discount</td>
                        <td style="color:#dc2626">- ₹<?= number_format($inv->total_discount,2) ?></td>
                    </tr>
                    <?php if(!$inv->is_igst): ?>
                    <tr>
                        <td style="color:#64748b">CGST</td>
                        <td>₹<?= number_format($inv->cgst_amount,2) ?></td>
                    </tr>
                    <tr>
                        <td style="color:#64748b">SGST</td>
                        <td>₹<?= number_format($inv->sgst_amount,2) ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td style="color:#64748b">IGST</td>
                        <td>₹<?= number_format($inv->igst_amount,2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td style="color:#64748b">Round Off</td>
                        <td>₹<?= number_format($inv->round_off,2) ?></td>
                    </tr>
                    <tr class="grand">
                        <td>Grand Total</td>
                        <td>₹<?= number_format($inv->grand_total,2) ?></td>
                    </tr>
                    <tr>
                        <td style="color:#16a34a">Amount Paid</td>
                        <td style="color:#16a34a">₹<?= number_format($inv->amount_paid,2) ?></td>
                    </tr>
                    <tr>
                        <td style="color:#dc2626;font-weight:700">Balance Due</td>
                        <td style="color:#dc2626;font-weight:700">₹<?= number_format($inv->balance_due,2) ?></td>
                    </tr>
                </table>
                <div
                    style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:7px;padding:9px 12px;margin-top:10px;font-size:12px;color:#166534;font-style:italic">
                    <?= $inv->amount_in_words ?: 'Zero Rupees Only' ?>
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <?php if($bank): ?>
        <div class="bil-card">
            <div class="bil-head"><i class="fa fa-university"></i> Bank Details</div>
            <div class="bil-body">
                <table style="width:100%;font-size:12.5px;border-collapse:collapse">
                    <tr>
                        <td style="color:#94a3b8;padding:3px 0;width:38%">Bank</td>
                        <td style="font-weight:600"><?= $bank->bank_name ?></td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:3px 0">Account</td>
                        <td style="font-weight:600"><?= $bank->account_name ?></td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:3px 0">A/C No.</td>
                        <td style="font-family:monospace;font-weight:700"><?= $bank->account_number ?></td>
                    </tr>
                    <tr>
                        <td style="color:#94a3b8;padding:3px 0">IFSC</td>
                        <td style="font-weight:600"><?= $bank->ifsc_code ?></td>
                    </tr>
                    <?php if($bank->upi_id): ?><tr>
                        <td style="color:#94a3b8;padding:3px 0">UPI</td>
                        <td style="font-weight:600;color:var(--p)"><?= $bank->upi_id ?></td>
                    </tr><?php endif; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Payment History -->
        <div class="bil-card">
            <div class="bil-head"><i class="fa fa-history"></i> Payments</div>
            <div class="bil-body">
                <?php if(empty($inv->payments)): ?>
                <div style="text-align:center;color:#94a3b8;font-size:13px;padding:10px 0">No payments recorded.</div>
                <?php else: ?>
                <?php foreach($inv->payments as $p): ?>
                <div class="tl-item">
                    <div class="tl-dot" style="background:#16a34a"></div>
                    <div class="tl-body">
                        <strong>₹<?= number_format($p->amount,2) ?></strong> received
                        <div class="tl-time"><?= date('d M Y',strtotime($p->payment_date)) ?> ·
                            <?= $p->payment_method ?><?= $p->transaction_ref?' · '.$p->transaction_ref:'' ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php if(in_array($inv->status,['unpaid','partial','overdue','sent'])): ?>
                <a href="<?= site_url('client_billing/Client_billing/record_payment?invoice_id='.$inv->id) ?>"
                    class="btn btn-success btn-block btn-sm" style="margin-top:10px"><i class="fa fa-plus"></i> Record
                    Payment</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bil-card">
            <div class="bil-head"><i class="fa fa-clock-o"></i> Activity</div>
            <div class="bil-body">
                <?php foreach($inv->logs as $log): ?>
                <div class="tl-item">
                    <div class="tl-dot"></div>
                    <div class="tl-body">
                        <?= htmlspecialchars($log->remarks??'') ?>
                        <?php if($log->old_status&&$log->new_status): ?>
                        <span
                            style="background:#f1f5f9;padding:1px 7px;border-radius:10px;font-size:10.5px;margin-left:4px"><?= $log->old_status ?>
                            → <?= $log->new_status ?></span>
                        <?php endif; ?>
                        <div class="tl-time"><?= date('d M Y H:i',strtotime($log->logged_at)) ?> · <?= $log->by_user ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div><!-- /col-md-4 -->
</div>