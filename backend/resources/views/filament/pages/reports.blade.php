<x-filament-panels::page>
    <div class="space-y-6">

        {{-- ملخص التقرير --}}
        @if($this->reportSummary)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">{{ $this->reportSummary['label'] }}</p>
                        <p class="mt-1 text-2xl font-bold">{{ number_format($this->reportSummary['total']) }}</p>
                    </div>
                    <x-heroicon-o-users class="h-10 w-10 opacity-70" />
                </div>
            </div>
            
            @if(isset($this->reportSummary['active']))
            <div class="rounded-xl bg-gradient-to-br from-success-500 to-success-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">النشط</p>
                        <p class="mt-1 text-2xl font-bold">{{ number_format($this->reportSummary['active']) }}</p>
                    </div>
                    <x-heroicon-o-check-circle class="h-10 w-10 opacity-70" />
                </div>
            </div>
            @endif
            
            @if(isset($this->reportSummary['issued']))
            <div class="rounded-xl bg-gradient-to-br from-info-500 to-info-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">تم إصداره</p>
                        <p class="mt-1 text-2xl font-bold">{{ number_format($this->reportSummary['issued']) }}</p>
                    </div>
                    <x-heroicon-o-document-check class="h-10 w-10 opacity-70" />
                </div>
            </div>
            @endif
            
            @if(isset($this->reportSummary['avg_score']))
            <div class="rounded-xl bg-gradient-to-br from-warning-500 to-warning-600 p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium opacity-80">متوسط الدرجات</p>
                        <p class="mt-1 text-2xl font-bold">{{ $this->reportSummary['avg_score'] }}%</p>
                    </div>
                    <x-heroicon-o-academic-cap class="h-10 w-10 opacity-70" />
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- إعدادات التقرير --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-4 sm:p-5">
                <div class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-950 dark:text-white">
                    <x-heroicon-m-adjustments-horizontal class="h-5 w-5 text-primary-600" />
                    إعدادات التقرير
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    {{-- نوع التقرير --}}
                    <div class="xl:col-span-2">
                        <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">نوع التقرير</label>
                        <select
                            wire:model.live="reportType"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            @foreach($this->types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- البحث --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">بحث</label>
                        <input
                            type="text"
                            wire:model.live.debounce.500ms="search"
                            placeholder="اكتب للبحث..."
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    {{-- حالة --}}
                    @if($this->statusOptions)
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">الحالة</label>
                            <select
                                wire:model.live="status"
                                class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">كل الحالات</option>
                                @foreach($this->statusOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- دورة --}}
                    @if(count($this->courseOptions))
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">الدورة</label>
                            <select
                                wire:model.live="courseId"
                                class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <option value="">كل الدورات</option>
                                @foreach($this->courseOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- الفترة من --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">من تاريخ</label>
                        <input
                            type="date"
                            wire:model.live="dateFrom"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    {{-- الفترة إلى --}}
                    <div>
                        <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">إلى تاريخ</label>
                        <input
                            type="date"
                            wire:model.live="dateTo"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                </div>

                {{-- أزرار --}}
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <x-filament::button
                        wire:click="resetFilters"
                        color="gray"
                        icon="heroicon-m-x-mark">
                        إعادة التعيين
                    </x-filament::button>
                    
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        عدد السجلات: <strong class="text-primary-600">{{ $this->results->count() }}</strong>
                    </span>
                </div>
            </div>
        </div>

        {{-- جدول النتائج --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-950/5 px-4 py-3.5 dark:border-white/10 sm:px-5">
                <div>
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">تقرير {{ $this->typeLabel }}</h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">عرض {{ $this->results->count() }} سجل</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <x-filament::button
                        tag="a"
                        :href="$this->printUrl('pdf')"
                        target="_blank"
                        size="sm"
                        color="success"
                        icon="heroicon-m-arrow-down-tray">
                        تصدير PDF
                    </x-filament::button>
                    
                    <x-filament::button
                        tag="a"
                        :href="$this->printUrl()"
                        target="_blank"
                        size="sm"
                        color="primary"
                        icon="heroicon-m-printer">
                        طباعة
                    </x-filament::button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-950/10 bg-gray-50 text-right dark:bg-gray-800 dark:border-white/10">
                            @foreach($this->columns as $col)
                                <th class="px-4 py-3 text-xs font-semibold whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $col['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-950/5 dark:divide-white/5">
                        @forelse($this->results as $row)
                            <tr class="text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                @foreach($this->columns as $col)
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-950 dark:text-white">{{ $col['value']($row) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($this->columns) }}" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <x-heroicon-o-inbox class="h-12 w-12 text-gray-400" />
                                        <p class="text-sm text-gray-500 dark:text-gray-400">لا توجد نتائج مطابقة للفلاتر المحددة</p>
                                        <x-filament::button
                                            wire:click="resetFilters"
                                            size="sm"
                                            color="gray">
                                            إعادة تعيين الفلاتر
                                        </x-filament::button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($this->results->count())
            <div class="border-t border-gray-950/5 px-4 py-3 text-xs text-gray-500 dark:border-white/10 dark:text-gray-400 sm:px-5">
                تم عرض {{ $this->results->count() }} سجل من إجمالي البيانات المتاحة
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>