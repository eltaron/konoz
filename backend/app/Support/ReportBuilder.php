<?php

namespace App\Support;

use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\ContactSubmission;
use App\Models\Course;
use App\Models\EnrollmentRequest;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\JuzProgress;
use App\Models\Message;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportBuilder
{
    public static function types(): array
    {
        return [
            'students' => 'الطلاب',
            'courses' => 'الدورات',
            'sessions' => 'الجلسات',
            'exams' => 'الامتحانات',
            'exam_results' => 'نتائج الامتحانات',
            'attendance' => 'الحضور',
            'certificates' => 'الشهادات الصادرة',
            'enrollment_requests' => 'طلبات التسجيل',
            'messages' => 'الرسائل الداخلية',
            'support_tickets' => 'تذاكر الدعم الفني',
            'contact_submissions' => 'رسائل التواصل',
            'activities' => 'نشاط الطلاب (الحفظ)',
            'juz_progress' => 'تقدم الحفظ (الأجزاء)',
            'users' => 'المستخدمون',
        ];
    }

    public static function find(?string $type): string
    {
        return in_array($type, array_keys(static::types()), true) ? $type : 'students';
    }

    public static function label(string $type): string
    {
        return static::types()[static::find($type)] ?? 'تقرير';
    }

    public static function courseScoped(string $type): bool
    {
        return in_array($type, ['sessions', 'exams', 'exam_results', 'attendance', 'certificates', 'enrollment_requests'], true);
    }

    public static function statuses(string $type): ?array
    {
        return match ($type) {
            'students' => ['active' => 'نشط', 'suspended' => 'موقوف'],
            'sessions' => ['upcoming' => 'قادمة', 'in_progress' => 'قيد التنفيذ', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة'],
            'exams' => ['upcoming' => 'قادم', 'in_progress' => 'قيد التنفيذ', 'completed' => 'منتهي'],
            'attendance' => ['present' => 'حاضر', 'absent' => 'غائب', 'excused' => 'معذور'],
            'certificates' => ['pending' => 'قيد الإصدار', 'delivered' => 'تم التسليم', 'issued' => 'صادرة', 'draft' => 'مسودة'],
            'enrollment_requests' => ['pending' => 'قيد المراجعة', 'approved' => 'مقبول', 'rejected' => 'مرفوض'],
            'support_tickets' => ['open' => 'مفتوحة', 'replied' => 'تم الرد', 'closed' => 'مغلقة'],
            'juz_progress' => ['not_started' => 'لم يبدأ', 'in_progress' => 'جارٍ الحفظ', 'reviewing' => 'مراجعة', 'completed' => 'مكتمل'],
            'courses' => ['1' => 'نشطة', '0' => 'غير نشطة'],
            default => null,
        };
    }

    public static function query(string $type, array $filters = []): Collection
    {
        $type = static::find($type);
        $search = $filters['search'] ?? null;
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;
        $status = $filters['status'] ?? null;
        $courseId = $filters['course_id'] ?? null;

        return match ($type) {
            'students' => static::students($search, $status, $from, $to),
            'courses' => static::courses($search, $status, $from, $to),
            'sessions' => static::sessions($search, $status, $from, $to, $courseId),
            'exams' => static::exams($search, $status, $from, $to, $courseId),
            'exam_results' => static::examResults($search, $from, $to, $courseId),
            'attendance' => static::attendance($search, $status, $from, $to, $courseId),
            'certificates' => static::certificates($search, $status, $from, $to, $courseId),
            'enrollment_requests' => static::enrollmentRequests($search, $status, $from, $to, $courseId),
            'messages' => static::messages($search, $from, $to),
            'support_tickets' => static::supportTickets($search, $status, $from, $to),
            'contact_submissions' => static::contactSubmissions($search, $from, $to),
            'activities' => static::activities($search, $from, $to),
            'juz_progress' => static::juzProgress($search, $status, $from, $to),
            'users' => static::users($search, $from, $to),
            default => collect(),
        };
    }

    public static function columns(string $type): array
    {
        return match (static::find($type)) {
            'students' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الاسم', 'value' => fn ($r) => $r->name_ar],
                ['label' => 'البريد الإلكتروني', 'value' => fn ($r) => $r->email ?: '—'],
                ['label' => 'الهاتف', 'value' => fn ($r) => $r->phone ?: '—'],
                ['label' => 'المستوى', 'value' => fn ($r) => $r->level ?: '—'],
                ['label' => 'الدورات', 'value' => fn ($r) => (string) ($r->courses_count ?? 0)],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'active' => 'نشط', 'suspended' => 'موقوف', default => $r->status ?: '—' }],
                ['label' => 'تاريخ التسجيل', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d') ?: '—'],
            ],
            'courses' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'اسم الدورة', 'value' => fn ($r) => $r->name_ar ?: $r->name_en],
                ['label' => 'الدروس', 'value' => fn ($r) => (string) ($r->lessons_count ?? 0)],
                ['label' => 'الجلسات', 'value' => fn ($r) => (string) ($r->sessions_count ?? 0)],
                ['label' => 'الامتحانات', 'value' => fn ($r) => (string) ($r->exams_count ?? 0)],
                ['label' => 'الطلاب', 'value' => fn ($r) => (string) ($r->students_count ?? 0)],
                ['label' => 'الحالة', 'value' => fn ($r) => $r->is_active ? 'نشطة' : 'غير نشطة'],
                ['label' => 'تاريخ الإنشاء', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d') ?: '—'],
            ],
            'sessions' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'عنوان الجلسة', 'value' => fn ($r) => $r->title_ar ?: $r->title_en],
                ['label' => 'الدورة', 'value' => fn ($r) => $r->course->name_ar ?? '—'],
                ['label' => 'التاريخ', 'value' => fn ($r) => optional($r->date)->format('Y/m/d') ?: '—'],
                ['label' => 'من', 'value' => fn ($r) => $r->time_from ?: '—'],
                ['label' => 'إلى', 'value' => fn ($r) => $r->time_to ?: '—'],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'completed' => 'مكتملة', 'in_progress' => 'قيد التنفيذ', 'cancelled' => 'ملغاة', default => 'قادمة' }],
            ],
            'exams' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'عنوان الامتحان', 'value' => fn ($r) => $r->title_ar ?: $r->title_en],
                ['label' => 'الدورة', 'value' => fn ($r) => $r->course->name_ar ?? '—'],
                ['label' => 'التاريخ', 'value' => fn ($r) => optional($r->date)->format('Y/m/d') ?: '—'],
                ['label' => 'عدد الطلاب', 'value' => fn ($r) => (string) ($r->total_students ?? 0)],
                ['label' => 'متوسط الدرجات', 'value' => fn ($r) => $r->avg_score !== null ? $r->avg_score . '%' : '—'],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'completed' => 'منتهي', 'in_progress' => 'قيد التنفيذ', default => 'قادم' }],
            ],
            'exam_results' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الطالب', 'value' => fn ($r) => $r->student->name_ar ?? '—'],
                ['label' => 'الامتحان', 'value' => fn ($r) => $r->exam->title_ar ?? $r->exam->title_en ?? '—'],
                ['label' => 'الدورة', 'value' => fn ($r) => $r->exam->course->name_ar ?? '—'],
                ['label' => 'الدرجة', 'value' => fn ($r) => $r->score . '%'],
                ['label' => 'الصحيح', 'value' => fn ($r) => $r->correct_count . ' / ' . $r->total_questions],
                ['label' => 'تاريخ التقديم', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d H:i') ?: '—'],
            ],
            'attendance' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الطالب', 'value' => fn ($r) => $r->student->name_ar ?? '—'],
                ['label' => 'الجلسة', 'value' => fn ($r) => $r->session->title_ar ?? $r->session->title_en ?? '—'],
                ['label' => 'الدورة', 'value' => fn ($r) => $r->session->course->name_ar ?? '—'],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'present' => 'حاضر', 'absent' => 'غائب', 'excused' => 'معذور', default => $r->status ?: '—' }],
                ['label' => 'كويز', 'value' => fn ($r) => $r->quiz_score !== null ? $r->quiz_score . ' / ' . $r->quiz_total : '—'],
                ['label' => 'التاريخ', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d H:i') ?: '—'],
            ],
            'certificates' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الطالب', 'value' => fn ($r) => $r->student->name_ar ?? '—'],
                ['label' => 'الدورة', 'value' => fn ($r) => $r->course->name_ar ?? '—'],
                ['label' => 'عنوان الشهادة', 'value' => fn ($r) => $r->title_ar ?: '—'],
                ['label' => 'تاريخ الإصدار', 'value' => fn ($r) => optional($r->issued_at)->format('Y/m/d') ?: optional($r->created_at)->format('Y/m/d') ?: '—'],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'delivered', 'issued' => 'صادرة', 'pending' => 'قيد الإصدار', 'draft' => 'مسودة', default => $r->status ?: '—' }],
            ],
            'enrollment_requests' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الطالب', 'value' => fn ($r) => $r->student->name_ar ?? '—'],
                ['label' => 'الدورة', 'value' => fn ($r) => $r->course->name_ar ?? '—'],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'approved' => 'مقبول', 'rejected' => 'مرفوض', default => 'قيد المراجعة' }],
                ['label' => 'المنصة', 'value' => fn ($r) => $r->approved_at ? 'من المنصة' : 'من الموقع'],
                ['label' => 'التاريخ', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d') ?: '—'],
            ],
            'messages' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'المرسل', 'value' => fn ($r) => $r->sender->name ?? '—'],
                ['label' => 'المستقبِل', 'value' => fn ($r) => $r->receiver->name ?? '—'],
                ['label' => 'نص الرسالة', 'value' => fn ($r) => $r->body],
                ['label' => 'مقروءة', 'value' => fn ($r) => $r->is_read ? 'نعم' : 'لا'],
                ['label' => 'الوقت', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d H:i') ?: '—'],
            ],
            'support_tickets' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الاسم', 'value' => fn ($r) => $r->name],
                ['label' => 'البريد', 'value' => fn ($r) => $r->email],
                ['label' => 'الموضوع', 'value' => fn ($r) => $r->subject],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'replied' => 'تم الرد', 'closed' => 'مغلقة', default => 'مفتوحة' }],
                ['label' => 'تاريخ الإرسال', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d H:i') ?: '—'],
            ],
            'contact_submissions' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الاسم', 'value' => fn ($r) => $r->name],
                ['label' => 'البريد', 'value' => fn ($r) => $r->email],
                ['label' => 'الموضوع', 'value' => fn ($r) => $r->subject ?: '—'],
                ['label' => 'مقروءة', 'value' => fn ($r) => $r->is_read ? 'نعم' : 'لا'],
                ['label' => 'التاريخ', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d H:i') ?: '—'],
            ],
            'activities' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الطالب', 'value' => fn ($r) => $r->student->name_ar ?? '—'],
                ['label' => 'النوع', 'value' => fn ($r) => $r->activity_type ?: '—'],
                ['label' => 'الجزء', 'value' => fn ($r) => $r->juz_number ?: '—'],
                ['label' => 'المدة (دقائق)', 'value' => fn ($r) => $r->duration_minutes ?: '—'],
                ['label' => 'ملاحظات', 'value' => fn ($r) => $r->notes ?: '—'],
                ['label' => 'التاريخ', 'value' => fn ($r) => optional($r->date)->format('Y/m/d') ?: '—'],
            ],
            'juz_progress' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الطالب', 'value' => fn ($r) => $r->student->name_ar ?? '—'],
                ['label' => 'رقم الجزء', 'value' => fn ($r) => (string) $r->juz_number],
                ['label' => 'الحالة', 'value' => fn ($r) => match ($r->status) { 'completed' => 'مكتمل', 'in_progress' => 'جارٍ الحفظ', 'reviewing' => 'مراجعة', default => 'لم يبدأ' }],
                ['label' => 'تاريخ التحديث', 'value' => fn ($r) => optional($r->updated_at)->format('Y/m/d') ?: '—'],
            ],
            'users' => [
                ['label' => '#', 'value' => fn ($r) => $r->id],
                ['label' => 'الاسم', 'value' => fn ($r) => $r->name],
                ['label' => 'البريد', 'value' => fn ($r) => $r->email],
                ['label' => 'الدور', 'value' => fn ($r) => match ($r->role) { 'admin' => 'إدارة', 'teacher' => 'معلم', default => 'طالب' }],
                ['label' => 'تاريخ التسجيل', 'value' => fn ($r) => optional($r->created_at)->format('Y/m/d') ?: '—'],
                ['label' => 'آخر دخول', 'value' => fn ($r) => optional($r->last_login_at)->format('Y/m/d H:i') ?: '—'],
            ],
            default => [['label' => '#', 'value' => fn ($r) => $r->id]],
        };
    }

    /* ==================== Query builders ==================== */

    private static function applyDate($q, string $column, ?string $from, ?string $to): void
    {
        if ($from) {
            $q->whereDate($column, '>=', $from);
        }
        if ($to) {
            $q->whereDate($column, '<=', $to);
        }
    }

    private static function students($search, $status, $from, $to): Collection
    {
        $q = Student::query();
        if ($search) {
            $q->where(fn ($x) => $x->where('name_ar', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"));
        }
        if ($status) {
            $q->where('status', $status);
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->withCount('courses')->orderByDesc('id')->get();
    }

    private static function courses($search, $status, $from, $to): Collection
    {
        $q = Course::query();
        if ($search) {
            $q->where(fn ($x) => $x->where('name_ar', 'like', "%{$search}%")->orWhere('name_en', 'like', "%{$search}%"));
        }
        if ($status !== null && $status !== '') {
            $q->where('is_active', $status === '1');
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->withCount(['lessons', 'sessions', 'exams', 'students'])->orderByDesc('id')->get();
    }

    private static function sessions($search, $status, $from, $to, $courseId): Collection
    {
        $q = Session::with('course');
        if ($status) {
            $q->where('status', $status);
        }
        if ($courseId) {
            $q->where('course_id', $courseId);
        }
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('title_ar', 'like', "%{$search}%")->orWhere('title_en', 'like', "%{$search}%")->orWhereHas('course', fn ($c) => $c->where('name_ar', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'date', $from, $to);
        return $q->orderByDesc('date')->get();
    }

    private static function exams($search, $status, $from, $to, $courseId): Collection
    {
        $q = Exam::with('course');
        if ($status) {
            $q->where('status', $status);
        }
        if ($courseId) {
            $q->where('course_id', $courseId);
        }
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('title_ar', 'like', "%{$search}%")->orWhere('title_en', 'like', "%{$search}%")->orWhereHas('course', fn ($c) => $c->where('name_ar', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'date', $from, $to);
        return $q->orderByDesc('date')->get();
    }

    private static function examResults($search, $from, $to, $courseId): Collection
    {
        $q = ExamResult::with(['student', 'exam.course']);
        if ($courseId) {
            $q->whereHas('exam', fn ($e) => $e->where('course_id', $courseId));
        }
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->whereHas('student', fn ($s) => $s->where('name_ar', 'like', "%{$search}%"))
                    ->orWhereHas('exam', fn ($e) => $e->where('title_ar', 'like', "%{$search}%")->orWhere('title_en', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }

    private static function attendance($search, $status, $from, $to, $courseId): Collection
    {
        $q = Attendance::with(['student', 'session.course']);
        if ($status) {
            $q->where('status', $status);
        }
        if ($courseId) {
            $q->whereHas('session', fn ($s) => $s->where('course_id', $courseId));
        }
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->whereHas('student', fn ($s) => $s->where('name_ar', 'like', "%{$search}%"))
                    ->orWhereHas('session', fn ($s) => $s->where('title_ar', 'like', "%{$search}%")->orWhere('title_en', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }

    private static function certificates($search, $status, $from, $to, $courseId): Collection
    {
        $q = Certificate::with(['student', 'course']);
        if ($status) {
            $q->where('status', $status);
        }
        if ($courseId) {
            $q->where('course_id', $courseId);
        }
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('title_ar', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($s) => $s->where('name_ar', 'like', "%{$search}%"))
                    ->orWhereHas('course', fn ($c) => $c->where('name_ar', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'issued_at', $from, $to);
        return $q->latest('issued_at')->get();
    }

    private static function enrollmentRequests($search, $status, $from, $to, $courseId): Collection
    {
        $q = EnrollmentRequest::with(['student', 'course']);
        if ($status) {
            $q->where('status', $status);
        }
        if ($courseId) {
            $q->where('course_id', $courseId);
        }
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->whereHas('student', fn ($s) => $s->where('name_ar', 'like', "%{$search}%"))
                    ->orWhereHas('course', fn ($c) => $c->where('name_ar', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }

    private static function messages($search, $from, $to): Collection
    {
        $q = Message::with(['sender', 'receiver']);
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('body', 'like', "%{$search}%")
                    ->orWhereHas('sender', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('receiver', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }

    private static function supportTickets($search, $status, $from, $to): Collection
    {
        $q = SupportTicket::query();
        if ($status) {
            $q->where('status', $status);
        }
        if ($search) {
            $q->where(fn ($x) => $x->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('subject', 'like', "%{$search}%"));
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }

    private static function contactSubmissions($search, $from, $to): Collection
    {
        $q = ContactSubmission::query();
        if ($search) {
            $q->where(fn ($x) => $x->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('subject', 'like', "%{$search}%")->orWhere('message', 'like', "%{$search}%"));
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }

    private static function activities($search, $from, $to): Collection
    {
        $q = StudentActivity::with('student');
        if ($search) {
            $q->where(function ($x) use ($search) {
                $x->where('notes', 'like', "%{$search}%")->orWhere('activity_type', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($s) => $s->where('name_ar', 'like', "%{$search}%"));
            });
        }
        static::applyDate($q, 'date', $from, $to);
        return $q->latest('date')->get();
    }

    private static function juzProgress($search, $status, $from, $to): Collection
    {
        $q = JuzProgress::with('student');
        if ($status) {
            $q->where('status', $status);
        }
        if ($search) {
            $q->where(fn ($x) => $x->whereHas('student', fn ($s) => $s->where('name_ar', 'like', "%{$search}%")));
        }
        static::applyDate($q, 'updated_at', $from, $to);
        return $q->orderBy('student_id')->orderBy('juz_number')->get();
    }

    private static function users($search, $from, $to): Collection
    {
        $q = User::query();
        if ($search) {
            $q->where(fn ($x) => $x->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }
        static::applyDate($q, 'created_at', $from, $to);
        return $q->latest()->get();
    }
}