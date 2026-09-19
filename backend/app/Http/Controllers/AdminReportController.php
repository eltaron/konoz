<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Support\ReportBuilder;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function print(Request $request)
    {
        $type = ReportBuilder::find($request->query('type'));
        $filters = $request->only(['search', 'from', 'to', 'status', 'course_id']);
        $results = ReportBuilder::query($type, $filters);
        $columns = ReportBuilder::columns($type);

        $meta = [
            'نوع التقرير' => ReportBuilder::label($type),
        ];
        if (! empty($filters['search'])) {
            $meta['كلمة البحث'] = $filters['search'];
        }
        if (! empty($filters['from']) || ! empty($filters['to'])) {
            $meta['الفترة'] = ($filters['from'] ?: 'البداية') . ' ← ' . ($filters['to'] ?: 'النهاية');
        }
        if (! empty($filters['status'])) {
            $meta['الحالة'] = ReportBuilder::statuses($type)[$filters['status']] ?? $filters['status'];
        }
        if (! empty($filters['course_id'])) {
            $meta['الدورة'] = Course::find($filters['course_id'])?->name_ar ?? $filters['course_id'];
        }
        $meta['عدد السجلات'] = (string) $results->count();

        return view('filament.pages.reports-print', compact('type', 'results', 'columns', 'meta'));
    }
}