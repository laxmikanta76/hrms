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

.lbl {
    display: block;
    font-size: 11px;
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
    padding: 8px 11px;
    font-size: 13px;
    color: #1e293b;
}

.inp:focus {
    border-color: var(--p);
    outline: none;
}

.tbl {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.tbl thead th {
    background: var(--p);
    color: #fff;
    padding: 9px 13px;
    font-size: 11.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.tbl tbody td {
    padding: 9px 13px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.tbl tbody tr:hover td {
    background: #f8fafc;
}

.tbl tbody tr:last-child td {
    border-bottom: none;
}

.cat-badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 700;
    background: #e0e7ff;
    color: #4338ca;
}
</style>

<div style="display:flex;align-items:center;gap:10px;margin-bottom:18px">
    <h4 style="margin:0;font-weight:700;color:var(--p)">Service Catalog</h4>
    <button class="btn btn-primary btn-sm"
        onclick="document.getElementById('addSvcPanel').style.display=document.getElementById('addSvcPanel').style.display==='none'?'block':'none'">
        <i class="fa fa-plus"></i> Add Service
    </button>
</div>

<!-- Add Service Panel -->
<div id="addSvcPanel" style="display:none;margin-bottom:20px">
    <div class="bil-card">
        <div class="bil-head"><i class="fa fa-plus-circle"></i> Add / Edit Service</div>
        <div style="padding:20px">
            <form action="<?= site_url('client_billing/client_billing/save_service') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="service_id" id="editSvcId" value="">
                <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:12px;margin-bottom:12px">
                    <div><label class="lbl">Service Name *</label><input type="text" name="name" id="svcName"
                            class="inp" required placeholder="Enterprise ERP Software License"></div>
                    <div><label class="lbl">HSN/SAC Code</label><input type="text" name="hsn_sac" id="svcHsn"
                            class="inp" placeholder="998314"></div>
                    <div><label class="lbl">Unit</label>
                        <select name="unit" id="svcUnit" class="inp">
                            <?php foreach(['Nos','Kg','Ltr','Mtr','Hrs','Days','Month','Year','Pcs','Set','Project','License'] as $u): ?>
                            <option value="<?=$u?>"><?=$u?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div><label class="lbl">Default Rate (₹)</label><input type="number" name="default_rate"
                            id="svcRate" class="inp" step="0.01" min="0" placeholder="0.00"></div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;gap:12px;margin-bottom:12px">
                    <div><label class="lbl">CGST %</label><input type="number" name="cgst_rate" id="svcCgst" class="inp"
                            step="0.01" value="9"></div>
                    <div><label class="lbl">SGST %</label><input type="number" name="sgst_rate" id="svcSgst" class="inp"
                            step="0.01" value="9"></div>
                    <div><label class="lbl">IGST %</label><input type="number" name="igst_rate" id="svcIgst" class="inp"
                            step="0.01" value="18"></div>
                    <div><label class="lbl">Category</label><input type="text" name="category" id="svcCat" class="inp"
                            placeholder="Software / Hosting…"></div>
                    <div style="display:flex;align-items:flex-end">
                        <button type="submit" class="btn btn-primary" style="width:100%"><i class="fa fa-save"></i>
                            Save</button>
                    </div>
                </div>
                <div><label class="lbl">Description</label><textarea name="description" id="svcDesc" class="inp"
                        rows="2" placeholder="Short description…"></textarea></div>
            </form>
        </div>
    </div>
</div>

<!-- Services Table -->
<div class="bil-card">
    <div class="bil-head">
        <span><i class="fa fa-cogs"></i> All Services (<?= count($services) ?>)</span>
        <input type="text" placeholder="Search services…" oninput="filterSvc(this.value)"
            style="border:1.5px solid rgba(255,255,255,.3);border-radius:6px;padding:4px 10px;font-size:12px;color:#fff;background:rgba(255,255,255,.15);width:180px">
    </div>
    <table class="tbl" id="svcTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th>HSN/SAC</th>
                <th>Unit</th>
                <th style="text-align:right">Default Rate</th>
                <th style="text-align:center">CGST%</th>
                <th style="text-align:center">SGST%</th>
                <th style="text-align:center">IGST%</th>
                <th>Category</th>
                <th>Status</th>
                <th style="text-align:center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($services)): ?>
            <tr>
                <td colspan="11" style="text-align:center;padding:30px;color:#94a3b8"><i class="fa fa-cogs"
                        style="font-size:32px;display:block;margin-bottom:12px;opacity:.2"></i>No services yet. Add your
                    first service above.</td>
            </tr>
            <?php endif; ?>
            <?php foreach($services as $i=>$svc): ?>
            <tr
                data-s="<?= strtolower(htmlspecialchars($svc->name.' '.($svc->hsn_sac??'').' '.($svc->category??''))) ?>">
                <td style="color:#94a3b8"><?= $i+1 ?></td>
                <td>
                    <div style="font-weight:600"><?= htmlspecialchars($svc->name) ?></div>
                    <?php if($svc->description): ?><div style="font-size:11px;color:#94a3b8">
                        <?= htmlspecialchars(substr($svc->description,0,60)) ?>…</div><?php endif; ?>
                </td>
                <td style="font-family:monospace;font-weight:600;color:var(--p)"><?= $svc->hsn_sac?:'—' ?></td>
                <td><?= $svc->unit ?></td>
                <td style="text-align:right;font-weight:700">₹<?= number_format($svc->default_rate,2) ?></td>
                <td style="text-align:center"><?= $svc->cgst_rate ?>%</td>
                <td style="text-align:center"><?= $svc->sgst_rate ?>%</td>
                <td style="text-align:center"><?= $svc->igst_rate ?>%</td>
                <td><?= $svc->category?'<span class="cat-badge">'.htmlspecialchars($svc->category).'</span>':'—' ?></td>
                <td><span
                        style="background:<?= $svc->is_active?'#dcfce7':'#fee2e2' ?>;color:<?= $svc->is_active?'#15803d':'#991b1b' ?>;padding:2px 9px;border-radius:10px;font-size:11px;font-weight:700"><?= $svc->is_active?'Active':'Inactive' ?></span>
                </td>
                <td style="text-align:center">
                    <button class="btn btn-xs btn-warning"
                        onclick="editSvc(<?= htmlspecialchars(json_encode($svc)) ?>)"><i
                            class="fa fa-pencil"></i></button>
                    <a href="<?= site_url('client_billing/client_billing/delete_service/'.$svc->id) ?>"
                        class="btn btn-xs btn-danger" onclick="return confirm('Deactivate this service?')"><i
                            class="fa fa-ban"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function filterSvc(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#svcTable tbody tr[data-s]').forEach(function(tr) {
        tr.style.display = tr.dataset.s.includes(q) ? '' : 'none';
    });
}

function editSvc(s) {
    document.getElementById('addSvcPanel').style.display = 'block';
    document.getElementById('editSvcId').value = s.id;
    document.getElementById('svcName').value = s.name || '';
    document.getElementById('svcHsn').value = s.hsn_sac || '';
    document.getElementById('svcRate').value = s.default_rate || '';
    document.getElementById('svcCgst').value = s.cgst_rate || 9;
    document.getElementById('svcSgst').value = s.sgst_rate || 9;
    document.getElementById('svcIgst').value = s.igst_rate || 18;
    document.getElementById('svcCat').value = s.category || '';
    document.getElementById('svcDesc').value = s.description || '';
    // Select unit
    Array.from(document.getElementById('svcUnit').options).forEach(function(o) {
        o.selected = o.value === s.unit;
    });
    document.getElementById('addSvcPanel').scrollIntoView({
        behavior: 'smooth'
    });
}
</script>