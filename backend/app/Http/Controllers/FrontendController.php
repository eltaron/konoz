<?php

namespace App\Http\Controllers;

use App\Mail\EnrollmentConfirmationMail;
use App\Models\EnrollmentRequest;
use App\Models\Student;
use App\Models\Category;
use App\Models\ContactSubmission;
use App\Models\Course;
use App\Models\HeroSlider;
use App\Models\Champion;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\Certificate;
use App\Models\SiteContent;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    // Public pages
    public function home()
    {
        $slides = HeroSlider::where('is_published', true)->orderBy('sort_order')->get();
        $testimonials = Testimonial::where('is_published', true)->latest()->get();
        $champions = Champion::where('is_active', true)->orderBy('sort_order')->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();
        $latestCourse = Course::where('is_active', true)->orderBy('created_at', 'desc')->first();
        $certificates = Certificate::published()->whereNotNull('image')->latest()->get();
        return view('pages.public.index', compact('slides', 'testimonials', 'champions', 'paymentMethods', 'latestCourse', 'certificates'));
    }
    public function blog()
    {
        $posts = Post::where('is_published', true)->orderBy('created_at', 'desc')->get();
        return view('pages.public.blog', compact('posts'));
    }
    public function blogPost(Request $request)
    {
        $post = Post::where('slug', $request->slug)->where('is_published', true)->firstOrFail();
        return view('pages.public.blog-post', compact('post'));
    }
    public function verifyCertificate(Request $request, string $code)
    {
        $certificate = Certificate::with('student', 'course')
            ->where('verification_code', $code)
            ->where('is_published', true)
            ->first();
        return view('pages.public.certificate-verify', ['certificate' => $certificate]);
    }
    public function terms()
    {
        return view('pages.public.terms');
    }
    public function privacy()
    {
        return view('pages.public.privacy');
    }
    public function departments()
    {
        $courses = Course::where('is_active', true)->with('category')->get();
        $categories = Category::all();
        $locale = App::getLocale();
        $coursesJson = $courses->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'desc' => $c->desc,
            'icon' => $c->icon ?: 'fa-book-open',
            'category' => $c->category?->slug ?: 'quran',
            'img' => $c->image ? asset('storage/' . $c->image) : asset('images/logo.png'),
            'students' => $c->students_count ?: '0',
            'price' => (float)$c->price,
            'is_free' => (bool)$c->is_free,
            'level' => $c->level ?: 'beginner',
            'audience' => $c->audience ?: 'all',
        ])->toJson();
        $categoriesJson = $categories->map(fn($cat) => [
            'slug' => $cat->slug,
            'name' => $cat->name,
            'icon' => 'fa-' . trim(str_replace(['fas ', 'far ', 'fa-'], '', (string) $cat->icon ?: 'fa-book-open'), ' '),
            'count' => $cat->courses()->where('is_active', true)->count(),
        ])->toJson();
        $deptLang = [
            'free_badge' => __('messages.dept_free_badge'),
            'free' => __('messages.dept_free'),
            'monthly' => __('messages.dept_monthly'),
            'discover' => __('messages.dept_discover_more'),
            'enroll' => __('messages.dept_enroll'),
            'all' => __('messages.dept_all'),
            'courses' => __('messages.dept_courses'),
            'departments_label' => __('messages.dept_departments_label'),
            'swal_title' => __('messages.dept_swal_title'),
            'swal_text' => __('messages.dept_swal_text'),
            'swal_btn' => __('messages.dept_swal_btn'),
            'level_beginner' => __('messages.dept_beginner'),
            'level_intermediate' => __('messages.dept_intermediate'),
            'level_advanced' => __('messages.dept_advanced'),
        ];
        return view('pages.public.departments', compact('coursesJson', 'categories', 'categoriesJson', 'deptLang'));
    }
    public function courseDetails(Request $request)
    {
        $categories = Category::all();
        $courseData = null;
        if ($request->id) {
            $course = Course::with(['lessons', 'freeSessions'])->where('is_active', true)->find($request->id);
            if ($course) {
                $courseData = [
                    'id' => $course->id,
                    'name' => $course->name,
                    'desc' => $course->desc,
                    'icon' => $course->icon ?: 'fa-book-open',
                    'category' => $course->category?->slug ?: 'quran',
                    'img' => $course->image ? asset('storage/' . $course->image) : asset('images/logo.png'),
                    'students' => $course->students_count ?: '0',
                    'price' => (float)$course->price,
                    'is_free' => (bool)$course->is_free,
                    'level' => $course->level ?: 'beginner',
                    'instructor' => $course->instructor_name,
                    'instructorBio' => $course->instructor_bio,
                    'duration' => $course->duration,
                    'sessions' => $course->sessions_per_week ? ($course->sessions_per_week . ' حصص/أسبوع') : null,
                    'lessons' => $course->lessons->pluck('name')->toArray(),
                    'freeSessions' => $course->freeSessions->map(fn($fs) => [
                        'title' => $fs->title,
                        'duration' => $fs->duration,
                        'videoUrl' => $fs->video_url ?: '#',
                        'description' => $fs->description,
                    ]),
                ];
            }
        }
        $student = null;
        if (Auth::check()) {
            $student = Auth::user()->student;
            if (!$student) {
                $student = new \App\Models\Student(['name_ar' => Auth::user()->name, 'email' => Auth::user()->email]);
            }
        }
        return view('pages.public.course-details', compact('categories', 'courseData', 'student'));
    }
    public function educationalSupport()
    {
        $contents = SiteContent::where('group', 'linguistics')->get()->keyBy('key');
        return view('pages.public.educational-support', compact('contents'));
    }
    public function parentingSupport()
    {
        $contents = SiteContent::where('group', 'parenting')->get()->keyBy('key');
        return view('pages.public.parenting-support', compact('contents'));
    }
    public function coding()
    {
        $contents = SiteContent::where('group', 'coding')->get()->keyBy('key');
        return view('pages.public.coding', compact('contents'));
    }
    public function honorBoard()
    {
        $champions = Champion::where('is_active', true)->orderBy('sort_order')->get();
        return view('pages.public.honor-board', compact('champions'));
    }
    public function about()
    {
        $contents = SiteContent::whereIn('group', ['about', 'why_us', 'stats'])->get()->keyBy('key');
        return view('pages.public.about', compact('contents'));
    }
    public function reviews()
    {
        $testimonials = Testimonial::where('is_published', true)->latest()->get();
        return view('pages.public.reviews', compact('testimonials'));
    }
    public function contact()
    {
        $contents = SiteContent::where('group', 'contact')->get()->keyBy('key');
        return view('pages.public.contact', compact('contents'));
    }
    public function contactSubmit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        ContactSubmission::create($data);
        return redirect()->back()->with('success', __('messages.contact_success'));
    }

    public function certificates()
    {
        $certificates = Certificate::published()->whereNotNull('image')->latest()->get();
        return view('pages.public.certificates', compact('certificates'));
    }

    public function enrollCourses()
    {
        $courses = Course::where('is_active', true)->with('category')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'desc' => $c->desc,
                'icon' => $c->icon ?: 'fa-book-open',
                'level' => $c->level,
                'category' => $c->category?->name ?? '',
                'duration' => $c->duration,
                'sessions_per_week' => $c->sessions_per_week,
                'price' => (int) $c->price,
                'is_free' => (bool) $c->is_free,
            ];
        });

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'details' => $m->details,
                'icon' => $m->icon ?? 'fa-wallet',
                'identifier' => $m->identifier,
            ];
        });

        return response()->json([
            'courses' => $courses,
            'payment_methods' => $paymentMethods,
        ]);
    }

    public function enroll(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:boy,girl,woman',
            'course_id' => 'required|exists:courses,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'receipt_note' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // إذا كان المستخدم مسجل دخول ولديه طالبة مرتبطة، استخدمها
        if ($user && $user->student) {
            $student = $user->student;
            $student->update(['name_ar' => $data['name'], 'phone' => $data['phone'], 'gender' => $data['gender']]);
        } else {
            $student = Student::where('phone', $data['phone'])->first();
            if (!$student) {
                $student = Student::create([
                    'name_ar' => $data['name'],
                    'phone' => $data['phone'],
                    'gender' => $data['gender'],
                    'user_id' => $user?->id,
                    'status' => 'active',
                ]);
            } elseif ($user && !$student->user_id) {
                $student->update(['user_id' => $user->id, 'name_ar' => $data['name'], 'phone' => $data['phone'], 'gender' => $data['gender']]);
            }
        }

        $course = Course::findOrFail($data['course_id']);
        $isPaidCourse = !$course->is_free && $course->price > 0;

        // التحقق من عدم وجود طلب مكرر معلق
        $existing = EnrollmentRequest::where('student_id', $student->id)
            ->where('course_id', $data['course_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->first();
        if ($existing) {
            return response()->json(['success' => true, 'message' => __('messages.enroll_exists')]);
        }

        $enrollmentRequest = EnrollmentRequest::create([
            'student_id' => $student->id,
            'course_id' => $data['course_id'],
        ]);

        $recipientEmail = $user?->email ?? $student->email;
        if ($recipientEmail) {
            try {
                Mail::to($recipientEmail)->send(new EnrollmentConfirmationMail(
                    $student->name,
                    $course->name,
                    $course->level,
                    $course->is_free || !$course->price ? 'مجانية' : number_format((float) $course->price) . ' ج.م'
                ));
            } catch (\Throwable $e) {
                // لا نعطل الطلب إذا تعذر الإرسال
            }
        }

        // إذا كانت الدورة مدفوعة وتم إرسال بيانات الدفع
        if ($isPaidCourse && ($request->has('payment_method_id') || $request->hasFile('receipt'))) {
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('receipts', 'public');
            }

            PaymentReceipt::create([
                'enrollment_request_id' => $enrollmentRequest->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'payment_method_id' => $data['payment_method_id'] ?? null,
                'receipt_path' => $receiptPath,
                'amount' => $course->price,
                'note' => $data['receipt_note'] ?? null,
                'status' => 'pending',
            ]);

            return response()->json(['success' => true, 'message' => __('messages.enroll_success_paid')]);
        }

        return response()->json(['success' => true, 'message' => __('messages.enroll_success')]);
    }

    // Teacher pages
    public function teacherDashboard()
    {
        return view('pages.teacher.dashboard');
    }
    public function teacherCourses()
    {
        return view('pages.teacher.courses');
    }
    public function teacherStudents()
    {
        return view('pages.teacher.students');
    }
    public function teacherSessions()
    {
        return view('pages.teacher.sessions');
    }
    public function teacherSchedule()
    {
        return view('pages.teacher.schedule');
    }
    public function teacherMessages()
    {
        return view('pages.teacher.messages');
    }
    public function teacherExams()
    {
        return view('pages.teacher.exams');
    }
    public function teacherCertificates()
    {
        return view('pages.teacher.certificates');
    }
    public function teacherReports()
    {
        return view('pages.teacher.reports');
    }
    public function teacherSettings()
    {
        return view('pages.teacher.settings');
    }
    public function teacherNotifications()
    {
        return view('pages.teacher.notifications');
    }
    public function teacherStudentProfile()
    {
        return view('pages.teacher.student-profile');
    }
    public function teacherExamDetails()
    {
        return view('pages.teacher.exam-details');
    }
    public function teacherExamResults()
    {
        return view('pages.teacher.exam-results');
    }
    public function teacherCourseDetails()
    {
        return view('pages.teacher.course-details');
    }

    // Student pages
    public function studentDashboard()
    {
        return view('pages.student.dashboard');
    }
    public function studentHifdhJourney()
    {
        return view('pages.student.hifdh-journey');
    }
    public function studentCertificates()
    {
        return view('pages.student.certificates');
    }
    public function studentExams()
    {
        return view('pages.student.exams');
    }
    public function studentSettings()
    {
        return view('pages.student.settings');
    }
    public function studentMessages()
    {
        return view('pages.student.messages');
    }
    public function studentSchedule()
    {
        return view('pages.student.schedule');
    }
    public function studentTreasureMap()
    {
        return view('pages.student.treasure-map');
    }
    public function studentLive()
    {
        return view('pages.student.live');
    }
    public function studentExam()
    {
        return view('pages.student.exam');
    }
}
