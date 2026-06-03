<?php defined('BASEPATH') OR exit('No direct script access allowed'); $co=$company; ?>
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
    margin: 18px 0 14px;
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

.bank-card {
    border: 1.5px solid var(--bd);
    border-radius: 8px;
    padding: 13px 15px;
    position: relative;
}

.bank-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.def-badge {
    background: #dcfce7;
    color: #15803d;
    padding: 2px 9px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
}

.svc-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}

.svc-row:last-child {
    border-bottom: none;
}

.svc-type {
    width: 55px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    padding: 3px 6px;
    border-radius: 5px;
    text-align: center;
}

.st-sft {
    background: #dbeafe;
    color: #1e40af;
}

.st-spt {
    background: #dcfce7;
    color: #15803d;
}

.st-mkt {
    background: #fef3c7;
    color: #92400e;
}

.st-inf {
    background: #f3e8ff;
    color: #7c3aed;
}

.st-con {
    background: #f1f5f9;
    color: #64748b;
}

.st-mnt {
    background: #ecfdf5;
    color: #065f46;
}

.st-dev {
    background: #fff7ed;
    color: #9a3412;
}

.st-des {
    background: #fdf2f8;
    color: #9d174d;
}
</style>

<!-- Company Settings -->
<form action="<?= site_url('client_billing/client_billing/save_settings') ?>" method="post"
    enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="bil-card">
        <div class="bil-head"><i class="fa fa-building-o"></i> Company Information</div>
        <div class="bil-body">

            <div class="r3">
                <div><label class="lbl">Company Name <span style="color:#dc2626">*</span></label><input type="text"
                        name="name" value="<?= htmlspecialchars($co->name??'') ?>" class="inp" required></div>
                <div><label class="lbl">GSTIN</label><input type="text" name="gstin"
                        value="<?= htmlspecialchars($co->gstin??'') ?>" class="inp" maxlength="15"></div>
                <div><label class="lbl">PAN</label><input type="text" name="pan"
                        value="<?= htmlspecialchars($co->pan??'') ?>" class="inp" maxlength="10"></div>
            </div>

            <div class="r3">
                <div><label class="lbl">Phone</label><input type="text" name="phone"
                        value="<?= htmlspecialchars($co->phone??'') ?>" class="inp"></div>
                <div><label class="lbl">Email</label><input type="email" name="email"
                        value="<?= htmlspecialchars($co->email??'') ?>" class="inp"></div>
                <div><label class="lbl">Website</label><input type="text" name="website"
                        value="<?= htmlspecialchars($co->website??'') ?>" class="inp"></div>
            </div>

            <div style="margin-bottom:14px"><label class="lbl">Address</label><textarea name="address" class="inp"
                    rows="2"><?= htmlspecialchars($co->address??'') ?></textarea></div>

            <div class="r3">
                <div><label class="lbl">City</label><input type="text" name="city"
                        value="<?= htmlspecialchars($co->city??'') ?>" class="inp"></div>
                <div><label class="lbl">State</label><input type="text" name="state"
                        value="<?= htmlspecialchars($co->state??'') ?>" class="inp"></div>
                <div><label class="lbl">PIN Code</label><input type="text" name="pincode"
                        value="<?= htmlspecialchars($co->pincode??'') ?>" class="inp"></div>
            </div>

            <div class="divider">Invoice Configuration</div>
            <div class="r3">
                <div><label class="lbl">Invoice Prefix</label><input type="text" name="invoice_prefix"
                        value="<?= htmlspecialchars($co->invoice_prefix??'INV') ?>" class="inp" placeholder="INV"></div>
                <div><label class="lbl">UPI ID (for QR payment)</label><input type="text" name="upi_id"
                        value="<?= htmlspecialchars($co->upi_id??'') ?>" class="inp" placeholder="company@bankname">
                </div>
                <div><label class="lbl">Company Logo</label>
                    <input type="file" name="company_logo" class="inp" style="padding:5px" accept="image/*">
                    <?php if($co->logo): ?><div style="margin-top:5px"><img src="<?=$co->logo?>"
                            style="height:36px;border-radius:6px"></div><?php endif; ?>
                </div>
            </div>

            <div class="divider">Default Content</div>
            <div class="r2">
                <div><label class="lbl">Default Terms &amp; Conditions</label><textarea name="terms" class="inp"
                        rows="4"
                        placeholder="1. Payment due within specified days..."><?= htmlspecialchars($co->terms??'') ?></textarea>
                </div>
                <div><label class="lbl">Invoice Footer Note</label><textarea name="footer_note" class="inp" rows="4"
                        placeholder="Thank you for your business!"><?= htmlspecialchars($co->footer_note??'') ?></textarea>
                </div>
            </div>

            <div style="margin-top:18px">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Company Settings</button>
            </div>
        </div>
    </div>
