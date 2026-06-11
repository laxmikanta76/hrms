<?php defined('BASEPATH') OR exit('No direct script access allowed');
$c=$client; $ie=!empty($c);
?>
<?php
die('INVOICE FORM LOADED');?>
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

.bil-body {
    padding: 22px;
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
    padding: 9px 12px;
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

.divider {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 18px 0 12px;
    font-size: 11px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--bd);
}

.gstin-msg {
    font-size: 11px;
    margin-top: 3px;
}
</style>

<div style="display:flex;align-items:center;gap:10px;margin-bottom:18px">
    <a href="<?= site_url('client_billing/client_billing/clients') ?>" class="btn btn-default btn-sm"><i
            class="fa fa-arrow-left"></i> Clients</a>
</div>

<form action="<?= site_url('client_billing/client_billing/save_client') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="client_id" value="<?= $c->id??'' ?>">

    <div class="bil-card">
        <div class="bil-head"><i class="fa fa-building-o"></i> <?= $ie?'Edit Client':'Add New Client' ?></div>
        <div class="bil-body">

            <div class="r3">
                <div><label class="lbl">Company Name <span style="color:#dc2626">*</span></label><input type="text"
                        name="company_name" value="<?= htmlspecialchars($c->company_name??'') ?>" class="inp" required
                        placeholder="XYZ Enterprises Pvt Ltd"></div>
                <div><label class="lbl">Contact Person</label><input type="text" name="contact_person"
                        value="<?= htmlspecialchars($c->contact_person??'') ?>" class="inp" placeholder="Rahul Sharma">
                </div>
                <div><label class="lbl">Client Code</label><input type="text" name="client_code"
                        value="<?= htmlspecialchars($c->client_code??'') ?>" class="inp" placeholder="CUST-001"></div>
            </div>

            <div class="r3">
                <div>
                    <label class="lbl">GSTIN</label>
                    <input type="text" name="gstin" id="gstinInp" value="<?= htmlspecialchars($c->gstin??'') ?>"
                        class="inp" placeholder="27AAACX1234Z1ZV" maxlength="15" oninput="valGstin(this)">
                    <div class="gstin-msg" id="gstinMsg"></div>
                </div>
                <div><label class="lbl">PAN</label><input type="text" name="pan"
                        value="<?= htmlspecialchars($c->pan??'') ?>" class="inp" placeholder="AAAAA0000A"
                        maxlength="10"></div>
                <div><label class="lbl">Payment Terms</label>
                    <select name="payment_terms" class="inp">
                        <?php foreach(['Net 7 Days','Net 15 Days','Net 30 Days','Net 45 Days','Net 60 Days','Due on Receipt'] as $t): ?>
                        <option value="<?=$t?>" <?=($c->payment_terms??'Net 30 Days')===$t?'selected':''?>><?=$t?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="r3">
                <div><label class="lbl">Email</label><input type="email" name="email"
                        value="<?= htmlspecialchars($c->email??'') ?>" class="inp" placeholder="accounts@client.com">
                </div>
                <div><label class="lbl">Mobile</label><input type="text" name="mobile"
                        value="<?= htmlspecialchars($c->mobile??'') ?>" class="inp" placeholder="+91 9988776655"></div>
                <div><label class="lbl">Phone / Landline</label><input type="text" name="phone"
                        value="<?= htmlspecialchars($c->phone??'') ?>" class="inp" placeholder="022-XXXXXXXX"></div>
            </div>

            <div class="r2">
                <div><label class="lbl">Credit Limit (₹)</label><input type="number" name="credit_limit"
                        value="<?= $c->credit_limit??0 ?>" class="inp" min="0" step="0.01"></div>
            </div>

            <div class="divider">Billing Address</div>
            <div style="margin-bottom:14px"><label class="lbl">Address Line</label><textarea name="billing_address"
                    class="inp" rows="2"
                    placeholder="Building, Street, Area"><?= htmlspecialchars($c->billing_address??'') ?></textarea>
            </div>
            <div class="r3">
                <div><label class="lbl">City</label><input type="text" name="billing_city"
                        value="<?= htmlspecialchars($c->billing_city??'') ?>" class="inp"></div>
                <div><label class="lbl">State</label><input type="text" name="billing_state"
                        value="<?= htmlspecialchars($c->billing_state??'') ?>" class="inp"></div>
                <div><label class="lbl">PIN Code</label><input type="text" name="billing_pincode"
                        value="<?= htmlspecialchars($c->billing_pincode??'') ?>" class="inp" maxlength="10"></div>
            </div>

            <div class="divider">Shipping Address</div>
            <button type="button" onclick="copyAddr()"
                style="background:#f0f4ff;border:1px solid #c7d2fe;border-radius:6px;padding:5px 13px;font-size:12px;font-weight:600;color:var(--p);cursor:pointer;margin-bottom:10px"><i
                    class="fa fa-copy"></i> Same as Billing</button>
            <div style="margin-bottom:14px"><label class="lbl">Address Line</label><textarea name="shipping_address"
                    id="sAddr" class="inp" rows="2"><?= htmlspecialchars($c->shipping_address??'') ?></textarea></div>
            <div class="r3">
                <div><label class="lbl">City</label><input type="text" name="shipping_city" id="sCity"
                        value="<?= htmlspecialchars($c->shipping_city??'') ?>" class="inp"></div>
                <div><label class="lbl">State</label><input type="text" name="shipping_state" id="sState"
                        value="<?= htmlspecialchars($c->shipping_state??'') ?>" class="inp"></div>
                <div><label class="lbl">PIN Code</label><input type="text" name="shipping_pincode" id="sPin"
                        value="<?= htmlspecialchars($c->shipping_pincode??'') ?>" class="inp" maxlength="10"></div>
            </div>

            <div class="divider">Notes</div>
            <textarea name="notes" class="inp" rows="2"
                placeholder="Internal notes about this client..."><?= htmlspecialchars($c->notes??'') ?></textarea>

            <div style="display:flex;gap:10px;margin-top:18px">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Client</button>
                <a href="<?= site_url('client_billing/client_billing/clients') ?>" class="btn btn-default">Cancel</a>
            </div>
        </div>
    </div>
</form>

<script>
function copyAddr() {
    document.getElementById('sAddr').value = document.querySelector('[name="billing_address"]').value;
    document.getElementById('sCity').value = document.querySelector('[name="billing_city"]').value;
    document.getElementById('sState').value = document.querySelector('[name="billing_state"]').value;
    document.getElementById('sPin').value = document.querySelector('[name="billing_pincode"]').value;
}
var GSTIN_RE = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

function valGstin(inp) {
    var v = inp.value.toUpperCase();
    inp.value = v;
    var msg = document.getElementById('gstinMsg');
    if (!v) {
        msg.textContent = '';
        return;
    }
    if (v.length < 15) {
        msg.style.color = '#94a3b8';
        msg.textContent = (15 - v.length) + ' more chars';
        return;
    }
    if (GSTIN_RE.test(v)) {
        msg.style.color = '#16a34a';
        msg.textContent = '✓ Valid GSTIN';
    } else {
        msg.style.color = '#dc2626';
        msg.textContent = '✗ Invalid GSTIN';
    }
}
</script>