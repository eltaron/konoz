@extends('emails.layout')

@section('content')
<p style="margin:0 0 16px;">
    مرحباً <strong>{{ $userName }}</strong>،
</p>
<p style="margin:0 0 16px;">
    استلمنا طلب تسجيلك في الدورة التالية بنجاح ✅
</p>
<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%; background:#f8fcfe; border:1px solid #dbeaf0; border-radius:12px; margin:18px 0;">
    <tr>
        <td style="padding:18px 22px;">
            <style>
                .em{padding:4px 0;color:#2c3e50;}
                .em2{color:#0F6D80;font-weight:700;}
            </style>
            <div style="padding:4px 0;">📚 <span class="em2">الدورة:</span> <span class="em">{{ $courseName }}</span></div>
            @if($courseLevel)
            <div style="padding:4px 0;">🎯 <span class="em2">المستوى:</span> <span class="em">{{ $courseLevel }}</span></div>
            @endif
            @if($coursePrice !== null)
            <div style="padding:4px 0;">💰 <span class="em2">التكلفة:</span> <span class="em">{{ $coursePrice }}</span></div>
            @endif
            <div style="padding:4px 0;">📅 <span class="em2">تاريخ الطلب:</span> <span class="em">{{ $requestedAt }}</span></div>
        </td>
    </tr>
</table>
<p style="margin:0 0 16px;">
    سيقوم فريقنا بمراجعة الطلب والرد عليك قريباً لتأكيد الحجز وتفاصيل الجدول. إذا كانت الدورة مدفوعة ولم تجدي وسيلة الدفع، سنوافيك ببيانات التحويل على الفور.
</p>
<p style="margin:0 0 0; color:#7f8c8d; font-size:13px;">
    إن كان لديكِ أي استفسار، راسلينا من خلال صفحة <strong>تواصل معنا</strong> على الموقع.
</p>
@endsection