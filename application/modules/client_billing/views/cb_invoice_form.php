<?php defined('BASEPATH') OR exit('No direct script access allowed');
$inv     = $invoice;
$is_edit = !empty($inv);
$svc_json = json_encode(array_map(function($s){
    return array('id'=>$s->id,'name'=>$s->name,'hsn_sac'=>$s->hsn_sac,'unit'=>$s->unit,'rate'=>(float)$s->default_rate,'cgst_rate'=>(float)$s->cgst_rate,'sgst_rate'=>(float)$s->sgst_rate,'igst_rate'=>(float)$s->igst_rate);
}, (array)$services));
?>
<?php
echo "<h2>BEFORE VIEW</h2>";
$this->load->view($module.'/'.$page);
echo "<h2>AFTER VIEW</h2>";
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

.bil-body {
    padding: 20px;
}

.lbl {
    display: block;
    font-size: 11.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
}

.inp {
    width: 100%;
    border: 1.5px solid var(--bd);
    border-radius: 7px;
    padding: 8px 12px;
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

.r3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}

.r2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}

/* Items Table */
.it {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.it thead th {
    background: var(--p);
    color: #fff;
    padding: 8px 7px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
    white-space: nowrap;
}

.it tbody td {
    padding: 5px 5px;
    border-bottom: 1px solid #f1f5f9;
}

.ii {
    border: 1.5px solid var(--bd);
    border-radius: 5px;
    padding: 5px 7px;
    font-size: 12.5px;
    width: 100%;
    background: #fff;
}

.ii:focus {
    border-color: var(--p);
    outline: none;
}

.ii.ro {
    background: #f8faff;
    font-weight: 700;
    text-align: right;
}

.svc-sel {
    border: 1.5px solid #c7d2fe;
    border-radius: 5px;
    padding: 4px 6px;
    font-size: 11.5px;
    background: #f0f4ff;
    color: var(--p);
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    margin-bottom: 4px;
}

/* Summary */
.sum-panel {
    background: linear-gradient(135deg, #fafbff, #f0f4ff);
    border: 1.5px solid #c7d2fe;
    border-radius: 10px;
    padding: 16px;
}

.sum-row {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
    font-size: 13px;
}

.sum-row.grand {
    font-size: 16px;
    font-weight: 800;
    color: var(--p);
    border-top: 2px solid var(--p);
    padding-top: 10px;
    margin-top: 6px;
}

.amt-words {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 7px;
    padding: 9px 12px;
    font-size: 12px;
    color: #166534;
    font-style: italic;
    margin-top: 10px;
}

.sticky-side {
    position: sticky;
    top: 68px;
}
</style>

<div style="display:flex;gap:10px;margin-bottom:18px;align-items:center">
    <a href="<?= site_url('client_billing/Client_billing/invoices') ?>" class="btn btn-default btn-sm"><i
            class="fa fa-arrow-left"></i> Invoices</a>
    <h4 style="margin:0;font-weight:700;color:var(--p)">
        <?= $is_edit ? 'Edit Invoice #'.$inv->invoice_number : 'Create New Invoice' ?></h4>
</div>

<form id="cbForm" action="<?= site_url('client_billing/Client_billing/save_invoice') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="invoice_id" value="<?= $inv->id ?? '' ?>">

    <div class="row">
        <div class="col-md-8">

            <!-- Invoice Details -->
            <div class="bil-card">
                <div class="bil-head"><span><i class="fa fa-file-text-o"></i> Invoice Details</span></div>
                <div class="bil-body">
                    <div class="r3">
                        <div>
                            <label class="lbl">Invoice Type</label>
                            <select name="invoice_type" class="inp">
                                <?php foreach(array('tax_invoice'=>'Tax Invoice','proforma'=>'Proforma Invoice','credit_note'=>'Credit Note','debit_note'=>'Debit Note') as $v=>$l): ?>
                                <option value="<?=$v?>" <?=($inv->invoice_type??'tax_invoice')===$v?'selected':''?>>
                                    <?=$l?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="lbl">Invoice Number</label>
                            <input type="text" name="invoice_number"
                                value="<?= htmlspecialchars($inv->invoice_number ?? $next_number) ?>" class="inp"
                                required>
                        </div>
                        <div>
                            <label class="lbl">Status</label>
                            <select name="status" class="inp">
                                <?php foreach(array('draft'=>'Draft','sent'=>'Sent','unpaid'=>'Unpaid','paid'=>'Paid','cancelled'=>'Cancelled') as $v=>$l): ?>
                                <option value="<?=$v?>" <?=($inv->status??'draft')===$v?'selected':''?>><?=$l?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="r3">
                        <div>
                            <label class="lbl">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoiceDate"
                                value="<?= $inv->invoice_date ?? date('Y-m-d') ?>" class="inp" required>
                        </div>
                        <div>
                            <label class="lbl">Due Date</label>
                            <input type="date" name="due_date" id="dueDate" value="<?= $inv->due_date ?? '' ?>"
                                class="inp">
                        </div>
                        <div>
                            <label class="lbl">Payment Terms</label>
                            <select name="payment_terms" class="inp" id="payTerms">
                                <?php foreach(array('Net 7 Days','Net 15 Days','Net 30 Days','Net 45 Days','Net 60 Days','Due on Receipt') as $t): ?>
                                <option value="<?=$t?>" <?=($inv->payment_terms??'Net 30 Days')===$t?'selected':''?>>
                                    <?=$t?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="r3">
                        <div><label class="lbl">Reference No.</label><input type="text" name="reference_number"
                                value="<?= htmlspecialchars($inv->reference_number??'') ?>" class="inp"
                                placeholder="REF-XXXX"></div>
                        <div><label class="lbl">PO Number</label><input type="text" name="po_number"
                                value="<?= htmlspecialchars($inv->po_number??'') ?>" class="inp" placeholder="PO-XXXX">
                        </div>
                        <div>
                            <label class="lbl">Mode of Payment</label>
                            <select name="mode_of_payment" class="inp">
                                <?php foreach(array('Bank Transfer','NEFT','RTGS','UPI','Cash','Cheque','Card') as $m): ?>
                                <option value="<?=$m?>"
                                    <?=($inv->mode_of_payment??'Bank Transfer')===$m?'selected':''?>><?=$m?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="r3">
                        <div><label class="lbl">Dispatch Through</label><input type="text" name="dispatch_through"
                                value="<?= htmlspecialchars($inv->dispatch_through??'') ?>" class="inp"
                                placeholder="BlueDart"></div>
                        <div><label class="lbl">Destination</label><input type="text" name="destination"
                                value="<?= htmlspecialchars($inv->destination??'') ?>" class="inp"></div>
                        <div><label class="lbl">Delivery Note</label><input type="text" name="delivery_note"
                                value="<?= htmlspecialchars($inv->delivery_note??'') ?>" class="inp"></div>
                    </div>
                </div>
            </div>

            <!-- Client -->
            <div class="bil-card">
                <div class="bil-head"><i class="fa fa-building-o"></i> Client (Bill To)</div>
                <div class="bil-body">
                    <div class="r2">
                        <div>
                            <label class="lbl">Select Client <span style="color:#dc2626">*</span></label>
                            <select name="client_id" class="inp" id="clientSelect" required>
                                <option value="">-- Select Client --</option>
                                <?php foreach($clients as $c): ?>
                                <option value="<?=$c->id?>"
                                    <?=($inv->client_id??$preload_client)==$c->id?'selected':''?>>
                                    <?= htmlspecialchars($c->company_name) ?><?= $c->contact_person?' ('.$c->contact_person.')':'' ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <a href="<?= site_url('client_billing/Client_billing/add_client') ?>" target="_blank"
                                style="font-size:11.5px;margin-top:5px;display:inline-block;color:var(--p)"><i
                                    class="fa fa-plus-circle"></i> Add New Client</a>
                        </div>
                        <div>
                            <label class="lbl">Place of Supply</label>
                            <input type="text" name="place_of_supply" id="placeOfSupply"
                                value="<?= htmlspecialchars($inv->place_of_supply??'') ?>" class="inp"
                                placeholder="State name">
                        </div>
                    </div>
                    <!-- Client Preview -->
                    <div id="clientPreview" style="display:none;margin-top:12px">
                        <div class="row">
                            <div class="col-sm-6">
                                <div
                                    style="background:#f8fafc;border-radius:8px;padding:12px;border:1px solid var(--bd)">
                                    <div
                                        style="font-size:10px;font-weight:800;text-transform:uppercase;color:#94a3b8;margin-bottom:6px">
                                        Bill To</div>
                                    <div id="billToText" style="font-size:12.5px;line-height:1.7;color:#374151"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div
                                    style="background:#f8fafc;border-radius:8px;padding:12px;border:1px solid var(--bd)">
                                    <div
                                        style="font-size:10px;font-weight:800;text-transform:uppercase;color:#94a3b8;margin-bottom:6px">
                                        Ship To</div>
                                    <div id="shipToText" style="font-size:12.5px;line-height:1.7;color:#374151"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="bil-card">
                <div class="bil-head">
                    <span><i class="fa fa-list-ul"></i> Services / Items</span>
                    <label
                        style="font-size:12px;display:flex;align-items:center;gap:7px;cursor:pointer;font-weight:600">
                        <input type="checkbox" id="isIgst" name="is_igst" value="1"
                            <?= !empty($inv->is_igst)?'checked':'' ?>> IGST (Inter-State)
                    </label>
                </div>
                <div style="overflow-x:auto">
                    <table class="it" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width:26px">#</th>
                                <th style="min-width:200px">Description / Service</th>
                                <th style="width:75px">HSN/SAC</th>
                                <th style="width:60px">Qty</th>
                                <th style="width:55px">Unit</th>
                                <th style="width:90px">Rate (Rs.)</th>
                                <th style="width:75px">Disc</th>
                                <th style="width:55px">D.Type</th>
                                <th class="cgst-col" style="width:60px">CGST%</th>
                                <th class="sgst-col" style="width:60px">SGST%</th>
                                <th class="igst-col" style="width:60px;display:none">IGST%</th>
                                <th style="width:95px">Taxable</th>
                                <th style="width:95px">Total</th>
                                <th style="width:32px"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>
                    <div style="padding:10px 12px">
                        <button type="button" onclick="CB.addRow()"
                            style="display:inline-flex;align-items:center;gap:6px;background:var(--p);color:#fff;border:none;border-radius:7px;padding:7px 16px;font-size:12.5px;font-weight:700;cursor:pointer">
                            <i class="fa fa-plus"></i> Add Service
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bil-card">
                <div class="bil-head"><i class="fa fa-sticky-note-o"></i> Notes &amp; Terms</div>
                <div class="bil-body">
                    <div class="r3">
                        <div><label class="lbl">Notes to Client</label><textarea name="notes" class="inp" rows="3"
                                placeholder="Thank you for your business!"><?= htmlspecialchars($inv->notes??'') ?></textarea>
                        </div>
                        <div><label class="lbl">Terms &amp; Conditions</label><textarea name="terms_conditions"
                                class="inp" rows="3"><?= htmlspecialchars($inv->terms_conditions??'') ?></textarea>
                        </div>
                        <div><label class="lbl">Internal Remarks</label><textarea name="internal_notes" class="inp"
                                rows="3"
                                placeholder="Not printed on invoice"><?= htmlspecialchars($inv->internal_notes??'') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /col-md-8 -->

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="sticky-side">

                <!-- Bank -->
                <div class="bil-card">
                    <div class="bil-head"><i class="fa fa-university"></i> Bank Account</div>
                    <div class="bil-body">
                        <select name="bank_account_id" class="inp">
                            <option value="">-- Default Bank --</option>
                            <?php foreach($banks as $b): ?>
                            <option value="<?=$b->id?>" <?=($inv->bank_account_id??'')==$b->id?'selected':''?>>
                                <?= htmlspecialchars($b->bank_name.' -- '.$b->account_number) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bil-card">
                    <div class="bil-head"><i class="fa fa-calculator"></i> Invoice Summary</div>
                    <div class="bil-body">
                        <div class="sum-panel">
                            <div class="sum-row"><span style="color:#64748b">Subtotal</span><span id="ss">Rs.0.00</span>
                            </div>
                            <div class="sum-row"><span style="color:#64748b">Discount</span><span id="sd"
                                    style="color:#dc2626">-Rs.0.00</span></div>
                            <div class="sum-row"><span style="color:#64748b">Taxable Amount</span><span
                                    id="st">Rs.0.00</span></div>
                            <div class="sum-row cgst-row"><span style="color:#64748b">CGST</span><span
                                    id="sc">Rs.0.00</span></div>
                            <div class="sum-row sgst-row"><span style="color:#64748b">SGST</span><span
                                    id="sg">Rs.0.00</span></div>
                            <div class="sum-row igst-row" style="display:none"><span
                                    style="color:#64748b">IGST</span><span id="si">Rs.0.00</span></div>
                            <div class="sum-row" style="border-top:1px solid #c7d2fe;padding-top:8px;margin-top:6px">
                                <span style="color:#64748b">Round Off</span>
                                <input type="number" name="round_off" id="roundOff" step="0.01"
                                    value="<?= $inv->round_off??0 ?>"
                                    style="width:80px;border:1.5px solid var(--bd);border-radius:5px;padding:4px 8px;text-align:right;font-size:13px">
                            </div>
                            <div class="sum-row grand"><span>Grand Total</span><span id="sg2">Rs.0.00</span></div>
                        </div>
                        <div class="amt-words" id="amtWords">Zero Rupees Only</div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="subtotal" id="h_s">
                        <input type="hidden" name="total_discount" id="h_d">
                        <input type="hidden" name="taxable_amount" id="h_t">
                        <input type="hidden" name="cgst_amount" id="h_c">
                        <input type="hidden" name="sgst_amount" id="h_sg">
                        <input type="hidden" name="igst_amount" id="h_i">
                        <input type="hidden" name="total_tax" id="h_tx">
                        <input type="hidden" name="grand_total" id="h_g">

                        <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
                            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-paper-plane"></i>
                                Save Invoice</button>
                            <button type="button" class="btn btn-default btn-block"
                                onclick="document.querySelector('[name=status]').value='draft';document.getElementById('cbForm').submit()"><i
                                    class="fa fa-save"></i> Save as Draft</button>
                            <?php if($is_edit): ?>
                            <a href="<?= site_url('client_billing/Client_billing/print_invoice/'.$inv->id) ?>"
                                target="_blank" class="btn btn-info btn-block"><i class="fa fa-print"></i> Print /
                                PDF</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /col-md-4 -->
    </div><!-- /row -->
</form>

<script>
var CB_SVC = <?= $svc_json ?>;
var CB_IGST = <?= !empty($inv->is_igst)?'true':'false' ?>;
var CB_EXIST = <?= json_encode(array_map(function($it){ return (array)$it; }, (array)($items??array()))) ?>;
var CB_SITE = '<?= site_url() ?>';
var UNITS = ['Nos', 'Kg', 'Ltr', 'Mtr', 'Hrs', 'Days', 'Month', 'Year', 'Pcs', 'Set', 'Project', 'License'];
var rowN = 0;

window.CB = {
    addRow: function(d) {
        rowN++;
        d = d || {};
        var tb = document.getElementById('itemsBody');
        var tr = document.createElement('tr');
        tr.id = 'r' + rowN;

        var svcOpts = '<option value="">Pick service...</option>' +
            CB_SVC.map(function(s) {
                return '<option value="' + s.id + '" data-hsn="' + s.hsn_sac + '" data-unit="' + s.unit +
                    '" data-rate="' + s.rate + '" data-cr="' + s.cgst_rate + '" data-sr="' + s.sgst_rate +
                    '" data-ir="' + s.igst_rate + '">' + s.name + '</option>';
            }).join('');

        var uOpts = UNITS.map(function(u) {
            return '<option' + (d.unit === u ? ' selected' : '') + '>' + u + '</option>';
        }).join('');
        var dtOpts = [
            ['flat', 'Flat'],
            ['percent', '%']
        ].map(function(x) {
            return '<option value="' + x[0] + '"' + (d.discount_type === x[0] ? ' selected' : '') +
                '>' + x[1] + '</option>';
        }).join('');
        var n = rowN;

        tr.innerHTML =
            '<td style="text-align:center;color:#94a3b8;font-weight:700;vertical-align:middle">' + n + '</td>' +
            '<td style="min-width:200px">' +
            '<select class="svc-sel" onchange="CB.fill(this,\'r' + n + '\')">' + svcOpts + '</select>' +
            '<input type="text" name="item_description[]" class="ii ii-desc" placeholder="Service description" value="' +
            _e(d.item_description || '') + '" required>' +
            '<input type="hidden" name="service_id[]" value="' + (d.service_id || '') + '">' +
            '</td>' +
            '<td><input type="text" name="hsn_sac_code[]" class="ii ii-hsn" value="' + _e(d.hsn_sac_code ||
                '') + '" placeholder="998314" style="width:72px"></td>' +
            '<td><input type="number" name="quantity[]" class="ii ii-qty" value="' + (d.quantity || 1) +
            '" min="0" step="0.001" style="width:58px"></td>' +
            '<td><select name="unit[]" class="ii ii-unit" style="width:52px">' + uOpts + '</select></td>' +
            '<td><input type="number" name="rate[]" class="ii ii-rate" value="' + (d.rate || '') +
            '" min="0" step="0.01" placeholder="0.00" style="width:88px"></td>' +
            '<td><input type="number" name="discount[]" class="ii ii-disc" value="' + (d.discount || 0) +
            '" min="0" step="0.01" style="width:72px"></td>' +
            '<td><select name="discount_type[]" class="ii ii-dtype" style="width:50px">' + dtOpts +
            '</select></td>' +
            '<td class="cgst-col"><input type="number" name="cgst_rate[]" class="ii ii-cr" value="' + (d
                .cgst_rate !== undefined ? d.cgst_rate : (CB_IGST ? 0 : 9)) +
            '" min="0" step="0.01" style="width:58px"></td>' +
            '<td class="sgst-col"><input type="number" name="sgst_rate[]" class="ii ii-sr" value="' + (d
                .sgst_rate !== undefined ? d.sgst_rate : (CB_IGST ? 0 : 9)) +
            '" min="0" step="0.01" style="width:58px"></td>' +
            '<td class="igst-col" style="display:' + (CB_IGST ? 'table-cell' : 'none') +
            '"><input type="number" name="igst_rate[]" class="ii ii-ir" value="' + (d.igst_rate !== undefined ?
                d.igst_rate : (CB_IGST ? 18 : 0)) + '" min="0" step="0.01" style="width:58px"></td>' +
            '<td><input type="text" class="ii ro ii-tax" readonly value="0.00" style="width:88px"></td>' +
            '<td><input type="text" class="ii ro ii-tot" readonly value="0.00" style="width:88px"></td>' +
            '<td><button type="button" onclick="CB.del(this)" style="background:#fee2e2;color:#dc2626;border:none;border-radius:5px;padding:4px 7px;cursor:pointer"><i class="fa fa-times"></i></button></td>';

        tb.appendChild(tr);
        tr.querySelectorAll('input,select').forEach(function(el) {
            el.addEventListener('input', function() {
                CB.calc(tr);
            });
        });
        CB.calc(tr);
    },

    fill: function(sel, rowId) {
        var opt = sel.options[sel.selectedIndex];
        if (!opt.value) return;
        var tr = document.getElementById(rowId);
        tr.querySelector('.ii-hsn').value = opt.dataset.hsn || '';
        tr.querySelector('.ii-rate').value = opt.dataset.rate || '';
        var uSel = tr.querySelector('.ii-unit');
        Array.from(uSel.options).forEach(function(o) {
            o.selected = o.value === opt.dataset.unit;
        });
        tr.querySelector('.ii-cr').value = CB_IGST ? 0 : (opt.dataset.cr || 9);
        tr.querySelector('.ii-sr').value = CB_IGST ? 0 : (opt.dataset.sr || 9);
        tr.querySelector('.ii-ir').value = CB_IGST ? (opt.dataset.ir || 18) : 0;
        var descEl = tr.querySelector('.ii-desc');
        if (!descEl.value) {
            var svc = CB_SVC.find(function(s) {
                return s.id == opt.value;
            });
            if (svc) descEl.value = svc.name;
        }
        CB.calc(tr);
        sel.value = '';
    },

    calc: function(tr) {
        var qty = parseFloat(tr.querySelector('.ii-qty').value) || 0;
        var rate = parseFloat(tr.querySelector('.ii-rate').value) || 0;
        var disc = parseFloat(tr.querySelector('.ii-disc').value) || 0;
        var dtype = tr.querySelector('.ii-dtype').value;
        var cr = parseFloat(tr.querySelector('.ii-cr').value) || 0;
        var sr = parseFloat(tr.querySelector('.ii-sr').value) || 0;
        var ir = parseFloat(tr.querySelector('.ii-ir').value) || 0;
        var gross = qty * rate;
        var dAmt = dtype === 'percent' ? gross * disc / 100 : disc;
        var tax = Math.max(0, gross - dAmt);
        var total = tax * (1 + (cr + sr + ir) / 100);
        tr.querySelector('.ii-tax').value = f2(tax);
        tr.querySelector('.ii-tot').value = f2(total);
        CB.totals();
    },

    totals: function() {
        var sub = 0,
            disc = 0,
            tax = 0,
            cgst = 0,
            sgst = 0,
            igst = 0;
        document.querySelectorAll('#itemsBody tr').forEach(function(tr) {
            var qty = parseFloat(tr.querySelector('.ii-qty') && tr.querySelector('.ii-qty').value) || 0;
            var rate = parseFloat(tr.querySelector('.ii-rate') && tr.querySelector('.ii-rate').value) ||
                0;
            var d = parseFloat(tr.querySelector('.ii-disc') && tr.querySelector('.ii-disc').value) || 0;
            var dt = tr.querySelector('.ii-dtype') && tr.querySelector('.ii-dtype').value;
            var cr = parseFloat(tr.querySelector('.ii-cr') && tr.querySelector('.ii-cr').value) || 0;
            var sr = parseFloat(tr.querySelector('.ii-sr') && tr.querySelector('.ii-sr').value) || 0;
            var ir = parseFloat(tr.querySelector('.ii-ir') && tr.querySelector('.ii-ir').value) || 0;
            var g = qty * rate,
                da = dt === 'percent' ? g * d / 100 : d,
                t = Math.max(0, g - da);
            sub += g;
            disc += da;
            tax += t;
            cgst += t * cr / 100;
            sgst += t * sr / 100;
            igst += t * ir / 100;
        });
        var ro = parseFloat(document.getElementById('roundOff').value) || 0;
        var ttax = cgst + sgst + igst;
        var grand = tax + ttax + ro;
        setText('ss', 'Rs.' + f2(sub));
        setText('sd', '-Rs.' + f2(disc));
        setText('st', 'Rs.' + f2(tax));
        setText('sc', 'Rs.' + f2(cgst));
        setText('sg', 'Rs.' + f2(sgst));
        setText('si', 'Rs.' + f2(igst));
        setText('sg2', 'Rs.' + f2(grand));
        setVal('h_s', f2(sub));
        setVal('h_d', f2(disc));
        setVal('h_t', f2(tax));
        setVal('h_c', f2(cgst));
        setVal('h_sg', f2(sgst));
        setVal('h_i', f2(igst));
        setVal('h_tx', f2(ttax));
        setVal('h_g', f2(grand));
        document.getElementById('amtWords').textContent = amtW(Math.round(grand));
    },

    del: function(btn) {
        if (document.querySelectorAll('#itemsBody tr').length <= 1) {
            alert('At least one item required.');
            return;
        }
        btn.closest('tr').remove();
        CB.totals();
    }
};

// IGST toggle
document.getElementById('isIgst').addEventListener('change', function() {
    CB_IGST = this.checked;
    document.querySelectorAll('.igst-col,.igst-row').forEach(function(el) {
        el.style.display = CB_IGST ? 'table-cell' : 'none';
    });
    document.querySelectorAll('.cgst-col,.sgst-col,.cgst-row,.sgst-row').forEach(function(el) {
        el.style.display = CB_IGST ? 'none' : '';
    });
    document.querySelectorAll('#itemsBody tr').forEach(function(tr) {
        var cr = tr.querySelector('.ii-cr'),
            sr = tr.querySelector('.ii-sr'),
            ir = tr.querySelector('.ii-ir');
        if (cr) cr.value = CB_IGST ? 0 : 9;
        if (sr) sr.value = CB_IGST ? 0 : 9;
        if (ir) ir.value = CB_IGST ? 18 : 0;
        CB.calc(tr);
    });
});

// Payment terms -> auto due date
document.getElementById('payTerms').addEventListener('change', function() {
    var m = {
        'Net 7 Days': 7,
        'Net 15 Days': 15,
        'Net 30 Days': 30,
        'Net 45 Days': 45,
        'Net 60 Days': 60,
        'Due on Receipt': 0
    };
    var dt = document.getElementById('invoiceDate').value;
    if (!dt) return;
    var d = new Date(dt);
    d.setDate(d.getDate() + (m[this.value] || 0));
    document.getElementById('dueDate').value = d.toISOString().split('T')[0];
});
document.getElementById('invoiceDate').addEventListener('change', function() {
    document.getElementById('payTerms').dispatchEvent(new Event('change'));
});
document.getElementById('roundOff').addEventListener('input', CB.totals);

// Client AJAX
document.getElementById('clientSelect').addEventListener('change', function() {
    var id = this.value;
    if (!id) {
        document.getElementById('clientPreview').style.display = 'none';
        return;
    }
    fetch(CB_SITE + 'client_billing/Client_billing/ajax_client/' + id)
        .then(function(r) {
            return r.json();
        })
        .then(function(c) {
            if (!c) return;
            var b = [c.company_name, c.contact_person ? 'Attn: ' + c.contact_person : null, c
                .billing_address, [c.billing_city, c.billing_state, c.billing_pincode].filter(Boolean)
                .join(', '), 'GSTIN: ' + (c.gstin || 'N/A'), c.mobile || c.phone || ''
            ].filter(Boolean);
            var s = [c.company_name, c.contact_person ? 'Attn: ' + c.contact_person : null, c
                .shipping_address || c.billing_address, [(c.shipping_city || c.billing_city), (c
                    .shipping_state || c.billing_state)].filter(Boolean).join(', ')
            ].filter(Boolean);
            document.getElementById('billToText').innerHTML = b.join('<br>');
            document.getElementById('shipToText').innerHTML = s.join('<br>');
            document.getElementById('clientPreview').style.display = 'block';
            if (c.billing_state) document.getElementById('placeOfSupply').value = c.billing_state;
        }).catch(function() {});
});

// Helpers
function f2(n) {
    return parseFloat(n).toFixed(2);
}

function setText(id, t) {
    var e = document.getElementById(id);
    if (e) e.textContent = t;
}

function setVal(id, v) {
    var e = document.getElementById(id);
    if (e) e.value = v;
}

function _e(s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Amount in words
var ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve',
    'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
];
var tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

function w3(n) {
    if (n < 20) return ones[n];
    if (n < 100) return tens[Math.floor(n / 10)] + (n % 10 ? ' ' + ones[n % 10] : '');
    return ones[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + w3(n % 100) : '');
}

function amtW(n) {
    if (!n) return 'Zero Rupees Only';
    var r = '';
    if (n >= 10000000) {
        r += w3(Math.floor(n / 10000000)) + ' Crore ';
        n %= 10000000;
    }
    if (n >= 100000) {
        r += w3(Math.floor(n / 100000)) + ' Lakh ';
        n %= 100000;
    }
    if (n >= 1000) {
        r += w3(Math.floor(n / 1000)) + ' Thousand ';
        n %= 1000;
    }
    if (n > 0) r += w3(n);
    return r.trim() + ' Rupees Only';
}

// Init on load
window.addEventListener('DOMContentLoaded', function() {
    if (CB_EXIST.length) {
        CB_EXIST.forEach(function(d) {
            CB.addRow(d);
        });
    } else {
        CB.addRow();
    }
    if (CB_IGST) document.getElementById('isIgst').dispatchEvent(new Event('change'));
    if (document.getElementById('clientSelect').value) document.getElementById('clientSelect').dispatchEvent(
        new Event('change'));
});
</script>