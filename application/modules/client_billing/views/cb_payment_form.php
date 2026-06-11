<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
    max-width: 700px;
    margin: 0 auto;
}

.bil-head {
    background: linear-gradient(135deg, var(--p), #1e40af);
    color: #fff;
    padding: 16px 22px;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 9px;
    border-radius: var(--r) var(--r) 0 0;
}

.bil-body {
    padding: 26px;
}

.lbl {
    display: block;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 5px;
}

.inp {
    width: 100%;
    border: 1.5px solid var(--bd);
    border-radius: 7px;
    padding: 10px 13px;
    font-size: 13px;
    color: #1e293b;
    transition: .15s;
    background: #fff;
}

.inp:focus {
    border-color: var(--p);
    outline: none;
    box-shadow: 0 0 0 3px rgba(11, 8, 149, .08);
}

.fg {
    margin-bottom: 18px;
}

.r2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* Invoice preview box */
.inv-prev {
    background: linear-gradient(135deg, #f0f4ff, #fefce8);
    border: 1.5px solid #c7d2fe;
    border-radius: 9px;
    padding: 14px 16px;
    margin-bottom: 18px;
}

.inv-prev-grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
}

.inv-prev-item .pl {
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.inv-prev-item .pv {
    font-size: 15px;
    font-weight: 800;
    color: var(--p);
}

.inv-prev-item .pv.red {
    color: #dc2626;
}

.inv-prev-item .pv.green {
    color: #16a34a;
}
</style>

<div style="max-width:700px;margin:0 auto">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px">
        <a href="<?= site_url('client_billing/Client_billing/payments') ?>" class="btn btn-default btn-sm">
            <i class="fa fa-arrow-left"></i> Payments
        </a>
    </div>

    <div class="bil-card">
        <div class="bil-head"><i class="fa fa-credit-card"></i> Record Payment</div>
        <div class="bil-body">
            <form action="<?= site_url('client_billing/Client_billing/save_payment') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Invoice Selector -->
                <div class="fg">
                    <label class="lbl">Select Invoice <span style="color:#dc2626">*</span></label>
                    <select name="invoice_id" class="inp" id="invoiceSelect" required onchange="loadInvoice(this)">
                        <option value="">— Select Invoice to Pay —</option>
                        <?php foreach($invoices as $inv): ?>
                        <option value="<?= $inv->id ?>" data-client="<?= $inv->client_id ?>"
                            data-num="<?= htmlspecialchars($inv->invoice_number) ?>"
                            data-grand="<?= $inv->grand_total ?>" data-balance="<?= $inv->balance_due ?>"
                            data-company="<?= htmlspecialchars($inv->company_name??'') ?>"
                            <?= (isset($invoice) && $invoice->id==$inv->id)?'selected':'' ?>>
                            <?= htmlspecialchars($inv->invoice_number) ?> —
                            <?= htmlspecialchars($inv->company_name??'') ?>
                            (₹<?= number_format($inv->balance_due,2) ?> due)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Invoice Preview -->
                <div class="inv-prev" id="invPreview" style="display:none">
                    <div
                        style="font-size:11px;font-weight:800;color:#94a3b8;text-transform:uppercase;margin-bottom:10px">
                        Invoice Summary</div>
                    <div class="inv-prev-grid">
                        <div class="inv-prev-item">
                            <div class="pl">Invoice #</div>
                            <div class="pv" id="prevNum">—</div>
                        </div>
                        <div class="inv-prev-item">
                            <div class="pl">Client</div>
                            <div class="pv" id="prevCompany" style="font-size:13px">—</div>
                        </div>
                        <div class="inv-prev-item">
                            <div class="pl">Grand Total</div>
                            <div class="pv" id="prevGrand">—</div>
                        </div>
                        <div class="inv-prev-item">
                            <div class="pl">Balance Due</div>
                            <div class="pv red" id="prevBalance">—</div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="client_id" id="clientId" value="<?= $invoice->client_id??'' ?>">

                <!-- Amount -->
                <div class="fg">
                    <label class="lbl">Payment Amount (₹) <span style="color:#dc2626">*</span></label>
                    <input type="number" name="amount" id="payAmount" class="inp" step="0.01" min="0.01"
                        placeholder="Enter amount" required value="<?= isset($invoice)?$invoice->balance_due:'' ?>">
                    <div style="font-size:11px;color:#94a3b8;margin-top:3px">
                        Enter the exact amount being paid now (partial payments supported)
                    </div>
                </div>

                <div class="r2">
                    <div class="fg">
                        <label class="lbl">Payment Date <span style="color:#dc2626">*</span></label>
                        <input type="date" name="payment_date" class="inp" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="fg">
                        <label class="lbl">Payment Method</label>
                        <select name="payment_method" class="inp">
                            <?php foreach(['Bank Transfer','NEFT','RTGS','UPI','Cash','Cheque','Card','Online'] as $m): ?>
                            <option value="<?=$m?>"><?=$m?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="r2">
                    <div class="fg">
                        <label class="lbl">Transaction Reference</label>
                        <input type="text" name="transaction_ref" class="inp" placeholder="UTR / Cheque No. / TXN ID">
                    </div>
                    <div class="fg">
                        <label class="lbl">Bank Account</label>
                        <select name="bank_account_id" class="inp">
                            <option value="">— Default —</option>
                            <?php foreach($banks as $b): ?>
                            <option value="<?=$b->id?>"><?= htmlspecialchars($b->bank_name.' — '.$b->account_number) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="fg">
                    <label class="lbl">Notes (Optional)</label>
                    <textarea name="notes" class="inp" rows="2" placeholder="Additional payment remarks..."></textarea>
                </div>

                <div style="display:flex;gap:10px;margin-top:6px">
                    <button type="submit" class="btn btn-success btn-lg" style="flex:1;font-size:14px">
                        <i class="fa fa-check-circle"></i> Record Payment
                    </button>
                    <a href="<?= site_url('client_billing/Client_billing/payments') ?>"
                        class="btn btn-default btn-lg">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', function() {
    var sel = document.getElementById('invoiceSelect');
    if (sel.value) loadInvoice(sel);
});

function loadInvoice(sel) {
    var opt = sel.options[sel.selectedIndex];
    if (!opt.value) {
        document.getElementById('invPreview').style.display = 'none';
        return;
    }
    document.getElementById('clientId').value = opt.dataset.client;
    document.getElementById('prevNum').textContent = opt.dataset.num;
    document.getElementById('prevCompany').textContent = opt.dataset.company;
    document.getElementById('prevGrand').textContent = '₹' + parseFloat(opt.dataset.grand).toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });
    document.getElementById('prevBalance').textContent = '₹' + parseFloat(opt.dataset.balance).toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });
    document.getElementById('payAmount').value = parseFloat(opt.dataset.balance).toFixed(2);
    document.getElementById('payAmount').max = parseFloat(opt.dataset.balance).toFixed(2);
    document.getElementById('invPreview').style.display = 'block';
}
</script>