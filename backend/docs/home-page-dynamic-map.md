# تحليل الصفحة الرئيسية — الأجزاء الديناميكية والثابتة

> الملف: `resources/views/pages/public/index.blade.php` (1164 سطر)

---

## الأجزاء الديناميكية (مضافة من لوحة التحكم فعلاً)

| القسم | السطور | المصدر (Filament Resource) | ملاحظات |
|-------|--------|----------------------------|---------|
| **سلايدر الهيرو** (الصور) | 86-161 | `HeroSliderResource` — مجموعة: **الصفحة الرئيسية** | صور متحركة مع عنوان، يتم التحكم في الترتيب والنشر |
| **آراء الطالبات** (Reviews) | 939-1014 | `TestimonialResource` — مجموعة: **الصفحة الرئيسية** | تظهر من 6 تقييمات عشوائية منشورة، مقسمة 4 لكل كاروسيل |

**ملاحظة:** الـ `FrontendController@home` يرسل المتغيرات `$slides` و `$testimonials` فقط للصفحة.

---

## الأجزاء الثابتة (Hardcoded — تحتاج إلى ديناميك)

| القسم | السطور | الوصف | الحالة المقترحة |
|-------|--------|-------|-----------------|
| **نص الهيرو + القائمة (checklist)** | 16-44 | العنوان "اهلاً بمنصة كُنوز التعليمية.." وقائمة 3 نقاط (أساتذة مجازات، بيئة آمنة، مواعيد مرنة) وإحصائيات (5000+ طالبة، 25+ دولة، 150+ معلمة، 98% رضا) | عمل **`SiteContent`** لكل عنصر أو حقول ديناميكية في `HeroSliderResource` |
| **خدماتنا المميزة** | 164-298 | بطاقة الباقة الكبيرة (خطة التعلم) + 4 بطاقات خدمات (حلقات، لغويات، تكنولوجيا، دعم تربوي) — كلها HTML صلب | عمل **Resource + Model جديد** باسم `Service` أو `HomeService` |
| **اختبار المسار التعليمي (Quiz)** | 300-396 | 3 خطوات مع أسئلة ثابتة وأزرار | يبقى ثابت (واجهة تفاعلية) أو يستبدل بـرابط خارجي |
| **نجوم منصة كُنوز التعليمية (Champions)** | 398-505 | 4 طالبات بصور واسم ووصف — كلها Hardcoded | عمل **Resource + Model جديد** باسم `Champion` |
| **لماذا تختارين منصة كُنوز التعليمية (Why Us)** | 507-590 | 4 بطاقات مميزات (خصوصية، متابعة فردية، إتقان، مرونة) + نص جانبي | عمل **Resource + Model جديد** باسم `WhyUsItem` أو استخدام `SiteContent` الموسع |
| **حاسبة التكاليف (Calculator)** | 592-670 | واجهة حاسبة JavaScript مع أسعار ثابتة وحقول (الفئة، عدد الحصص، عدد الأشهر) | يبقى ثابت أو يربط بـ `SettingResource` لتغيير الأسعار |
| **طرق الدفع** | 672-735 | 3 طرق (فودافون كاش، إنستا باي، بطاقة ائتمان) مع أرقام ثابتة | عمل **`PaymentMethod` Resource** أو استخدام `SiteContent` أو `SettingResource` |
| **معرض الشهادات (Certificates Gallery)** | 737-937 | كاروسيل صور الشهادات مع مودال عرض — كل الصور مسار ثابت | عمل **Resource + Model جديد** باسم `CertGalleryItem` |
| **قسم CTA (الدعوة للتسجيل)** | 1016-1048 | "هل أنت مستعدة لبدء رحلتك؟" مع زر تسجيل وواتساب | يبقى ثابت أو يجعل النص من `SiteContent` |

---

## الموارد الموجودة في Filament تحت مجموعة "الصفحة الرئيسية"

- ✅ `HeroSliderResource` — يدير السلايدر (موجود)
- ✅ `SiteContentResource` — يدير محتوى عام حسب `key` (موجود لكن يستخدم فقط في صفحة "من نحن")
- ✅ `TestimonialResource` — يدير آراء الطالبات (موجود)

---

## الموارد الجديدة المطلوب إنشاؤها

| Resource | Model | الحقول المقترحة | تخدم القسم |
|----------|-------|-----------------|------------|
| `ServiceResource` | `Service` | `icon`, `title_ar`, `title_en`, `desc_ar`, `desc_en`, `link`, `sort_order`, `is_published` | خدماتنا المميزة (السطور 164-298) |
| `ChampionResource` | `Champion` | `name_ar`, `name_en`, `image`, `achievement_ar`, `achievement_en`, `sort_order`, `is_published` | نجوم منصة كُنوز التعليمية (السطور 398-505) |
| `WhyUsItemResource` | `WhyUsItem` | `icon`, `title_ar`, `title_en`, `desc_ar`, `desc_en`, `sort_order`, `is_published` | لماذا تختارين (السطور 507-590) |
| `CertGalleryResource` | `CertGalleryItem` | `image`, `title_ar`, `title_en`, `category`, `sort_order`, `is_published` | معرض الشهادات (السطور 737-937) |
| `PaymentMethodResource` | `PaymentMethod` | `icon`, `name_ar`, `name_en`, `account_info`, `sort_order`, `is_published` | طرق الدفع (السطور 672-735) |

**بديل مبسط:** يمكن استخدام `SiteContentResource` الموجود لبعض النصوص (مثل نص الهيرو، نص الـ Why Us) بدل إنشاء Resources جديدة.

---

## خطوات العمل المقترحة

1. إنشاء الموديلات (Models) للموارد الجديدة
2. إنشاء المايجريشنز (Migrations) لجداول `services`, `champions`, `why_us_items`, `cert_gallery_items`, `payment_methods`
3. إنشاء Filament Resources لكل نموذج (تحت مجموعة "الصفحة الرئيسية")
4. تحديث `FrontendController@home` لجلب البيانات الجديدة وإرسالها للـ View
5. تحديث `index.blade.php` لاستبدال الـ HTML الثابت بـ `@foreach` على البيانات الديناميكية

---

> آخر تحديث: 7 يوليو 2026
