<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\EnrollmentRequest;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\JuzProgress;
use App\Models\Message;
use App\Models\PaymentMethod;
use App\Models\PaymentReceipt;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function __construct()
    {
        \Illuminate\Support\Facades\View::composer('layouts.student', function ($view) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (!$user) {
                $student = \App\Models\Student::with('courses')->first();
            } else {
                $student = \App\Models\Student::with('courses')->where('user_id', $user->id)->first()
                    ?? \App\Models\Student::with('courses')->where('email', $user->email)->first()
                    ?? \App\Models\Student::with('courses')->first();
            }
            if ($student) {
                $notifications = \App\Models\StudentNotification::where('student_id', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $unreadNotifications = $notifications->whereNull('read_at');
                $unreadMessagesCount = $user
                    ? \App\Models\Message::where('receiver_id', $user->id)->where('is_read', false)->count()
                    : 0;
                $view->with(compact('notifications', 'unreadNotifications', 'unreadMessagesCount'));
            }
        });
    }

    private function getStudent()
    {
        $user = Auth::user();
        if (!$user) {
            return Student::with('courses')->first();
        }
        return Student::with('courses')->where('user_id', $user->id)->first()
            ?? Student::with('courses')->where('email', $user->email)->first()
            ?? Student::with('courses')->where('phone', $user->phone ?? '')->first()
            ?? Student::with('courses')->first();
    }

    public function dashboard()
    {
        $student = $this->getStudent();
        $studentId = $student->id;

        $myCourses = $student->courses;

        $availableCourses = Course::where('is_active', true)
            ->whereDoesntHave('students', fn($q) => $q->where('student_id', $studentId))
            ->get();

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();

        $pendingEnrollments = EnrollmentRequest::with('course')
            ->where('student_id', $studentId)
            ->where('status', 'pending')
            ->get();

        $completedJuzCount = JuzProgress::where('student_id', $studentId)->where('status', 'completed')->count();
        $inProgressJuzCount = JuzProgress::where('student_id', $studentId)->where('status', 'in_progress')->count();

        $activities = StudentActivity::where('student_id', $studentId)->get();
        $todayMinutes = $activities->where('date', today())->sum('duration_minutes');
        $streakDays = $activities->groupBy('date')->count();
        $weekActivities = $activities->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);

        // Weekly chart: daily minutes for the last 7 days
        $weekLabels = [];
        $weekData = [];
        $weekStart = now()->startOfWeek(Carbon::SATURDAY);
        for ($i = 0; $i < 7; $i++) {
            $d = $weekStart->copy()->addDays($i);
            $weekLabels[] = $d->format('D');
            $weekData[] = $activities->where('date', $d->toDateString())->sum('duration_minutes');
        }

        $stats = (object) [
            'courses_count' => $myCourses->count(),
            'sessions_this_week' => Session::whereHas('course.students', fn($q) => $q->where('student_id', $studentId))
                ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'exams_upcoming' => Exam::whereHas('course.students', fn($q) => $q->where('student_id', $studentId))
                ->where('date', '>=', now())->count(),
            'certificates_count' => Certificate::where('student_id', $studentId)->count(),
            'streak_days' => $streakDays,
            'total_points' => ExamResult::where('student_id', $studentId)->sum('score') ?? 0,
        ];

        $upcomingSessions = Session::with('course')
            ->whereHas('course.students', fn($q) => $q->where('student_id', $studentId))
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(3)
            ->get();

        return view('pages.student.dashboard', compact(
            'student', 'myCourses', 'availableCourses', 'pendingEnrollments', 'stats',
            'completedJuzCount', 'inProgressJuzCount', 'todayMinutes', 'streakDays',
            'weekLabels', 'weekData', 'upcomingSessions', 'paymentMethods'
        ));
    }

    public function hifdhJourney()
    {
        $student = $this->getStudent();

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
        $inProgressJuz = $juzProgress->where('status', 'in_progress')->pluck('juz_number')->first();
        $reviewingJuz = $juzProgress->where('status', 'reviewing')->pluck('juz_number')->toArray();

        $activities = StudentActivity::where('student_id', $student->id)->orderBy('date', 'desc')->get();
        $activityDates = $activities->groupBy('date');
        $todayActivities = $activities->where('date', today());
        $streakDays = $activityDates->count();
        $todayMinutes = $activities->where('date', today())->sum('duration_minutes');
        $thisWeek = $activities->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        $activityCounts = $thisWeek->groupBy('activity_type')->map->count();
        $weeklyMinutes = $thisWeek->sum('duration_minutes');

        return view('pages.student.hifdh-journey', compact(
            'student', 'juzProgress', 'completedJuz', 'inProgressJuz', 'reviewingJuz',
            'activities', 'activityDates', 'todayActivities', 'streakDays', 'todayMinutes',
            'activityCounts', 'weeklyMinutes'
        ));
    }

    public function certificates()
    {
        $student = $this->getStudent();
        $certificates = Certificate::with('course')
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $stats = (object) [
            'total' => $certificates->count(),
            'delivered' => $certificates->where('status', 'delivered')->count(),
            'pending' => $certificates->where('status', 'pending')->count(),
        ];

        $activities = \App\Models\StudentActivity::where('student_id', $student->id)->get();
        $streakDays = $activities->groupBy('date')->count();
        $totalMinutes = $activities->sum('duration_minutes');
        $completedJuz = \App\Models\JuzProgress::where('student_id', $student->id)->where('status', 'completed')->count();

        return view('pages.student.certificates', compact(
            'student', 'certificates', 'stats', 'streakDays', 'totalMinutes', 'completedJuz'
        ));
    }

    public function exams()
    {
        $student = $this->getStudent();
        $exams = Exam::with('course')
            ->whereHas('course.students', fn($q) => $q->where('student_id', $student->id))
            ->where(fn($q) => $q->where('date', '>=', now()->toDateString())->orWhereNull('date'))
            ->orderBy('date', 'desc')
            ->get();
        $results = \App\Models\ExamResult::with('exam.course')
            ->where('student_id', $student->id)
            ->get()->keyBy('exam_id');
        $perfData = $results->values()->take(6)->map(function ($r) {
            return ['label' => $r->exam->course->name ?? $r->exam->title ?? 'اختبار', 'score' => $r->score];
        })->values();
        $stats = (object) [
            'total' => $exams->count(),
            'upcoming' => $exams->count(),
            'avg_score' => $results->avg('score') ?: 0,
        ];
        return view('pages.student.exams', compact('student', 'exams', 'stats', 'exams', 'results', 'perfData'));
    }

    public function settings()
    {
        $student = $this->getStudent();
        return view('pages.student.settings', compact('student'));
    }

    public function messages()
    {
        $student = $this->getStudent();
        $user = Auth::user();
        $messages = collect();
        if ($user) {
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->with('sender', 'receiver')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        $conversations = $this->buildConversations($messages, $user);
        $admins = User::where('role', 'admin')->get();
        $unreadTotal = $user
            ? Message::where('receiver_id', $user->id)->where('is_read', false)->count()
            : 0;
        return view('pages.student.messages', compact('student', 'messages', 'conversations', 'admins', 'user', 'unreadTotal'));
    }

    private function buildConversations($messages, $user)
    {
        if (!$user || $messages->count() === 0) {
            return collect();
        }

        return $messages
            ->groupBy(function ($m) use ($user) {
                return $m->sender_id === $user->id ? $m->receiver_id : $m->sender_id;
            })
            ->map(function ($msgs, $contactId) use ($user) {
                $msgs = $msgs->sortBy('created_at');
                $last = $msgs->last();
                $contact = $last->sender_id === $user->id ? $last->receiver : $last->sender;
                $unread = $msgs->where('is_read', false)->where('receiver_id', (int) $user->id)->count();
                return (object)[
                    'contact_id' => (int) $contactId,
                    'name' => $this->contactName($contact),
                    'last_msg' => \Illuminate\Support\Str::limit($last->body, 60),
                    'last_sender_me' => $last->sender_id === $user->id,
                    'time' => $last->created_at->diffForHumans(),
                    'datetime' => $last->created_at,
                    'unread' => $unread,
                ];
            })
            ->sortByDesc(fn($c) => $c->datetime && $c->datetime instanceof \Carbon\CarbonInterface ? $c->datetime : \Carbon\Carbon::parse($c->datetime))
            ->values();
    }

    private function contactName($contact)
    {
        if (!$contact) {
            return 'غير معروف';
        }
        $student = Student::where('user_id', $contact->id)->first();
        if ($student && $student->name_ar) {
            return $student->name_ar;
        }
        return $contact->name ?: 'غير معروف';
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
        return back()->with('success', __('messages.student_message_sent'));
    }

    public function updateSettings(Request $request)
    {
        $student = $this->getStudent();
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('students','email')->ignore($student->id), Rule::unique('users','email')->ignore(Auth::id())],
            'phone' => 'nullable|string|max:20',
            'level' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:woman,girl,boy',
            'age' => 'nullable|integer|min:3|max:100',
            'current_password' => 'nullable|current_password',
            'new_password' => 'nullable|string|min:8|confirmed',
            'notification_preferences' => 'nullable|array',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('name_ar', 'name_en', 'email', 'phone', 'level', 'gender', 'age');

        $prefs = $request->input('notification_preferences', []);
        $prefs = array_map(
            fn($v) => in_array($v, [true, 1, '1', 'on', 'true'], true),
            is_array($prefs) ? $prefs : []
        );
        $data['notification_preferences'] = array_merge(Student::DEFAULT_NOTIFICATION_PREFS, $prefs);

        if ($request->hasFile('avatar')) {
            if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                Storage::disk('public')->delete($student->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $student->update($data);
        $user = Auth::user();
        if ($user) {
            $user->update(['name' => $request->name_ar, 'email' => $request->email]);
            if ($request->filled('new_password')) {
                $user->update(['password' => Hash::make($request->new_password)]);
            }
        }
        return back()->with('success', __('messages.student_settings_saved'));
    }

    public function schedule()
    {
        $student = $this->getStudent();
        $sessions = Session::with('course')
            ->whereHas('course.students', fn($q) => $q->where('student_id', $student->id))
            ->orderBy('date')
            ->orderBy('time_from')
            ->get();
        $upcoming = $sessions->where('date', '>=', now()->toDateString())->take(3);
        $past = $sessions->where('date', '<', now()->toDateString())->take(5);
        $stats = (object) [
            'total' => $sessions->count(),
            'this_week' => $sessions->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'upcoming' => $upcoming->count(),
        ];
        return view('pages.student.schedule', compact('student', 'sessions', 'stats', 'upcoming', 'past'));
    }

    public function treasureMap()
    {
        $student = $this->getStudent();
        $juzCount = $student->courses->count();
        $totalJuz = 30;
        $pct = $totalJuz > 0 ? round(($juzCount / $totalJuz) * 100) : 0;
        $completedIds = $student->courses->pluck('id')->toArray();
        return view('pages.student.treasure-map', compact(
            'student',
            'juzCount',
            'totalJuz',
            'pct',
            'completedIds'
        ));
    }

    public function logActivity(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'activity_type' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'juz_number' => 'nullable|integer|between:1,30',
        ]);
        $student = $this->getStudent();

        $data = [
            'student_id' => $student->id,
            'date' => $request->date,
            'activity_type' => $request->activity_type,
            'duration_minutes' => $request->duration_minutes,
            'notes' => $request->notes,
            'juz_number' => $request->juz_number,
        ];
        $activity = StudentActivity::create($data);

        if ($request->juz_number && in_array($request->activity_type, ['حفظ', 'مراجعة'])) {
            $jp = JuzProgress::firstOrCreate(
                ['student_id' => $student->id, 'juz_number' => $request->juz_number],
                ['status' => 'not_started']
            );
            if ($request->activity_type === 'حفظ' && $jp->status === 'not_started') {
                $jp->update(['status' => 'in_progress', 'started_at' => $jp->started_at ?? $request->date]);
            } elseif ($request->activity_type === 'مراجعة') {
                $jp->update(['status' => 'reviewing', 'target_review_at' => null]);
            }
        }

        return response()->json(['status' => 'ok', 'activity' => $activity]);
    }

    public function deleteActivity(Request $request)
    {
        $request->validate(['id' => 'required|exists:student_activities,id']);
        $student = $this->getStudent();
        StudentActivity::where('id', $request->id)->where('student_id', $student->id)->delete();
        return response()->json(['status' => 'deleted']);
    }

    public function live()
    {
        $student = $this->getStudent();
        $sessions = Session::with('course')
            ->whereHas('course.students', fn($q) => $q->where('student_id', $student->id))
            ->orderBy('date', 'desc')
            ->get();
        $attendance = Attendance::where('student_id', $student->id)->get()->keyBy('session_id');

        $sessionQuizzes = $sessions->filter(fn($s) => $s->quiz_data && isset($s->quiz_data['question']))->mapWithKeys(function($s) {
            $q = $s->quiz_data;
            return [$s->id => [
                'id' => $s->id,
                'title' => $s->title ?? $s->course->name ?? 'اختبار',
                'question' => $q['question'] ?? 'ما هي الإجابة الصحيحة؟',
                'options' => $q['options'] ?? ['الخيار الأول', 'الخيار الثاني', 'الخيار الثالث', 'الخيار الرابع'],
                'correct' => $q['correct'] ?? 0,
            ]];
        });

        return view('pages.student.live', compact('student', 'sessions', 'attendance', 'sessionQuizzes'));
    }

    public function markAttendance(Request $request)
    {
        $request->validate(['session_id' => 'required|exists:course_sessions,id']);
        $student = $this->getStudent();
        if ($request->boolean('delete')) {
            Attendance::where('student_id', $student->id)->where('session_id', $request->session_id)->delete();
        } else {
            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'session_id' => $request->session_id],
                ['status' => 'present']
            );
        }
        return response()->json(['status' => 'ok']);
    }

    public function saveQuizResult(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:course_sessions,id',
            'score' => 'required|integer|min:0',
            'total' => 'required|integer|min:1',
            'answers' => 'nullable|array',
        ]);
        $student = $this->getStudent();
        Attendance::updateOrCreate(
            ['student_id' => $student->id, 'session_id' => $request->session_id],
            [
                'quiz_score' => $request->score,
                'quiz_total' => $request->total,
                'quiz_answers' => $request->answers,
            ]
        );
        return response()->json(['status' => 'ok']);
    }

    public function exam(Exam $exam)
    {
        $student = $this->getStudent();
        if (!$student->courses->contains($exam->course_id)) {
            return redirect()->route('student.dashboard')->with('error', 'غير مصرح لك بهذا الاختبار');
        }
        if ($exam->date && \Carbon\Carbon::parse($exam->date)->lt(today())) {
            return redirect()->route('student.exams')->with('error', 'انتهى هذا الاختبار ولم يعد متاحاً');
        }

        $exam->load('course', 'questions');
        $examsData = [[
            'id' => $exam->id,
            'title' => $exam->title,
            'course' => $exam->course->name ?? '',
            'questions' => $exam->questions->sortBy('order')->values()->map(function ($q) {
                $opts = is_array($q->options) ? array_values($q->options) : [];
                return [
                    'id' => $q->id,
                    'q' => $q->question ?? '',
                    'opts' => $opts,
                    'ans' => (int) ($q->correct_answer ?? 0),
                ];
            }),
        ]];
        return view('pages.student.exam', compact('student', 'exam', 'examsData'));
    }

    public function submitExam(Request $request, Exam $exam)
    {
        $request->validate([
            'answers' => 'required|array',
            'score' => 'required|numeric|min:0|max:100',
            'correct_count' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
        ]);
        $student = $this->getStudent();
        if ($exam->date && \Carbon\Carbon::parse($exam->date)->lt(today())) {
            return response()->json(['status' => 'error', 'message' => 'انتهى هذا الاختبار ولم يعد متاحاً'], 403);
        }
        $result = \App\Models\ExamResult::updateOrCreate(
            ['student_id' => $student->id, 'exam_id' => $exam->id],
            [
                'answers' => $request->answers,
                'score' => $request->score,
                'correct_count' => $request->correct_count,
                'total_questions' => $request->total_questions,
                'submitted_at' => now(),
            ]
        );
        $exam->update([
            'total_students' => \App\Models\ExamResult::where('exam_id', $exam->id)->distinct('student_id')->count('student_id'),
            'avg_score' => round(\App\Models\ExamResult::where('exam_id', $exam->id)->avg('score') ?? 0),
        ]);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'saved', 'result' => $result]);
        }
        return redirect()->route('student.exams')->with('success', 'تم إرسال الاختبار بنجاح');
    }

    public function courses()
    {
        $student = $this->getStudent();
        $availableCourses = Course::where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.student.courses', compact('student', 'availableCourses', 'paymentMethods'));
    }

    public function enrollRequest(Request $request, Course $course)
    {
        $student = $this->getStudent();

        if ($student->courses->contains($course->id)) {
            return back()->with('error', 'أنت مسجلة بالفعل في هذه الدورة');
        }

        $existing = EnrollmentRequest::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                return back()->with('info', 'طلب التسجيل في هذه الدورة قيد المراجعة بالفعل');
            }
            if ($existing->status === 'approved') {
                return back()->with('info', 'تم قبول طلبك بالفعل');
            }
            $existing->update(['status' => 'pending', 'notes' => null, 'approved_at' => null]);
            $enrollmentRequest = $existing;
        } else {
            $enrollmentRequest = EnrollmentRequest::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
            ]);
        }

        $isPaidCourse = !$course->is_free && $course->price > 0;

        if ($isPaidCourse) {
            $data = $request->validate([
                'payment_method_id' => 'required|exists:payment_methods,id',
                'receipt' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
                'receipt_note' => 'nullable|string|max:1000',
            ]);

            $receiptPath = $request->file('receipt')->store('receipts', 'public');

            PaymentReceipt::create([
                'enrollment_request_id' => $enrollmentRequest->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'payment_method_id' => $data['payment_method_id'],
                'receipt_path' => $receiptPath,
                'amount' => $course->price,
                'note' => $data['receipt_note'] ?? null,
                'status' => 'pending',
            ]);

            return back()->with('success', __('messages.enroll_success_paid'));
        }

        return back()->with('success', 'تم إرسال طلب التسجيل بنجاح، سنقوم بالتواصل معك قريبًا');
    }

    public function myCourses()
    {
        $student = $this->getStudent();
        $myCourses = $student->courses;
        return view('pages.student.my-courses', compact('student', 'myCourses'));
    }

    public function courseDetail(Course $course)
    {
        $student = $this->getStudent();

        if (!$student->courses->contains($course->id)) {
            return redirect()->route('student.dashboard')->with('error', 'أنت غير مسجلة في هذه الدورة');
        }

        $course->load([
            'lessons' => fn($q) => $q->orderBy('order'),
            'sessions' => fn($q) => $q->orderBy('date'),
            'exams',
            'certificates' => fn($q) => $q->where('student_id', $student->id),
        ]);

        $upcomingSessions = $course->sessions->where('date', '>=', now()->toDateString())->take(5);
        $pastSessions = $course->sessions->where('date', '<', now()->toDateString())->take(5);

        $examResults = ExamResult::where('student_id', $student->id)
            ->whereIn('exam_id', $course->exams->pluck('id'))
            ->get()
            ->keyBy('exam_id');

        $juzCount = JuzProgress::where('student_id', $student->id)->where('status', 'completed')->count();

        $activeTab = request('tab', 'overview');

        return view('pages.student.course-detail', compact(
            'student', 'course', 'upcomingSessions', 'pastSessions', 'examResults', 'juzCount', 'activeTab'
        ));
    }

    public function juzUpdate(Request $request, JuzProgress $juz)
    {
        $student = $this->getStudent();
        if ($juz->student_id !== $student->id) {
            return response()->json(['status' => 'error', 'message' => 'غير مصرح'], 403);
        }

        $data = $request->validate([
            'started_at' => 'nullable|date',
            'target_review_at' => 'nullable|date|after_or_equal:started_at',
            'status' => 'nullable|in:not_started,in_progress,reviewing,completed',
            'notes' => 'nullable|string|max:500',
        ]);

        $juz->update(array_filter($data));

        return response()->json(['status' => 'ok']);
    }

    public function lessonShow(CourseLesson $lesson)
    {
        $student = $this->getStudent();
        $course = $lesson->course;
        if (!$student->courses->contains($course->id)) {
            return redirect()->route('student.dashboard')->with('error', 'أنت غير مسجلة في هذه الدورة');
        }
        return view('pages.student.lesson-show', compact('student', 'course', 'lesson'));
    }

    public function certificateShow(Certificate $certificate)
    {
        $student = $this->getStudent();
        if ($certificate->student_id !== $student->id) {
            return redirect()->route('student.dashboard')->with('error', 'غير مصرح');
        }
        return view('pages.student.certificate-show', compact('student', 'certificate'));
    }

    public function markNotificationsRead()
    {
        $student = $this->getStudent();
        StudentNotification::where('student_id', $student->id)->whereNull('read_at')
            ->update(['read_at' => now()]);
        return redirect()->back();
    }
}
