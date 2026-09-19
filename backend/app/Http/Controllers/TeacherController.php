<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\EnrollmentRequest;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\JuzProgress;
use App\Models\Message;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function __construct()
    {
        \Illuminate\Support\Facades\View::composer('layouts.teacher', function ($view) {
            $user = Auth::user();
            $teacherName = $user->name ?? __('messages.teacher_female');
            $lastLoginText = $user && $user->last_login_at
                ? \Carbon\Carbon::parse($user->last_login_at)->format('Y/m/d g:i a')
                : __('messages.teacher_header_last_login_time');

            $notifications = collect();
            if ($user) {
                $courseIds = Course::where('user_id', $user->id)->pluck('id');

                if ($courseIds->isNotEmpty()) {
                    EnrollmentRequest::with(['student', 'course'])
                        ->whereIn('course_id', $courseIds)
                        ->where('status', 'pending')
                        ->latest()
                        ->limit(5)
                        ->get()
                        ->each(function ($req) use (&$notifications) {
                            $notifications->push((object) [
                                'type' => 'new_registration',
                                'title' => __('messages.teacher_notifications_type_new_reg'),
                                'body' => ($req->student->name ?? __('messages.teacher_students_student')) . ' — ' . ($req->course->name ?? ''),
                                'created_at' => $req->created_at,
                                'is_read' => false,
                                'url' => route('teacher.course-details', $req->course_id),
                            ]);
                        });

                    Session::with('course')
                        ->whereIn('course_id', $courseIds)
                        ->whereBetween('date', [now()->toDateString(), now()->addDay()->toDateString()])
                        ->orderBy('date')
                        ->orderBy('time_from')
                        ->limit(5)
                        ->get()
                        ->each(function ($s) use (&$notifications) {
                            $notifications->push((object) [
                                'type' => 'reminder',
                                'title' => __('messages.teacher_notifications_type_reminder'),
                                'body' => ($s->course->name ?? '') . ' — ' . $s->date,
                                'created_at' => $s->date,
                                'is_read' => false,
                                'url' => route('teacher.schedule'),
                            ]);
                        });
                }

                $latestUnread = Message::where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->with('sender')
                    ->orderByDesc('created_at')
                    ->first();
                if ($latestUnread) {
                    $unreadCount = Message::where('receiver_id', $user->id)->where('is_read', false)->count();
                    $fromId = $latestUnread->sender_id === $user->id ? $latestUnread->receiver_id : $latestUnread->sender_id;
                    $notifications->push((object) [
                        'type' => 'message',
                        'title' => __('messages.teacher_notifications_type_message'),
                        'body' => ($latestUnread->sender->name ?? __('messages.teacher_messages_unknown')) . ' — ' . $unreadCount . ' ' . __('messages.teacher_unread_messages'),
                        'created_at' => $latestUnread->created_at,
                        'is_read' => false,
                        'url' => route('teacher.messages', ['to' => $fromId]),
                    ]);
                }
            }

            $notifications = $notifications->sortByDesc('created_at')->values();
            $unreadNotificationsCount = $notifications->count();

            $view->with(compact('teacherName', 'lastLoginText', 'notifications', 'unreadNotificationsCount'));
        });
    }

    private function examStatus($exam): string
    {
        if (in_array($exam->status, ['current', 'ended'], true)) return $exam->status;
        return ($exam->date && \Carbon\Carbon::parse($exam->date)->lt(today())) ? 'ended' : 'current';
    }

    private function teacherCourseIds()
    {
        if (Auth::user()->role === 'admin') return null;
        return Course::where('user_id', Auth::id())->pluck('id');
    }

    private function scopeTeacherCourses($query)
    {
        $ids = $this->teacherCourseIds();
        return $ids === null ? $query : $query->whereIn('course_id', $ids);
    }

    private function teacherStudentsQuery()
    {
        $courseIds = $this->teacherCourseIds();
        if ($courseIds === null) return Student::query();
        return Student::whereHas('courses', fn ($q) => $q->whereIn('courses.id', $courseIds));
    }

    public function dashboard()
    {
        $courseIds = $this->teacherCourseIds();
        $scoped = fn ($q) => $courseIds === null ? $q : $q->whereIn('course_id', $courseIds);

        $stats = (object) [
            'courses' => $courseIds === null ? Course::count() : count($courseIds),
            'students' => $this->teacherStudentsQuery()->count(),
            'sessions_week' => $scoped(Session::query())->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'exams_upcoming' => $scoped(Exam::query())->where('date', '>=', now())->count(),
        ];

        $days = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        $chartData = [];
        foreach (range(0, 6) as $i) {
            $day = now()->startOfWeek()->addDays($i)->format('Y-m-d');
            $chartData[] = $scoped(Session::query())->whereDate('date', $day)->count();
        }
        $chartLabels = $days;

        $upcomingSessions = $scoped(Session::with('course'))
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time_from')
            ->limit(5)
            ->get();

        $students = $this->teacherStudentsQuery()
            ->with(['courses', 'juzProgress'])
            ->where('status', 'active')
            ->limit(5)
            ->get()
            ->map(function ($s) {
                $attPct = $this->calcAttendanceRate($s->id);
                $avgScore = $this->calcAvgScore($s->id);
                return (object) [
                    'name' => $s->name,
                    'course' => $s->courses->first()->name ?? '—',
                    'attendance' => $attPct . '%',
                    'avg_score' => $avgScore . '%',
                    'status' => $this->getStatusLabel($avgScore),
                ];
            });

        $recentActivities = collect();
        $recentSessions = $scoped(Session::query())->where('created_at', '>=', now()->subDays(7))
            ->latest()->limit(3)->with('course')->get();
        foreach ($recentSessions as $s) {
            $recentActivities->push((object) [
                'icon' => 'video',
                'color' => '#0F6D80',
                'text' => "أضيفت جلسة {$s->title} لـ {$s->course->name}",
                'time' => $s->created_at->diffForHumans(),
            ]);
        }
        $recentExams = $scoped(Exam::query())->where('created_at', '>=', now()->subDays(7))
            ->latest()->limit(3)->with('course')->get();
        foreach ($recentExams as $e) {
            $recentActivities->push((object) [
                'icon' => 'pen-to-square',
                'color' => '#dc3545',
                'text' => "أضيف امتحان {$e->title} لـ {$e->course->name}",
                'time' => $e->created_at->diffForHumans(),
            ]);
        }
        $recentStudents = $this->teacherStudentsQuery()
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()->limit(3)->get();
        foreach ($recentStudents as $st) {
            $recentActivities->push((object) [
                'icon' => 'user-plus',
                'color' => '#198754',
                'text' => "انضمت {$st->name} جديدة",
                'time' => $st->created_at->diffForHumans(),
            ]);
        }
        $recentActivities = $recentActivities->sortByDesc(function ($a) {
            return $a->time;
        })->take(5);

        $user = Auth::user();

        return view('pages.teacher.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'upcomingSessions',
            'students',
            'recentActivities',
            'user'
        ));
    }

    private function calcAttendanceRate($studentId)
    {
        $total = Attendance::where('student_id', $studentId)->count();
        $present = Attendance::where('student_id', $studentId)->where('status', 'present')->count();
        return $total > 0 ? round(($present / $total) * 100) : 0;
    }

    private function calcAvgScore($studentId)
    {
        return round(ExamResult::where('student_id', $studentId)->avg('score') ?? 0);
    }

    public function courses()
    {
        $courses = Course::withCount('students')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $totalCourses = $courses->count();
        $activeCourses = $courses->where('is_active', true)->count();
        $completedCourses = $totalCourses - $activeCourses;

        return view('pages.teacher.courses', compact(
            'courses',
            'totalCourses',
            'activeCourses',
            'completedCourses'
        ));
    }

    public function students()
    {
        $courseIds = Course::where('user_id', Auth::id())->pluck('id');
        $students = Student::with(['courses', 'juzProgress'])
            ->whereHas('courses', fn ($q) => $q->whereIn('courses.id', $courseIds))
            ->withCount(['courses as courses_count'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($s) use ($courseIds) {
                $s->attendance_rate = $this->calcAttendanceRate($s->id);
                $s->exams_avg_score = $this->calcAvgScore($s->id);
                $s->sessions_count = Attendance::where('student_id', $s->id)->count();
                $s->session_attended = Attendance::where('student_id', $s->id)->where('status', 'present')->count();
                $s->completed_juz = $s->juzProgress->where('status', 'completed')->count();
                $s->in_progress_juz = $s->juzProgress->where('status', 'in_progress')->count();
                $s->reviewing_juz = $s->juzProgress->where('status', 'reviewing')->count();
                $herCourses = $s->courses->whereIn('id', $courseIds);
                $s->teacher_courses = $herCourses->pluck('name')->join('، ');
                $s->course_list = $herCourses->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values();
                return $s;
            });
        $stats = [
            'total' => $students->count(),
            'active' => $students->where('status', 'active')->count(),
            'new_this_month' => $students->where('created_at', '>=', now()->startOfMonth())->count(),
            'completed' => $students->where('status', 'completed')->count(),
        ];
        $courses = Course::where('user_id', Auth::id())->where('is_active', true)->get();
        $enrollableStudents = Student::whereDoesntHave('courses', fn ($q) => $q->whereIn('courses.id', $courseIds))
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'phone']);
        return view('pages.teacher.students', compact('students', 'stats', 'courses', 'enrollableStudents'));
    }

    public function storeEnrollStudent(Request $request)
    {
        $teacherCourseIds = Course::where('user_id', Auth::id())->pluck('id');
        if ($teacherCourseIds->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => __('messages.teacher_students_no_courses')], 422);
        }
        $data = $request->validate([
            'course_id' => ['required', 'integer', 'in:' . $teacherCourseIds->implode(',')],
            'student_id' => ['nullable', 'integer', 'exists:students,id'],
            'name_ar' => ['nullable', 'string', 'max:255', 'required_without:student_id'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
        $student = !empty($data['student_id'])
            ? Student::findOrFail($data['student_id'])
            : Student::create([
                'name_ar' => $data['name_ar'],
                'phone' => $data['phone'] ?? null,
                'status' => 'active',
                'joined_at' => now(),
            ]);
        $course = Course::find($data['course_id']);
        if (!$course->students()->whereKey($student->id)->exists()) {
            $course->students()->attach($student->id, ['enrolled_at' => now()]);
        }
        return response()->json(['status' => 'enrolled', 'student_id' => $student->id]);
    }

    public function sessions()
    {
        $sessions = $this->scopeTeacherCourses(Session::query())->with('course')->orderBy('date', 'desc')->orderBy('time_from', 'desc')->get();
        $stats = [
            'today' => $sessions->where('date', now()->toDateString())->count(),
            'this_week' => $sessions->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'completed' => $sessions->where('status', 'completed')->count(),
            'upcoming' => $sessions->where('status', 'upcoming')->count(),
            'cancelled' => $sessions->where('status', 'cancelled')->count(),
        ];
        return view('pages.teacher.sessions', compact('sessions', 'stats'));
    }

    public function schedule(Request $request)
    {
        $weekStart = Carbon::parse($request->input('week', now()->toDateString()))
            ->startOfDay()
            ->startOfWeek(Carbon::SATURDAY);
        $weekEnd = $weekStart->copy()->addDays(6);
        $sessions = $this->scopeTeacherCourses(Session::query())->with('course')->where('date', '>=', now()->subDays(3))->orderBy('date')->orderBy('time_from')->get();
        $totalSessions = $this->scopeTeacherCourses(Session::query())->count();
        $thisWeek = $this->scopeTeacherCourses(Session::query())->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])->count();
        $upcomingCount = $this->scopeTeacherCourses(Session::query())->where('date', '>=', now()->toDateString())->count();
        $weekDayNames = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        $weekSessions = [];
        foreach (range(0, 6) as $i) {
            $day = $weekStart->copy()->addDays($i)->format('Y-m-d');
            $weekSessions[$day] = $this->scopeTeacherCourses(Session::query())->with('course')
                ->whereDate('date', $day)
                ->orderBy('time_from')
                ->get();
        }
        $currentWeekStart = $weekStart->format('Y-m-d');
        $currentWeekEnd = $weekEnd->format('Y-m-d');
        $prevWeekStart = $weekStart->copy()->subDays(7)->format('Y-m-d');
        $nextWeekStart = $weekStart->copy()->addDays(7)->format('Y-m-d');
        return view('pages.teacher.schedule', compact(
            'sessions',
            'totalSessions',
            'thisWeek',
            'upcomingCount',
            'weekDayNames',
            'weekSessions',
            'currentWeekStart',
            'currentWeekEnd',
            'prevWeekStart',
            'nextWeekStart'
        ));
    }

    public function messages()
    {
        $user = Auth::user();
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with('sender', 'receiver')
            ->orderBy('created_at', 'desc')
            ->get();
        $admins = User::where('role', 'admin')->orderBy('name')->get();
        $students = $this->teacherStudentsQuery()
            ->whereHas('user')
            ->with('user')
            ->get()
            ->sortBy('name_ar');
        return view('pages.teacher.messages', compact('messages', 'user', 'students', 'admins'));
    }

    public function fetchMessages(Request $request, $contactId)
    {
        $userId = Auth::id();
        $messages = Message::where(function ($q) use ($userId, $contactId) {
            $q->where('sender_id', $userId)->where('receiver_id', $contactId);
        })->orWhere(function ($q) use ($userId, $contactId) {
            $q->where('sender_id', $contactId)->where('receiver_id', $userId);
        })->with('sender', 'receiver')->orderBy('created_at', 'asc')->get();
        Message::where('sender_id', $contactId)->where('receiver_id', $userId)->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string',
        ]);
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'sent', 'message' => $message->load('sender')]);
        }
        return back()->with('success', __('messages.teacher_messages_success'));
    }

    public function exams()
    {
        $exams = $this->scopeTeacherCourses(Exam::query())->with('course')->orderBy('date', 'desc')->get();
        $stats = [
            'total' => $exams->count(),
            'current' => $exams->filter(fn ($e) => $this->examStatus($e) === 'current')->count(),
            'ended' => $exams->filter(fn ($e) => $this->examStatus($e) === 'ended')->count(),
        ];
        return view('pages.teacher.exams', compact('exams', 'stats'));
    }

    public function certificates()
    {
        $certificates = $this->scopeTeacherCourses(Certificate::query())->with('student.user', 'course')->orderBy('created_at', 'desc')->get();
        $stats = [
            'issued' => $certificates->whereIn('status', ['issued', 'delivered', 'active'])->count(),
            'pending' => $certificates->where('status', 'pending')->count(),
            'cancelled' => $certificates->where('status', 'cancelled')->count(),
        ];
        $courseIds = $this->teacherCourseIds();
        $courses = ($courseIds === null ? Course::query() : Course::whereIn('id', $courseIds))
            ->orderBy('name_ar')->get(['id', 'name_ar']);
        $students = $this->teacherStudentsQuery()
            ->with('courses:id')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name_ar,
                'course_ids' => $s->courses->pluck('id')->all(),
            ])
            ->sortBy('name')
            ->values();
        return view('pages.teacher.certificates', compact('certificates', 'stats', 'courses', 'students'));
    }

    public function storeCertificate(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id', function ($attribute, $value, $fail) {
                if (auth()->user()->role === 'admin') return;
                if (!Course::where('id', $value)->where('user_id', auth()->id())->exists()) {
                    $fail('لا يمكنك إصدار شهادة لدورة ليست ملكك');
                }
            }],
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'title_ar' => 'required|string|max:255',
            'issued_at' => 'nullable|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('certificates', 'public');
        }

        $createdCount = 0;
        foreach ($data['student_ids'] as $sid) {
            Certificate::create([
                'student_id' => $sid,
                'course_id' => $data['course_id'],
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_ar'],
                'issued_at' => $data['issued_at'] ?? now()->toDateString(),
                'status' => 'issued',
                'is_published' => true,
                'image' => $imagePath,
            ]);
            $createdCount++;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'created', 'count' => $createdCount, 'message' => __('messages.teacher_certificates_created_success')]);
        }
        return redirect()->route('teacher.certificates')->with('success', __('messages.teacher_certificates_created_success'));
    }

    private function compileReports()
    {
        $courseIds = Course::where('user_id', Auth::id())->pluck('id');
        $students = Student::with('courses')
            ->whereHas('courses', fn ($q) => $q->whereIn('courses.id', $courseIds))
            ->get()
            ->map(function ($s) use ($courseIds) {
                $s->teacher_course_titles = $s->courses->whereIn('id', $courseIds)->pluck('name');
                return $s;
            });
        $totalSessions = Session::whereIn('course_id', $courseIds)->count();
        $sessionIds = Session::whereIn('course_id', $courseIds)->pluck('id');
        $stats = [
            'students' => $students->count(),
            'courses' => $courseIds->count(),
            'sessions' => $totalSessions,
            'exams' => Exam::whereIn('course_id', $courseIds)->count(),
            'completion_rate' => $totalSessions > 0
                ? round((Session::whereIn('course_id', $courseIds)->where('status', 'completed')->count() / $totalSessions) * 100) . '%'
                : '0%',
        ];
        $sessionStats = collect([]);
        $arMonths = [1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل', 5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس', 9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'];
        foreach (range(0, 4) as $i) {
            $month = now()->subMonths($i);
            $total = Session::whereIn('course_id', $courseIds)->whereYear('date', $month->year)->whereMonth('date', $month->month)->count();
            $attended = Attendance::whereIn('session_id', $sessionIds)->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)
                ->where('status', 'present')->count();
            $sessionStats->push((object) [
                'month' => $arMonths[$month->month],
                'total' => $total,
                'attended' => $attended,
                'attendance_rate' => $total > 0 ? round(($attended / $total) * 100) : 0,
            ]);
        }
        $examStats = Exam::whereIn('course_id', $courseIds)->with('course')->get()->map(function ($e) {
            $results = ExamResult::where('exam_id', $e->id);
            return (object) [
                'exam_title' => $e->title,
                'title' => $e->title,
                'avg_score' => round($results->avg('score') ?? 0),
                'max_score' => round($results->max('score') ?? 0),
                'min_score' => round($results->min('score') ?? 0),
            ];
        });
        return compact('stats', 'students', 'sessionStats', 'examStats');
    }

    public function reports()
    {
        return view('pages.teacher.reports', $this->compileReports());
    }

    public function reportsPrint()
    {
        return view('pages.teacher.reports-print', $this->compileReports());
    }

    public function settings()
    {
        $user = Auth::user();
        return view('pages.teacher.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);
        $user = Auth::user();
        $user->update($request->only('name', 'email'));
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }
        if ($request->ajax()) {
            return response()->json(['status' => 'saved']);
        }
        return back()->with('success', 'تم حفظ الإعدادات');
    }

    public function notifications()
    {
        return view('pages.teacher.notifications');
    }

    public function markAllNotificationsRead()
    {
        Message::where('receiver_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['status' => 'ok']);
    }

    public function studentProfile($id = null)
    {
        $student = $id ? Student::with(['courses', 'juzProgress'])->findOrFail($id) : Student::with(['courses', 'juzProgress'])->first();

        // Ensure juz progress exists for all 30 juz
        $existing = JuzProgress::where('student_id', $student->id)->pluck('juz_number')->toArray();
        $missing = array_diff(range(1, 30), $existing);
        if (!empty($missing)) {
            $inserts = [];
            foreach ($missing as $juz) {
                $inserts[] = ['student_id' => $student->id, 'juz_number' => $juz, 'status' => 'not_started', 'created_at' => now(), 'updated_at' => now()];
            }
            JuzProgress::insert($inserts);
        }

        $juzProgress = JuzProgress::where('student_id', $student->id)->orderBy('juz_number')->get();
        $completedJuz = $juzProgress->where('status', 'completed')->count();
        $inProgressJuz = $juzProgress->where('status', 'in_progress')->pluck('juz_number');

        // Stats
        $totalSessions = \App\Models\Attendance::where('student_id', $student->id)->count();
        $attendedSessions = \App\Models\Attendance::where('student_id', $student->id)->where('status', 'present')->count();
        $attendanceRate = $totalSessions > 0 ? round(($attendedSessions / $totalSessions) * 100) : 0;

        $stats = [
            'completed_juz' => $completedJuz,
            'in_progress_juz' => $inProgressJuz->count(),
            'total_sessions' => $totalSessions,
            'attendance_rate' => $attendanceRate,
        ];

        $examResults = \App\Models\ExamResult::with('exam.course')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $recentSessions = \App\Models\Attendance::with('session.course')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentActivities = StudentActivity::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        return view('pages.teacher.student-profile', compact(
            'student', 'juzProgress', 'stats', 'examResults', 'recentSessions', 'recentActivities'
        ));
    }

    public function juzUpdate(Request $request, $studentId, $juzNumber)
    {
        $request->validate(['status' => 'required|in:not_started,in_progress,completed,reviewing']);

        $student = Student::findOrFail($studentId);

        JuzProgress::updateOrCreate(
            ['student_id' => $student->id, 'juz_number' => $juzNumber],
            [
                'status' => $request->status,
                'completed_at' => $request->status === 'completed' ? now() : null,
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'updated', 'juz_number' => (int) $juzNumber]);
        }
        return back()->with('success', 'تم تحديث حالة الجزء ' . $juzNumber);
    }

    public function examDetails($id = null)
    {
        $query = $this->scopeTeacherCourses(Exam::query())->with('course', 'questions');
        $exam = $id ? $query->findOrFail($id) : $query->orderBy('date', 'desc')->first();
        $questions = $exam ? $exam->questions->sortBy('order')->values() : collect();
        return view('pages.teacher.exam-details', compact('exam', 'questions'));
    }

    public function examResults($id = null)
    {
        $query = $this->scopeTeacherCourses(Exam::query())->with('course');
        $exam = $id ? $query->findOrFail($id) : $query->orderBy('date', 'desc')->first();
        $results = $exam
            ? ExamResult::with('student')->where('exam_id', $exam->id)->orderByDesc('score')->get()
            : collect();
        $corrected = $results->whereNotNull('score')->count();
        $enrolled = $exam && $exam->course ? $exam->course->students()->count() : 0;
        $stats = [
            'total' => $results->count(),
            'corrected' => $corrected,
            'pending' => max(0, $enrolled - $corrected),
            'students' => $results->pluck('student_id')->unique()->count(),
        ];
        return view('pages.teacher.exam-results', compact('exam', 'results', 'stats'));
    }

    public function examResultsPrint($id)
    {
        $exam = $this->scopeTeacherCourses(Exam::query())->with('course')->findOrFail($id);
        $results = ExamResult::with('student')->where('exam_id', $exam->id)->orderByDesc('score')->get();
        return view('pages.teacher.exam-results-print', compact('exam', 'results'));
    }

    public function storeQuestion(Request $request)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'question_ar' => 'required|string',
            'question_en' => 'nullable|string',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|integer|min:0',
        ]);
        $data['question_en'] = ($data['question_en'] ?? '') ?: $data['question_ar'];
        $data['order'] = ExamQuestion::where('exam_id', $data['exam_id'])->max('order') + 1;
        $question = ExamQuestion::create($data);
        return response()->json(['status' => 'created', 'question' => $question]);
    }
    public function courseDetails($id = null)
    {
        $query = Course::with(['lessons', 'sessions', 'exams', 'students', 'certificates.student'])
            ->withCount(['students', 'sessions', 'exams', 'certificates']);
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
        $course = $id ? $query->findOrFail($id) : ($query->orderByDesc('created_at')->first() ?? abort(404));
        $enrollableStudents = Student::whereDoesntHave('courses', fn ($q) => $q->where('courses.id', $course->id))
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'phone']);
        return view('pages.teacher.course-details', compact('course', 'enrollableStudents'));
    }

    public function detachStudent(Course $course, Student $student)
    {
        if (Auth::user()->role !== 'admin' && $course->user_id !== Auth::id()) {
            abort(403);
        }
        $course->students()->detach($student->id);
        return response()->json(['status' => 'detached']);
    }

    // ========== Course CRUD ==========
    public function storeCourse(Request $request)
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'desc_ar' => 'nullable|string',
            'level' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'sessions_per_week' => 'nullable|integer|min:1|max:7',
            'is_active' => 'boolean',
            'instructor_ar' => 'nullable|string|max:255',
            'audience' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['user_id'] = Auth::id();
        $data = $this->fillCourseDefaults($data);
        $course = Course::create($data);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'created', 'course' => $course, 'message' => 'تم إضافة الدورة بنجاح']);
        }
        return redirect()->route('teacher.courses')->with('success', 'تم إضافة الدورة بنجاح');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'desc_ar' => 'nullable|string',
            'level' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'nullable|numeric|min:0',
            'sessions_per_week' => 'nullable|integer|min:1|max:7',
            'is_active' => 'boolean',
            'instructor_ar' => 'nullable|string|max:255',
            'audience' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
        ]);
        $data['is_active'] = $request->boolean('is_active', $course->is_active);
        $course->update($data);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'saved', 'course' => $course, 'message' => 'تم تحديث الدورة بنجاح']);
        }
        return redirect()->route('teacher.courses')->with('success', 'تم تحديث الدورة بنجاح');
    }

    public function deleteCourse(Request $request, Course $course)
    {
        $course->delete();
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'deleted', 'message' => 'تم حذف الدورة بنجاح']);
        }
        return redirect()->route('teacher.courses')->with('success', 'تم حذف الدورة بنجاح');
    }

    private function fillCourseDefaults(array $data): array
    {
        if (empty($data['name_en'])) {
            $data['name_en'] = $data['name_ar'];
        }
        if (empty($data['slug'])) {
            $base = Str::slug($data['name_en'] ?? $data['name_ar']);
            if ($base === '') {
                $base = 'course';
            }
            $slug = $base;
            $i = 1;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $base . '-' . ++$i;
            }
            $data['slug'] = $slug;
        }
        if (empty($data['category_id'])) {
            $data['category_id'] = Category::query()->value('id');
        }
        return $data;
    }

    // ========== Session CRUD ==========
    public function storeSession(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time_from' => 'required|string|max:10',
            'time_to' => 'nullable|string|max:10',
            'status' => 'nullable|string|in:upcoming,completed,cancelled,in_progress',
            'stream_url' => 'nullable|string|max:500',
        ]);
        $session = Session::create($data);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'created', 'session' => $session, 'message' => 'تم إضافة الجلسة بنجاح']);
        }
        return redirect()->route('teacher.sessions')->with('success', 'تم إضافة الجلسة بنجاح');
    }

    public function updateSession(Request $request, Session $session)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time_from' => 'required|string|max:10',
            'time_to' => 'nullable|string|max:10',
            'status' => 'nullable|string|in:upcoming,completed,cancelled,in_progress',
            'stream_url' => 'nullable|string|max:500',
        ]);
        $session->update($data);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'saved', 'session' => $session, 'message' => 'تم تحديث الجلسة بنجاح']);
        }
        return redirect()->route('teacher.sessions')->with('success', 'تم تحديث الجلسة بنجاح');
    }

    public function deleteSession(Request $request, Session $session)
    {
        $session->delete();
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'deleted', 'message' => 'تم حذف الجلسة بنجاح']);
        }
        return redirect()->route('teacher.sessions')->with('success', 'تم حذف الجلسة بنجاح');
    }

    // ========== Lesson CRUD ==========
    public function storeLesson(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:1',
            'link' => 'nullable|string|max:500',
        ]);
        $data['name_en'] = $data['name_en'] ?? $data['name_ar'];
        $data['order'] = $data['order'] ?? CourseLesson::where('course_id', $data['course_id'])->max('order') + 1;
        $lesson = CourseLesson::create($data);
        return response()->json(['status' => 'created', 'lesson' => $lesson, 'message' => 'تم إضافة الدرس بنجاح']);
    }

    public function updateLesson(Request $request, CourseLesson $lesson)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:1',
            'link' => 'nullable|string|max:500',
        ]);
        $data['name_en'] = $data['name_en'] ?? $data['name_ar'];
        $lesson->update($data);
        return response()->json(['status' => 'saved', 'lesson' => $lesson, 'message' => 'تم تحديث الدرس بنجاح']);
    }

    public function deleteLesson(Request $request, CourseLesson $lesson)
    {
        $lesson->delete();
        return response()->json(['status' => 'deleted', 'message' => 'تم حذف الدرس بنجاح']);
    }

    // ========== Exam CRUD ==========
    public function storeExam(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id', function ($attribute, $value, $fail) {
                if (auth()->user()->role === 'admin') return;
                if (!Course::where('id', $value)->where('user_id', auth()->id())->exists()) {
                    $fail('لا يمكنك إضافة امتحان لدورة ليست ملكك');
                }
            }],
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);
        $data['total_students'] = 0;
        $data['status'] = \Carbon\Carbon::parse($data['date'])->lt(today()) ? 'ended' : 'current';
        $exam = Exam::create($data);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'created', 'exam' => $exam, 'message' => 'تم إضافة الامتحان بنجاح']);
        }
        return redirect()->route('teacher.exams')->with('success', 'تم إضافة الامتحان بنجاح');
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id', function ($attribute, $value, $fail) {
                if (auth()->user()->role === 'admin') return;
                if (!Course::where('id', $value)->where('user_id', auth()->id())->exists()) {
                    $fail('لا يمكنك ربط الامتحان بدورة ليست ملكك');
                }
            }],
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);
        $data['total_students'] = \App\Models\ExamResult::where('exam_id', $exam->id)->distinct('student_id')->count('student_id');
        $exam->update($data);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'saved', 'exam' => $exam, 'message' => 'تم تحديث الامتحان بنجاح']);
        }
        return redirect()->route('teacher.exams')->with('success', 'تم تحديث الامتحان بنجاح');
    }

    public function toggleExamStatus(Request $request, Exam $exam)
    {
        if (auth()->user()->role !== 'admin' && !Course::where('id', $exam->course_id)->where('user_id', auth()->id())->exists()) {
            return response()->json(['status' => 'error', 'message' => 'غير مصرح لك بتعديل هذا الامتحان'], 403);
        }
        $current = in_array($exam->status, ['current', 'ended'], true)
            ? $exam->status
            : (($exam->date && \Carbon\Carbon::parse($exam->date)->lt(today())) ? 'ended' : 'current');
        $exam->update(['status' => $current === 'current' ? 'ended' : 'current']);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'toggled', 'exam_status' => $exam->status]);
        }
        return back();
    }

    public function deleteExam(Request $request, Exam $exam)
    {        $exam->delete();
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'deleted', 'message' => 'تم حذف الامتحان بنجاح']);
        }
        return redirect()->route('teacher.exams')->with('success', 'تم حذف الامتحان بنجاح');
    }

    private function getStatusLabel($avg)
    {
        if ($avg >= 90) return 'ممتاز';
        if ($avg >= 80) return 'جيد جداً';
        if ($avg >= 70) return 'جيد';
        return 'متوسط';
    }
}