</form>

<!-- Bank Accounts -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-university"></i> Bank Accounts</span>
        <button class="btn btn-xs"
            style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3)"
            onclick="var p=document.getElementById('addBankPanel');p.style.display=p.style.display==='none'?'block':'none'">
            <i class="fa fa-plus"></i> Add Bank
        </button>
    </div>
    <div class="bil-body">

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:14px;margin-bottom:16px">
            <?php foreach($banks as $b): ?>
            <div class="bank-card">
                <div class="bank-card-head">
                    <strong style="font-size:13.5px;color:var(--p)"><?= htmlspecialchars($b->bank_name) ?></strong>
                    <?php if($b->is_default): ?><span class="def-badge">Default</span><?php endif; ?>
                </div>
                <div style="font-size:12.5px;color:#374151;line-height:1.8">
                    <div><?= htmlspecialchars($b->account_name) ?></div>
                    <div style="font-family:monospace;font-weight:700;font-size:13px"><?= $b->account_number ?></div>
                    <div style="color:#64748b">IFSC: <?= $b->ifsc_code ?> | <?= $b->branch_name ?></div>
                    <?php if($b->upi_id): ?><div style="color:var(--p);font-weight:600">UPI: <?= $b->upi_id ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Add Bank Form -->
        <div id="addBankPanel" style="display:none;border:1.5px dashed var(--bd);border-radius:8px;padding:16px">
            <div style="font-size:13px;font-weight:700;color:var(--p);margin-bottom:12px"><i
                    class="fa fa-plus-circle"></i> Add New Bank Account</div>
            <form action="<?= site_url('client_billing/client_billing/save_settings') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="add_bank" value="1">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                    <div><label class="lbl">Bank Name</label><input type="text" name="bank_name" class="inp" required>
                    </div>
                    <div><label class="lbl">Account Name</label><input type="text" name="account_name" class="inp"
                            required></div>
                    <div><label class="lbl">Account Number</label><input type="text" name="account_number" class="inp"
                            required></div>
                    <div><label class="lbl">IFSC Code</label><input type="text" name="ifsc_code" class="inp"></div>
                    <div><label class="lbl">Branch Name</label><input type="text" name="branch_name" class="inp"></div>
                    <div><label class="lbl">UPI ID</label><input type="text" name="bank_upi_id" class="inp"
                            placeholder="company@bankname"></div>
                </div>
                <div style="margin-bottom:12px"><label
                        style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;cursor:pointer"><input
                            type="checkbox" name="is_default" value="1"> Set as Default Bank</label></div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save Bank
                    Account</button>
            </form>
        </div>
    </div>
</div>

<!-- Service Catalog Quick View -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-cogs"></i> Service Catalog (<?= count($services) ?> services)</span>
        <a href="<?= site_url('client_billing/client_billing/services') ?>" class="btn btn-xs"
            style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3)">Manage →</a>
    </div>
    <div class="bil-body" style="padding:0">
        <?php
    $cat_colors = ['Software'=>'st-sft','Support'=>'st-spt','Marketing'=>'st-mkt','Infrastructure'=>'st-inf','Consulting'=>'st-con','Maintenance'=>'st-mnt','Development'=>'st-dev','Design'=>'st-des'];
    foreach($services as $svc):
      $cc = $cat_colors[$svc->category??''] ?? 'st-con';
    ?>
        <div class="svc-row" style="padding:9px 18px">
            <?php if($svc->category): ?><span
                class="svc-type <?= $cc ?>"><?= htmlspecialchars(substr($svc->category,0,4)) ?></span><?php endif; ?>
            <div style="flex:1;font-weight:600"><?= htmlspecialchars($svc->name) ?></div>
            <div style="font-family:monospace;font-size:12px;color:var(--p);font-weight:700;width:65px">
                <?= $svc->hsn_sac?:'—' ?></div>
            <div style="font-weight:700;color:#1e293b;width:90px;text-align:right">
                ₹<?= number_format($svc->default_rate,2) ?></div>
            <div style="font-size:11.5px;color:#64748b;width:100px;text-align:right">
                <?= $svc->cgst_rate ?>%+<?= $svc->sgst_rate ?>% GST</div>
            <span
                style="background:<?= $svc->is_active?'#dcfce7':'#fee2e2' ?>;color:<?= $svc->is_active?'#15803d':'#991b1b' ?>;padding:2px 8px;border-radius:10px;font-size:10.5px;font-weight:700"><?= $svc->is_active?'Active':'Off' ?></span>
        </div>
        <?php endforeach; ?>
        <?php if(empty($services)): ?>
        <div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px">No services yet. <a
                href="<?= site_url('client_billing/client_billing/services') ?>">Add services →</a></div>
        <?php endif; ?>
    </div>
</div>