<?php defined('BASEPATH') OR exit('No direct script access allowed');
$bmap=['paid'=>['#dcfce7','#15803d'],'unpaid'=>['#fef3c7','#92400e'],'overdue'=>['#fee2e2','#991b1b'],'draft'=>['#f1f5f9','#64748b'],'partial'=>['#dbeafe','#1e40af'],'sent'=>['#e0e7ff','#4338ca'],'cancelled'=>['#f3f4f6','#4b5563']];
?>
<style>
:root{--p:#0b0895;--s:#e0802d;--bd:#e2e8f0;--r:10px;}
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:18px;}.toolbar .sp{flex:1;}
.filter-bar{background:#fff;border-radius:9px;border:1px solid var(--bd);padding:13px 16px;margin-bottom:16px;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;}
.status-tabs{display:flex;gap:5px;flex-wrap:wrap;margin-bottom:14px;}
.stab{padding:5px 14px;border-radius:20px;border:1px solid var(--bd);background:#fff;color:#64748b;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;transition:.15s;}
.stab:hover,.stab.active{background:var(--p);color:#fff;border-color:var(--p);text-decoration:none;}
.tbl-wrap{background:#fff;border-radius:var(--r);box-shadow:0 4px 20px rgba(0,0,0,.07);border:1px solid var(--bd);overflow:hidden;}
.tbl{width:100%;border-collapse:collapse;font-size:13px;}
.tbl thead th{background:var(--p);color:#fff;padding:10px 13px;font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;white-space:nowrap;}
.tbl tbody td{padding:10px 13px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
.tbl tbody tr:hover td{background:#f8fafc;}
.tbl tbody tr:last-child td{border-bottom:none;}
.inv-no{font-weight:700;color:var(--p);font-family:monospace;}
.pag-wrap{display:flex;align-items:center;justify-content:space-between;padding:11px 14px;border-top:1px solid #f1f5f9;font-size:13px;color:#64748b;}
</style>

<div class="toolbar">
  <a href="<?= site_url('client_billing/Client_billing/create_invoice') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Invoice</a>
  <div class="sp"></div>
  <span style="font-size:13px;color:#94a3b8"><?= $total ?> found</span>
</div>

<div class="status-tabs">
<?php foreach(['' =>'All','draft'=>'Draft','sent'=>'Sent','unpaid'=>'Unpaid','partial'=>'Partial','paid'=>'Paid','overdue'=>'Overdue','cancelled'=>'Cancelled'] as $k=>$l): ?>
<a href="<?= site_url('client_billing/Client_billing/invoices?status='.$k) ?>" class="stab <?= ($filters['status']==$k)?'active':'' ?>"><?= $l ?></a>
<?php endforeach; ?>
</div>

<div class="filter-bar">
<form method="get" action="<?= site_url('client_billing/Client_billing/invoices') ?>" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;width:100%">
  <div style="flex:2;min-width:170px"><input type="text" name="search" value="<?= htmlspecialchars($filters['search']??'') ?>" placeholder="Search invoice #, client..." class="form-control input-sm"></div>
  <div style="flex:1.5;min-width:150px">
    <select name="client_id" class="form-control input-sm">
      <option value="">All Clients</option>
      <?php foreach($clients as $c): ?><option value="<?=$c->id?>" <?= ($filters['client_id']==$c->id)?'selected':'' ?>><?= htmlspecialchars($c->company_name) ?></option><?php endforeach; ?>
    </select>
  </div>
  <div><input type="date" name="date_from" value="<?= $filters['date_from']??'' ?>" class="form-control input-sm"></div>
  <div><input type="date" name="date_to" value="<?= $filters['date_to']??'' ?>" class="form-control input-sm"></div>
  <input type="hidden" name="status" value="<?= htmlspecialchars($filters['status']??'') ?>">
  <div>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Filter</button>
    <a href="<?= site_url('client_billing/Client_billing/invoices') ?>" class="btn btn-default btn-sm">Reset</a>
  </div>
</form>
</div>

<div class="tbl-wrap">
<table class="tbl">
  <thead><tr><th>#</th><th>Invoice No.</th><th>Client</th><th>Invoice Date</th><th>Due Date</th><th>Grand Total</th><th>Balance Due</th><th>Status</th><th style="text-align:center">Actions</th></tr></thead>
  <tbody>
  <?php if(empty($invoices)): ?>
  <tr><td colspan="9" style="text-align:center;padding:40px;color:#94a3b8"><i class="fa fa-file-text-o" style="font-size:36px;display:block;margin-bottom:12px;opacity:.2"></i>No invoices found. <a href="<?= site_url('client_billing/Client_billing/create_invoice') ?>">Create first invoice</a></td></tr>
  <?php else: ?>
  <?php foreach($invoices as $i=>$inv):
    $sc=$bmap[$inv->status]??['#f1f5f9','#64748b'];
    $n=(($current_page-1)*$per_page)+$i+1;
  ?>
  <tr>
    <td style="color:#94a3b8"><?= $n ?></td>
    <td><span class="inv-no"><?= $inv->invoice_number ?></span></td>
    <td><div style="font-weight:600"><?= htmlspecialchars($inv->company_name??'') ?></div><?php if($inv->contact_person): ?><div style="font-size:11px;color:#94a3b8"><?= htmlspecialchars($inv->contact_person) ?></div><?php endif; ?></td>
    <td><?= date('d M Y',strtotime($inv->invoice_date)) ?></td>
    <td><?= $inv->due_date?date('d M Y',strtotime($inv->due_date)):'—' ?></td>
    <td style="font-weight:700">&#8377;<?= number_format($inv->grand_total,2) ?></td>
    <td style="font-weight:700;color:<?= $inv->balance_due>0?'#dc2626':'#16a34a' ?>">&#8377;<?= number_format($inv->balance_due,2) ?></td>
    <td><span style="background:<?=$sc[0]?>;color:<?=$sc[1]?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700"><?= ucfirst($inv->status) ?></span></td>
    <td style="text-align:center;white-space:nowrap">
      <div class="btn-group btn-group-xs">
        <a href="<?= site_url('client_billing/Client_billing/view_invoice/'.$inv->id) ?>" class="btn btn-info btn-xs" title="View"><i class="fa fa-eye"></i></a>
        <a href="<?= site_url('client_billing/Client_billing/edit_invoice/'.$inv->id) ?>" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
        <a href="<?= site_url('client_billing/Client_billing/print_invoice/'.$inv->id) ?>" class="btn btn-default btn-xs" title="Print" target="_blank"><i class="fa fa-print"></i></a>
        <a href="<?= site_url('client_billing/Client_billing/duplicate_invoice/'.$inv->id) ?>" class="btn btn-primary btn-xs" onclick="return confirm('Duplicate?')"><i class="fa fa-copy"></i></a>
        <a href="<?= site_url('client_billing/Client_billing/record_payment?invoice_id='.$inv->id) ?>" class="btn btn-success btn-xs"><i class="fa fa-usd"></i></a>
        <a href="<?= site_url('client_billing/Client_billing/delete_invoice/'.$inv->id) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete this invoice?')"><i class="fa fa-trash"></i></a>
      </div>
    </td>
  </tr>
  <?php endforeach; endif; ?>
  </tbody>
</table>
<?php if($total > $per_page): ?>
<div class="pag-wrap">
  <div>Showing <?= min(($current_page-1)*$per_page+1,$total) ?>–<?= min($current_page*$per_page,$total) ?> of <?= $total ?></div>
  <ul class="pagination pagination-sm" style="margin:0">
    <?php $pages=ceil($total/$per_page); for($p=1;$p<=$pages;$p++): ?>
    <li class="<?= $p==$current_page?'active':'' ?>"><a href="<?= site_url('client_billing/Client_billing/invoices?page='.$p.'&status='.urlencode($filters['status']??'')) ?>"><?= $p ?></a></li>
    <?php endfor; ?>
  </ul>
</div>
<?php endif; ?>
</div>
