<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
:root{--p:#0b0895;--s:#e0802d;--bd:#e2e8f0;--r:10px;--sh:0 4px 20px rgba(0,0,0,.07);}
.stat-card{border-radius:var(--r);padding:20px 22px;color:#fff;position:relative;overflow:hidden;box-shadow:var(--sh);transition:transform .2s;}
.stat-card:hover{transform:translateY(-4px);}
.stat-card .icon{position:absolute;right:16px;top:14px;font-size:40px;opacity:.18;}
.stat-card .val{font-size:26px;font-weight:800;line-height:1.1;}
.stat-card .lbl{font-size:12px;opacity:.88;font-weight:500;margin-top:4px;}
.stat-card .sub{font-size:11px;opacity:.65;margin-top:2px;}
.g-blue{background:linear-gradient(135deg,#0b0895,#1e40af);}
.g-orange{background:linear-gradient(135deg,#e0802d,#f59e0b);}
.g-green{background:linear-gradient(135deg,#16a34a,#22c55e);}
.g-red{background:linear-gradient(135deg,#dc2626,#ef4444);}
.g-teal{background:linear-gradient(135deg,#0891b2,#06b6d4);}
.g-purple{background:linear-gradient(135deg,#7c3aed,#a78bfa);}
.bil-panel{background:#fff;border-radius:var(--r);border:1px solid var(--bd);box-shadow:var(--sh);margin-bottom:20px;overflow:hidden;}
.bil-ph{padding:13px 18px;border-bottom:1px solid var(--bd);display:flex;align-items:center;justify-content:space-between;background:#fafbff;}
.bil-ph h4{margin:0;font-size:14px;font-weight:700;color:#1e293b;}
.inv-tbl{width:100%;border-collapse:collapse;font-size:13px;}
.inv-tbl thead th{background:var(--p);color:#fff;padding:9px 13px;font-size:11.5px;font-weight:600;text-transform:uppercase;letter-spacing:.4px;}
.inv-tbl tbody td{padding:9px 13px;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
.inv-tbl tbody tr:hover td{background:#f8fafc;}
.qa-btn{display:flex;align-items:center;gap:10px;background:#f8fafc;border:1px solid var(--bd);border-radius:8px;padding:11px 15px;color:#1e293b;text-decoration:none;font-size:13px;font-weight:600;transition:.15s;margin-bottom:8px;}
.qa-btn:hover{background:var(--p);color:#fff;text-decoration:none;}
.svc-row{display:flex;align-items:center;gap:12px;padding:10px 16px;border-bottom:1px solid var(--bd);}
.svc-row:last-child{border-bottom:none;}
.svc-bar-wrap{flex:1;background:#f1f5f9;border-radius:10px;height:8px;overflow:hidden;}
.svc-bar{height:100%;background:linear-gradient(90deg,var(--p),var(--s));border-radius:10px;}
.chart-wrap{height:250px;}
</style>

<!-- Stat Cards -->
<div class="row" style="margin-bottom:20px">
  <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:14px">
    <div class="stat-card g-blue"><i class="fa fa-inr icon"></i>
      <div class="val">&#8377;<?= number_format($stats['revenue_this_month'],0) ?></div>
      <div class="lbl">Revenue This Month</div><div class="sub"><?= date('F Y') ?></div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:14px">
    <div class="stat-card g-orange"><i class="fa fa-clock-o icon"></i>
      <div class="val">&#8377;<?= number_format($stats['outstanding'],0) ?></div>
      <div class="lbl">Outstanding</div><div class="sub">Unpaid + Overdue</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:14px">
    <div class="stat-card g-teal"><i class="fa fa-file-text-o icon"></i>
      <div class="val"><?= $stats['total_invoices'] ?></div>
      <div class="lbl">Total Invoices</div><div class="sub"><?= $stats['draft_count'] ?> Draft</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:14px">
    <div class="stat-card g-red"><i class="fa fa-exclamation-triangle icon"></i>
      <div class="val"><?= $stats['overdue_count'] ?></div>
      <div class="lbl">Overdue</div><div class="sub">Needs attention</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:14px">
    <div class="stat-card g-green"><i class="fa fa-percent icon"></i>
      <div class="val">&#8377;<?= number_format($stats['gst_this_month'],0) ?></div>
      <div class="lbl">GST This Month</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6" style="margin-bottom:14px">
    <div class="stat-card g-purple"><i class="fa fa-building-o icon"></i>
      <div class="val"><?= $stats['total_clients'] ?></div>
      <div class="lbl">Active Clients</div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Revenue Chart -->
  <div class="col-md-8">
    <div class="bil-panel">
      <div class="bil-ph"><h4><i class="fa fa-bar-chart" style="color:var(--p);margin-right:7px"></i> Monthly Revenue</h4>
        <a href="<?= site_url('client_billing/Client_billing/revenue_report') ?>" class="btn btn-xs btn-default">Full Report</a>
      </div>
      <div style="padding:16px"><div class="chart-wrap"><canvas id="revChart"></canvas></div></div>
    </div>
  </div>
  <!-- Quick Actions -->
  <div class="col-md-4">
    <div class="bil-panel">
      <div class="bil-ph"><h4><i class="fa fa-bolt" style="color:var(--s);margin-right:7px"></i> Quick Actions</h4></div>
      <div style="padding:14px">
        <a href="<?= site_url('client_billing/Client_billing/create_invoice') ?>" class="qa-btn"><i class="fa fa-plus-circle" style="color:var(--p)"></i> Create New Invoice</a>
        <a href="<?= site_url('client_billing/Client_billing/clients') ?>" class="qa-btn"><i class="fa fa-building-o" style="color:#16a34a"></i> Add New Client</a>
        <a href="<?= site_url('client_billing/Client_billing/record_payment') ?>" class="qa-btn"><i class="fa fa-credit-card" style="color:var(--s)"></i> Record Payment</a>
        <a href="<?= site_url('client_billing/Client_billing/outstanding_report') ?>" class="qa-btn"><i class="fa fa-exclamation-circle" style="color:#dc2626"></i> Outstanding Report</a>
        <a href="<?= site_url('client_billing/Client_billing/gst_report') ?>" class="qa-btn"><i class="fa fa-file-pdf-o" style="color:#0891b2"></i> GST Report</a>
        <a href="<?= site_url('client_billing/Client_billing/services') ?>" class="qa-btn"><i class="fa fa-cogs" style="color:#7c3aed"></i> Services</a>
        <a href="<?= site_url('client_billing/Client_billing/settings') ?>" class="qa-btn"><i class="fa fa-cog" style="color:#64748b"></i> Settings</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent Invoices -->
  <div class="col-md-8">
    <div class="bil-panel">
      <div class="bil-ph"><h4><i class="fa fa-file-text-o" style="color:var(--p);margin-right:7px"></i> Recent Invoices</h4>
        <a href="<?= site_url('client_billing/Client_billing/invoices') ?>" class="btn btn-xs btn-primary">View All</a>
      </div>
      <table class="inv-tbl">
        <thead><tr><th>Invoice #</th><th>Client</th><th>Date</th><th>Amount</th><th>Due</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php
        $bmap=['paid'=>['#dcfce7','#15803d'],'unpaid'=>['#fef3c7','#92400e'],'overdue'=>['#fee2e2','#991b1b'],'draft'=>['#f1f5f9','#64748b'],'partial'=>['#dbeafe','#1e40af'],'sent'=>['#e0e7ff','#4338ca'],'cancelled'=>['#f3f4f6','#4b5563']];
        foreach($stats['recent_invoices'] as $inv):
          $sc=$bmap[$inv->status]??['#f1f5f9','#64748b'];
        ?>
        <tr>
          <td><strong style="color:var(--p);font-family:monospace"><?= $inv->invoice_number ?></strong></td>
          <td><div style="font-weight:600"><?= htmlspecialchars($inv->company_name??'') ?></div></td>
          <td><?= date('d M Y',strtotime($inv->invoice_date)) ?></td>
          <td style="font-weight:700">&#8377;<?= number_format($inv->grand_total,2) ?></td>
          <td style="color:<?= $inv->balance_due>0?'#dc2626':'#16a34a' ?>;font-weight:600">&#8377;<?= number_format($inv->balance_due,2) ?></td>
          <td><span style="background:<?=$sc[0]?>;color:<?=$sc[1]?>;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700"><?= ucfirst($inv->status) ?></span></td>
          <td>
            <a href="<?= site_url('client_billing/Client_billing/view_invoice/'.$inv->id) ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
            <a href="<?= site_url('client_billing/Client_billing/print_invoice/'.$inv->id) ?>" class="btn btn-xs btn-default" target="_blank"><i class="fa fa-print"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($stats['recent_invoices'])): ?>
        <tr><td colspan="7" style="text-align:center;padding:30px;color:#94a3b8">No invoices yet. <a href="<?= site_url('client_billing/Client_billing/create_invoice') ?>">Create first invoice</a></td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Top Clients -->
  <div class="col-md-4">
    <div class="bil-panel">
      <div class="bil-ph"><h4><i class="fa fa-trophy" style="color:var(--s);margin-right:7px"></i> Top Clients</h4></div>
      <?php foreach($stats['top_clients'] as $i=>$cl): ?>
      <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-bottom:1px solid var(--bd)">
        <div style="width:26px;height:26px;border-radius:50%;background:var(--p);color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800"><?= $i+1 ?></div>
        <div style="flex:1;min-width:0"><div style="font-weight:600;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($cl->company_name??'') ?></div><div style="font-size:11px;color:#94a3b8"><?= $cl->cnt ?> invoices</div></div>
        <div style="font-weight:800;font-size:13px;color:#16a34a;white-space:nowrap">&#8377;<?= number_format($cl->total,0) ?></div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($stats['top_clients'])): ?><div style="padding:20px;text-align:center;color:#94a3b8;font-size:13px">No data yet.</div><?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('revChart'),{type:'bar',data:{labels:<?= json_encode(array_column($stats['monthly_revenue'],'month')) ?>,datasets:[{label:'Revenue',data:<?= json_encode(array_column($stats['monthly_revenue'],'revenue')) ?>,backgroundColor:'rgba(11,8,149,.15)',borderColor:'#0b0895',borderWidth:2,borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>'&#8377;'+v.toLocaleString('en-IN')}},x:{grid:{display:false}}}}});
</script>
