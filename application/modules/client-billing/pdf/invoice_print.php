<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice <?= $invoice->invoice_number ?></title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11.5px;
        color: #1f2937;
        background: #eef2f9;
        padding: 20px;
    }

    .page {
        width: 210mm;
        min-height: 297mm;
        background: #fff;
        margin: auto;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
        position: relative;
    }

    .top-bar {
        height: 8px;
        background: linear-gradient(90deg, #0b0895, #e0802d);
    }

    .bot-bar {
        height: 8px;
        background: linear-gradient(90deg, #e0802d, #0b0895);
    }

    .wrap {
        padding: 10mm 12mm;
    }

    /* HEADER */
    .hdr {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .logo-area {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .logo-box {
        width: 72px;
        height: 72px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0b0895, #1e40af);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 26px;
        font-weight: 900;
        flex-shrink: 0;
    }

    .co-name {
        font-size: 19px;
        font-weight: 900;
        color: #0b0895;
        letter-spacing: .4px;
        margin-bottom: 5px;
    }

    .co-meta {
        font-size: 10.5px;
        color: #6b7280;
        line-height: 1.75;
    }

    .co-meta span {
        display: flex;
        gap: 5px;
        align-items: baseline;
    }

    /* Invoice Meta Box */
    .inv-meta-box {
        min-width: 195px;
    }

    .inv-type-bar {
        background: linear-gradient(135deg, #0b0895, #1e40af);
        color: #fff;
        text-align: center;
        padding: 7px 14px;
        border-radius: 8px 8px 0 0;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    .inv-meta-tbl {
        border: 1.5px solid #d7dceb;
        border-top: none;
        border-radius: 0 0 8px 8px;
        overflow: hidden;
    }

    .inv-meta-tbl table {
        width: 100%;
        border-collapse: collapse;
    }

    .inv-meta-tbl td {
        padding: 5px 9px;
        border-bottom: 1px solid #eef2f9;
        font-size: 10.5px;
    }

    .inv-meta-tbl td:first-child {
        color: #6b7280;
        font-weight: 600;
        width: 44%;
    }

    .inv-meta-tbl td:last-child {
        font-weight: 700;
        color: #1f2937;
    }

    .inv-meta-tbl tr:last-child td {
        border-bottom: none;
    }

    .st-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 9.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .st-paid {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .st-unpaid {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .st-overdue {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .st-draft {
        background: #f1f5f9;
        color: #64748b;
    }

    .st-sent {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Section box */
    .sec {
        border: 1.5px solid #d7dceb;
        border-radius: 8px;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .sec-head {
        background: linear-gradient(135deg, #0b0895, #e0802d);
        color: #fff;
        padding: 6px 11px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .sec-body {
        padding: 9px 11px;
    }

    /* Bill To / Ship To */
    .addr-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .addr-co {
        font-size: 13px;
        font-weight: 800;
        color: #0b0895;
        margin-bottom: 3px;
    }

    .addr-lbl {
        font-size: 9px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 4px;
    }

    .addr-txt {
        font-size: 10.5px;
        color: #374151;
        line-height: 1.7;
    }

    /* Shipping info row */
    .ship-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 4px;
    }

    .ship-cell {
        border: 1px solid #d7dceb;
        border-radius: 5px;
        padding: 5px 7px;
        text-align: center;
    }

    .ship-label {
        font-size: 8.5px;
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
    }

    .ship-val {
        font-size: 10.5px;
        font-weight: 700;
        color: #1f2937;
        margin-top: 2px;
    }

    /* Items table */
    .it {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .it thead tr {
        background: #0b0895;
    }

    .it thead th {
        color: #fff;
        padding: 7px 8px;
        text-align: left;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .it thead th.r {
        text-align: right;
    }

    .it thead th.c {
        text-align: center;
    }

    .it tbody td {
        padding: 6.5px 8px;
        border-bottom: 1px solid #f0f4ff;
    }

    .it tbody tr:nth-child(even) td {
        background: #fafbff;
    }

    .it tfoot td {
        padding: 6px 8px;
        font-weight: 700;
        border-top: 1.5px solid #d7dceb;
    }

    .r {
        text-align: right;
    }

    .c {
        text-align: center;
    }

    /* Bottom 2-col */
    .bot-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 8px;
    }

    /* GST summary */
    .gst-tbl {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .gst-tbl tr td {
        padding: 4px 0;
    }

    .gst-tbl tr td:last-child {
        text-align: right;
        font-weight: 600;
    }

    .gst-tbl .grand td {
        font-size: 14px;
        font-weight: 900;
        color: #0b0895;
        border-top: 2px solid #0b0895;
        padding-top: 8px;
    }

    .gst-tbl .div td {
        border-top: 1px solid #d7dceb;
        padding-top: 6px;
    }

    /* Bank */
    .bank-tbl {
        width: 100%;
        font-size: 10.5px;
        border-collapse: collapse;
    }

    .bank-tbl td {
        padding: 3px 0;
    }

    .bank-tbl td:first-child {
        color: #94a3b8;
        font-weight: 600;
        width: 36%;
    }

    .bank-tbl td:last-child {
        font-weight: 700;
    }

    /* Amount in words */
    .amt-box {
        background: linear-gradient(135deg, #f0f4ff, #fefce8);
        border: 1.5px solid #d7dceb;
        border-radius: 8px;
        padding: 8px 11px;
        margin: 8px 0;
    }

    .amt-lbl {
        font-size: 9px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .amt-text {
        font-size: 11.5px;
        font-weight: 700;
        color: #0b0895;
        font-style: italic;
    }

    /* Footer row */
    .foot-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 8px;
    }

    .terms-list {
        font-size: 10px;
        color: #6b7280;
        line-height: 1.8;
        padding-left: 0;
        list-style: none;
    }

    .terms-list li::before {
        content: '✓ ';
    }

    .sig-area {
        text-align: center;
    }

    .sig-line {
        border-bottom: 1.5px dashed #d7dceb;
        height: 44px;
        width: 140px;
        margin: 0 auto 6px;
    }

    .page-footer {
        background: #f8fafc;
        border-top: 1px solid #d7dceb;
        padding: 7px 12mm;
        display: flex;
        justify-content: space-between;
        font-size: 9.5px;
        color: #94a3b8;
    }

    @media print {
        body {
            background: none;
            padding: 0;
        }

        .page {
            box-shadow: none;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        @page {
            size: A4;
            margin: 0;
        }
    }
    </style>
</head>

<body>

    <!-- Print Button -->
    <div class="no-print" style="max-width:230mm;margin:0 auto 14px;display:flex;gap:10px;justify-content:flex-end">
        <button onclick="window.print()"
            style="background:#0b0895;color:#fff;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:700;cursor:pointer">🖨️
            Print / Save PDF</button>
        <a href="<?= site_url('client_billing/client_billing/view_invoice/'.$invoice->id) ?>"
            style="background:#f1f5f9;color:#1e293b;border:none;border-radius:8px;padding:9px 22px;font-size:13px;font-weight:600;text-decoration:none">←
            Back</a>
    </div>

    <?php
$inv  = $invoice;
$c    = $invoice->client;
$co   = $invoice->company;
$bank = $invoice->bank;
$type_label = ['tax_invoice'=>'TAX INVOICE','proforma'=>'PROFORMA INVOICE','credit_note'=>'CREDIT NOTE','debit_note'=>'DEBIT NOTE'][$inv->invoice_type] ?? 'TAX INVOICE';
$sc   = ['paid'=>'st-paid','unpaid'=>'st-unpaid','overdue'=>'st-overdue','draft'=>'st-draft','sent'=>'st-sent','partial'=>'st-sent'][$inv->status] ?? 'st-draft';
?>

    <div class="page">
        <div class="top-bar"></div>
        <div class="wrap">

            <!-- HEADER -->
            <div class="hdr">
                <div class="logo-area">
                    <?php if($co->logo): ?><img src="<?=$co->logo?>"
                        style="width:72px;height:72px;object-fit:contain;border-radius:10px"><?php else: ?>
                    <div class="logo-box"><?= strtoupper(substr($co->name??'C',0,1)) ?></div><?php endif; ?>
                    <div>
                        <div class="co-name"><?= htmlspecialchars($co->name??'') ?></div>
                        <div class="co-meta">
                            <?php if($co->address): ?><span>📍
                                <?= htmlspecialchars($co->address.', '.($co->city??'').', '.($co->state??'').' - '.($co->pincode??'')) ?></span><?php endif; ?>
                            <?php if($co->gstin): ?><span>🏛 GSTIN:
                                <strong><?= $co->gstin ?></strong></span><?php endif; ?>
                            <?php if($co->phone): ?><span>📞 <?= $co->phone ?></span><?php endif; ?>
                            <?php if($co->email): ?><span>✉ <?= $co->email ?></span><?php endif; ?>
                            <?php if($co->website): ?><span>🌐 <?= $co->website ?></span><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="inv-meta-box">
                    <div class="inv-type-bar"><?= $type_label ?></div>
                    <div class="inv-meta-tbl">
                        <table>
                            <tr>
                                <td>Invoice No.</td>
                                <td style="font-family:monospace"><?= $inv->invoice_number ?></td>
                            </tr>
                            <tr>
                                <td>Invoice Date</td>
                                <td><?= date('d-M-Y',strtotime($inv->invoice_date)) ?></td>
                            </tr>
                            <?php if($inv->due_date): ?><tr>
                                <td>Due Date</td>
                                <td><?= date('d-M-Y',strtotime($inv->due_date)) ?></td>
                            </tr><?php endif; ?>
                            <tr>
                                <td>Payment Terms</td>
                                <td><?= $inv->payment_terms ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td><span class="st-badge <?=$sc?>"><?= strtoupper($inv->status) ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BILL TO / SHIP TO -->
            <div class="sec">
                <div class="addr-grid" style="padding:9px 11px">
                    <div>
                        <div class="addr-lbl">Bill To</div>
                        <div class="addr-co"><?= htmlspecialchars($c->company_name??'') ?></div>
                        <div class="addr-txt">
                            <?= $c->contact_person ? 'Attn: '.htmlspecialchars($c->contact_person).'<br>' : '' ?>
                            <?= $c->billing_address ? nl2br(htmlspecialchars($c->billing_address)).'<br>' : '' ?>
                            <?= implode(', ', array_filter([$c->billing_city??null,$c->billing_state??null,$c->billing_pincode??null])) ?>
                            <?= $c->gstin ? '<br>GSTIN: <strong>'.$c->gstin.'</strong>' : '' ?>
                            <?= ($c->mobile||$c->phone) ? '<br>📞 '.($c->mobile?:$c->phone) : '' ?>
                            <?= $c->email ? '<br>✉ '.htmlspecialchars($c->email) : '' ?>
                        </div>
                    </div>
                    <div style="border-left:1px dashed #d7dceb;padding-left:11px">
                        <div class="addr-lbl">Ship To</div>
                        <div class="addr-co"><?= htmlspecialchars($c->company_name??'') ?></div>
                        <div class="addr-txt">
                            <?= $c->contact_person ? 'Attn: '.htmlspecialchars($c->contact_person).'<br>' : '' ?>
                            <?= ($c->shipping_address||$c->billing_address) ? nl2br(htmlspecialchars($c->shipping_address?:$c->billing_address)).'<br>' : '' ?>
                            <?= implode(', ', array_filter([($c->shipping_city??null)?:($c->billing_city??null),($c->shipping_state??null)?:($c->billing_state??null),($c->shipping_pincode??null)?:($c->billing_pincode??null)])) ?>
                            <?= $c->gstin ? '<br>GSTIN: <strong>'.$c->gstin.'</strong>' : '' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHIPPING INFO ROW -->
            <div class="sec" style="margin-bottom:8px">
                <div class="ship-grid" style="padding:7px 10px">
                    <div class="ship-cell">
                        <div class="ship-label">Mode / Terms of Payment</div>
                        <div class="ship-val"><?= $inv->mode_of_payment ?: '—' ?></div>
                    </div>
                    <div class="ship-cell">
                        <div class="ship-label">Reference Number</div>
                        <div class="ship-val"><?= $inv->reference_number ?: '—' ?></div>
                    </div>
                    <div class="ship-cell">
                        <div class="ship-label">Buyer Order Number</div>
                        <div class="ship-val"><?= $inv->po_number ?: '—' ?></div>
                    </div>
                    <div class="ship-cell">
                        <div class="ship-label">Dispatch Through</div>
                        <div class="ship-val"><?= $inv->dispatch_through ?: '—' ?></div>
                    </div>
                    <div class="ship-cell">
                        <div class="ship-label">Destination</div>
                        <div class="ship-val"><?= $inv->destination ?: '—' ?></div>
                    </div>
                    <div class="ship-cell">
                        <div class="ship-label">Delivery Note</div>
                        <div class="ship-val" style="font-size:9.5px"><?= $inv->delivery_note ?: '—' ?></div>
                    </div>
                </div>
            </div>

            <!-- ITEMS TABLE -->
            <div class="sec">
                <table class="it">
                    <thead>
                        <tr>
                            <th class="c" style="width:28px">Sl No.</th>
                            <th>Item Description</th>
                            <th class="c" style="width:68px">HSN/SAC Code</th>
                            <th class="c" style="width:44px">Quantity</th>
                            <th class="c" style="width:42px">Unit</th>
                            <th class="r" style="width:80px">Rate (₹)</th>
                            <th class="r" style="width:75px">Discount (₹)</th>
                            <th class="r" style="width:90px">Taxable Amount (₹)</th>
                            <?php if(!$inv->is_igst): ?>
                            <th class="r" style="width:75px">CGST (%)</th>
                            <th class="r" style="width:75px">SGST (%)</th>
                            <?php else: ?>
                            <th class="r" style="width:75px">IGST (%)</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($inv->items as $it): ?>
                        <tr>
                            <td class="c"><?= $it->sl_no ?></td>
                            <td style="font-weight:600"><?= htmlspecialchars($it->item_description) ?></td>
                            <td class="c"><?= $it->hsn_sac_code ?: '—' ?></td>
                            <td class="c"><?= rtrim(rtrim($it->quantity,'0'),'.') ?></td>
                            <td class="c"><?= $it->unit ?></td>
                            <td class="r"><?= number_format($it->rate,2) ?></td>
                            <td class="r" style="color:#dc2626"><?= number_format($it->discount_amount,2) ?></td>
                            <td class="r"><?= number_format($it->taxable_amount,2) ?></td>
                            <?php if(!$inv->is_igst): ?>
                            <td class="r">
                                <?= $it->cgst_rate ?>%<br><small>₹<?= number_format($it->cgst_amount,2) ?></small></td>
                            <td class="r">
                                <?= $it->sgst_rate ?>%<br><small>₹<?= number_format($it->sgst_amount,2) ?></small></td>
                            <?php else: ?>
                            <td class="r">
                                <?= $it->igst_rate ?>%<br><small>₹<?= number_format($it->igst_amount,2) ?></small></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="<?= $inv->is_igst?7:8 ?>"
                                style="text-align:right;color:#6b7280;font-weight:600">Total</td>
                            <td class="r" style="color:#dc2626">₹<?= number_format($inv->total_discount,2) ?></td>
                            <td class="r" style="font-weight:800">₹<?= number_format($inv->taxable_amount,2) ?></td>
                            <?php if(!$inv->is_igst): ?><td></td>
                            <td></td><?php else: ?><td></td><?php endif; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- AMOUNT IN WORDS -->
            <div class="amt-box">
                <div class="amt-lbl">Amount in Words</div>
                <div class="amt-text">Rupees <?= $inv->amount_in_words ?: 'Zero Rupees Only' ?></div>
            </div>

            <!-- BANK + GST SUMMARY -->
            <div class="bot-grid">
                <!-- Bank Details -->
                <?php if($bank): ?>
                <div class="sec">
                    <div class="sec-head">🏦 Bank Details</div>
                    <div class="sec-body">
                        <table class="bank-tbl">
                            <tr>
                                <td>Bank Name</td>
                                <td>: <?= htmlspecialchars($bank->bank_name) ?></td>
                            </tr>
                            <tr>
                                <td>Account Name</td>
                                <td>: <?= htmlspecialchars($bank->account_name) ?></td>
                            </tr>
                            <tr>
                                <td>Account Number</td>
                                <td>: <strong style="font-family:monospace"><?= $bank->account_number ?></strong></td>
                            </tr>
                            <tr>
                                <td>IFSC Code</td>
                                <td>: <?= $bank->ifsc_code ?></td>
                            </tr>
                            <tr>
                                <td>Branch Name</td>
                                <td>: <?= $bank->branch_name ?></td>
                            </tr>
                            <?php if($bank->upi_id): ?><tr>
                                <td>UPI ID</td>
                                <td>: <strong style="color:#0b0895"><?= $bank->upi_id ?></strong></td>
                            </tr><?php endif; ?>
                        </table>
                        <?php if($bank->upi_id): ?>
                        <div style="margin-top:8px;text-align:right">
                            <div
                                style="display:inline-block;background:#f0f4ff;border-radius:8px;padding:6px;text-align:center">
                                <div
                                    style="width:64px;height:64px;background:linear-gradient(135deg,#0b0895,#1e40af);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:8.5px;font-weight:700;text-align:center;line-height:1.3">
                                    QR<br>CODE<br><span style="font-size:7px">(UPI)</span></div>
                                <div style="font-size:8px;font-weight:800;color:#94a3b8;margin-top:4px">SCAN &amp; PAY
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- GST Summary -->
                <div class="sec">
                    <div class="sec-head">📊 GST Summary</div>
                    <div class="sec-body">
                        <table class="gst-tbl">
                            <tr>
                                <td style="font-weight:700;color:#94a3b8">Particulars</td>
                                <td style="text-align:center;color:#94a3b8">Rate (%)</td>
                                <td style="font-weight:700;color:#94a3b8">Amount (₹)</td>
                            </tr>
                            <tr class="div">
                                <td>Taxable Amount</td>
                                <td class="c">—</td>
                                <td>₹<?= number_format($inv->taxable_amount,2) ?></td>
                            </tr>
                            <tr>
                                <td>Total Discount</td>
                                <td class="c">—</td>
                                <td style="color:#dc2626">₹<?= number_format($inv->total_discount,2) ?></td>
                            </tr>
                            <?php if(!$inv->is_igst): ?>
                            <tr>
                                <td>CGST</td>
                                <td class="c">9%</td>
                                <td>₹<?= number_format($inv->cgst_amount,2) ?></td>
                            </tr>
                            <tr>
                                <td>SGST</td>
                                <td class="c">9%</td>
                                <td>₹<?= number_format($inv->sgst_amount,2) ?></td>
                            </tr>
                            <tr>
                                <td>IGST</td>
                                <td class="c">18%</td>
                                <td>₹0.00</td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td>CGST</td>
                                <td class="c">—</td>
                                <td>₹0.00</td>
                            </tr>
                            <tr>
                                <td>SGST</td>
                                <td class="c">—</td>
                                <td>₹0.00</td>
                            </tr>
                            <tr>
                                <td>IGST</td>
                                <td class="c">18%</td>
                                <td>₹<?= number_format($inv->igst_amount,2) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Round Off</td>
                                <td class="c">—</td>
                                <td>₹<?= number_format($inv->round_off,2) ?></td>
                            </tr>
                            <tr class="grand">
                                <td><strong>GRAND TOTAL</strong></td>
                                <td></td>
                                <td><strong>₹ <?= number_format($inv->grand_total,2) ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TERMS + SIGNATORY -->
            <div class="foot-grid" style="margin-top:8px">
                <div class="sec">
                    <div class="sec-head">📋 Terms &amp; Conditions</div>
                    <div class="sec-body">
                        <?php $terms = $inv->terms_conditions ?: ($co->terms ?? ''); ?>
                        <?php if($terms): ?>
                        <ul class="terms-list">
                            <?php foreach(array_filter(explode("\n", $terms)) as $line): ?>
                            <li><?= htmlspecialchars(trim($line)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <ul class="terms-list">
                            <li>Payment due within specified days from invoice date.</li>
                            <li>Subject to Bhubaneswar jurisdiction only.</li>
                            <li>Goods/services once sold will not be refunded.</li>
                            <li>This is a digitally generated invoice and valid without signature.</li>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="sec">
                    <div class="sec-head">✍ Authorized Signatory</div>
                    <div class="sec-body" style="text-align:center;padding-top:8px">
                        <div
                            style="display:flex;align-items:flex-end;justify-content:center;gap:20px;margin-bottom:8px">
                            <div
                                style="width:58px;height:58px;border-radius:50%;border:1.5px dashed #d7dceb;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#94a3b8;text-align:center;line-height:1.3">
                                COMPANY<br>SEAL</div>
                            <div>
                                <?php if($co->signature_image): ?>
                                <img src="<?=$co->signature_image?>" style="max-height:48px;max-width:130px">
                                <?php else: ?>
                                <div class="sig-line"></div>
                                <?php endif; ?>
                                <div style="font-size:10px;color:#94a3b8;margin-top:5px">Authorized Signatory</div>
                                <div style="font-size:11.5px;font-weight:800;color:#0b0895">
                                    <?= htmlspecialchars($co->name??'') ?></div>
                            </div>
                        </div>
                        <?php if($inv->notes): ?>
                        <div
                            style="font-size:10px;color:#6b7280;font-style:italic;border-top:1px dashed #d7dceb;padding-top:6px;margin-top:4px;text-align:left">
                            <?= nl2br(htmlspecialchars($inv->notes)) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div><!-- /wrap -->
        <div class="page-footer">
            <span>🙏 <?= htmlspecialchars($co->footer_note ?? 'Thank you for your business!') ?></span>
            <span><?= $co->website ?? '' ?></span>
        </div>
        <div class="bot-bar"></div>
    </div><!-- /page -->
</body>

</html>