<?php
$views = [
    'pages.teacher.dashboard',
    'pages.teacher.courses',
    'pages.teacher.students',
    'pages.teacher.sessions',
    'pages.teacher.schedule',
    'pages.teacher.messages',
    'pages.teacher.exams',
    'pages.teacher.certificates',
    'pages.teacher.reports',
    'pages.teacher.settings',
    'pages.teacher.notifications',
    'pages.teacher.student-profile',
    'pages.teacher.exam-details',
    'pages.teacher.exam-results',
    'pages.teacher.course-details',
];
foreach ($views as $view) {
    try {
        view($view)->render();
        echo "✅ {$view}\n";
    } catch (\Exception $e) {
        echo "❌ {$view}: " . $e->getMessage() . "\n";
    }
}
