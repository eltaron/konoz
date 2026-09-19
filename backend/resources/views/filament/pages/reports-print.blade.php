<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>تقرير {{ $meta['نوع التقرير'] }} - منصة كُنوز التعليمية</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Cairo', 'Segoe UI', Tahoma, Arial, sans-serif; color: #1f2937; background: #f3f4f6; padding: 24px; }
  .sheet { max-width: 820px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
  .print-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #0F6D80; padding-bottom: 18px; margin-bottom: 22px; }
  .brand { display: flex; align-items: center; gap: 12px; }
  .brand img { width: 52px; height: 52px; object-fit: contain; }
  .brand-name { font-size: 1.25rem; font-weight: 700; color: #0F6D80; }
  .brand-sub { font-size: 0.78rem; color: #6b7a7e; }
  .print-date { font-size: 0.8rem; color: #6b7a7e; text-align: left; }
  h1.report-title { font-size: 1.15rem; color: #0F6D80; margin-bottom: 14px; }
  .report-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px 24px; background: rgba(15,109,128,0.04); border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 0.88rem; }
  .report-meta b { color: #0F6D80; }
  table.results { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
  table.results th, table.results td { border: 1px solid #d7e0e3; padding: 9px 12px; text-align: right; }
  table.results th { background: #0F6D80; color: #fff; font-weight: 600; }
  table.results tr:nth-child(even) td { background: rgba(15,109,128,0.03); }
  .empty-state { text-align: center; color: #6b7a7e; padding: 32px 0; font-size: 0.9rem; }
  .sign-row { display: flex; justify-content: space-between; margin-top: 48px; font-size: 0.85rem; color: #374151; }
  .sign-row span { border-top: 1px dashed #9ca3af; padding-top: 6px; min-width: 160px; text-align: center; }
  .toolbar { max-width: 820px; margin: 0 auto 16px; display: flex; gap: 10px; }
  .toolbar a, .toolbar button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none; font-family: inherit; }
  .btn-print { background: #0F6D80; color: #fff; }
  .btn-back { background: #fff; color: #0F6D80; border: 1.5px solid #0F6D80 !important; }
  @media print {
    body { background: #fff; padding: 0; }
    .sheet { box-shadow: none; border-radius: 0; max-width: none; padding: 12mm; }
    .toolbar { display: none; }
    table.results th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    table.results tr:nth-child(even) td, .report-meta { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    @page { size: A4; margin: 10mm; }
  }
</style>
</head>
<body>
  <div class="toolbar">
    <button class="btn-print" onclick="window.print()">&#128424; طباعة التقرير</button>
    <a class="btn-back" href="{{ route('filament.admin.pages.reports') }}">العودة إلى التقارير</a>
  </div>

  <div class="sheet" dir="rtl">
    <div class="print-head">
      <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="">
        <div>
          <div class="brand-name">منصة كُنوز التعليمية</div>
          <div class="brand-sub">نظام التقارير والإحصائيات الشاملة</div>
        </div>
      </div>
      <div class="print-date">
        تاريخ الطباعة<br>
        <b>{{ now()->format('Y/m/d H:i') }}</b>
      </div>
    </div>

    <h1 class="report-title">تقرير {{ $meta['نوع التقرير'] }}</h1>

    <div class="report-meta">
      @foreach($meta as $label => $value)
        <div><b>{{ $label }}:</b> {{ $value }}</div>
      @endforeach
    </div>

    @if($results->count())
    <table class="results">
      <thead>
        <tr>
          @foreach($columns as $col)
            <th>{{ $col['label'] }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($results as $row)
        <tr>
          @foreach($columns as $col)
            <td>{{ $col['value']($row) }}</td>
          @endforeach
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="empty-state">لا توجد نتائج مطابقة للفلاتر المحددة</div>
    @endif

    <div class="sign-row">
      <span>إعداد</span>
      <span>اعتماد مدير المنصة</span>
    </div>
  </div>
</body>
</html>