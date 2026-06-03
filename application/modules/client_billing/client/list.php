<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
:root {
    --p: #0b0895;
    --s: #e0802d;
    --bd: #e2e8f0;
    --r: 10px;
    --sh: 0 4px 20px rgba(0, 0, 0, .07);
}

.cust-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 16px;
}

.cust-card {
    background: #fff;
    border-radius: var(--r);
    border: 1px solid var(--bd);
    box-shadow: var(--sh);
    padding: 17px 18px;
    transition: .2s;
    position: relative;
    overflow: hidden;
}

.cust-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0, 0, 0, .1);
}

.cust-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--p), var(--s));
}

.c-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--p), #1e40af);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 900;
    flex-shrink: 0;
}

.c-name {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}

.c-sub {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 2px;
}

.c-meta {
    font-size: 12px;
    color: #64748b;
    margin-top: 8px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.c-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}

.c-meta i {
    width: 13px;
    text-align: center;
    color: var(--p);
}

.c-actions {
    display: flex;
    gap: 6px;
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid var(--bd);
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

.search-inp {
    border: 1.5px solid var(--bd);
    border-radius: 8px;
    padding: 7px 14px;
    font-size: 13px;
    width: 260px;
}

.search-inp:focus {
    border-color: var(--p);
    outline: none;
}

.empty-st {
    grid-column: 1/-1;
    text-align: center;
    padding: 50px;
    color: #94a3b8;
}

.empty-st i {
    font-size: 48px;
    display: block;
    margin-bottom: 14px;
    opacity: .22;
}
</style>

<div class="toolbar">
    <a href="<?= site_url('client_billing/client_billing/add_client') ?>" class="btn btn-primary btn-sm"><i
            class="fa fa-plus"></i> Add Client</a>
    <div class="sp"></div>
    <input type="text" class="search-inp" id="clientSearch" placeholder="Search clients..." oninput="filterClients()">
    <span style="font-size:13px;color:#94a3b8"><?= count($clients) ?> clients</span>
</div>

<div class="cust-grid" id="clientGrid">
    <?php if(empty($clients)): ?>
    <div class="empty-st">
        <i class="fa fa-building-o"></i>
        <div style="font-size:15px;font-weight:600;margin-bottom:8px">No clients yet</div>
        <a href="<?= site_url('client_billing/client_billing/add_client') ?>" class="btn btn-primary btn-sm">Add First
            Client</a>
    </div>
    <?php endif; ?>
    <?php foreach($clients as $c): ?>
    <div class="cust-card"
        data-s="<?= strtolower(htmlspecialchars($c->company_name.' '.($c->contact_person??'').' '.($c->email??'').' '.($c->gstin??''))) ?>">
        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
            <div class="c-avatar"><?= strtoupper(substr($c->company_name,0,1)) ?></div>
            <div style="flex:1;min-width:0">
                <div class="c-name"><?= htmlspecialchars($c->company_name) ?></div>
                <div class="c-sub"><?= $c->contact_person?htmlspecialchars($c->contact_person):'—' ?></div>
            </div>
        </div>
        <div class="c-meta">
            <?php if($c->email):   ?><span><i class="fa fa-envelope-o"></i>
                <?= htmlspecialchars($c->email) ?></span><?php endif; ?>
            <?php if($c->mobile||$c->phone): ?><span><i class="fa fa-phone"></i>
                <?= $c->mobile?:$c->phone ?></span><?php endif; ?>
            <?php if($c->billing_city||$c->billing_state): ?><span><i class="fa fa-map-marker"></i>
                <?= implode(', ',array_filter([$c->billing_city,$c->billing_state])) ?></span><?php endif; ?>
            <?php if($c->gstin): ?><span><i class="fa fa-id-card-o"></i> <?= $c->gstin ?></span><?php endif; ?>
        </div>
        <div class="c-actions">
            <a href="<?= site_url('client_billing/client_billing/client_detail/'.$c->id) ?>"
                class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View</a>
            <a href="<?= site_url('client_billing/client_billing/edit_client/'.$c->id) ?>"
                class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Edit</a>
            <a href="<?= site_url('client_billing/client_billing/create_invoice?client_id='.$c->id) ?>"
                class="btn btn-primary btn-xs"><i class="fa fa-file-text-o"></i> Invoice</a>
            <a href="<?= site_url('client_billing/client_billing/delete_client/'.$c->id) ?>"
                class="btn btn-danger btn-xs" onclick="return confirm('Delete this client?')"><i
                    class="fa fa-trash"></i></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
function filterClients() {
    var q = document.getElementById('clientSearch').value.toLowerCase();
    document.querySelectorAll('.cust-card').forEach(function(el) {
        el.style.display = el.dataset.s.includes(q) ? '' : 'none';
    });
}
</script>