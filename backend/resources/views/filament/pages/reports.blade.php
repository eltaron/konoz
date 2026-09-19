<x-filament-panels::page>
    <div class="space-y-6">

        {{-- إعدادات التقرير --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="p-4 sm:p-5">
                <div class="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-950 dark:text-white">
                    <x-heroicon-m-adjustments-horizontal class="h-5 w-5 text-primary-600" />
                    إعدادات التقرير
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- نوع التقرير --}}
                    <div>
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
                            placeholder="اكتب اسم الطالب، الدورة، العنوان..."
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
                        tag="a"
                        :href="$this->printUrl()"
                        target="_blank"
                        icon="heroicon-m-printer">
                        طباعة هذا التقرير
                    </x-filament::button>

                    <x-filament::button
                        wire:click="resetFilters"
                        color="gray"
                        icon="heroicon-m-x-mark">
                        إعادة التعيين
                    </x-filament::button>
                </div>
            </div>
        </div>

        {{-- جدول النتائج --}}
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-950/5 px-4 py-3.5 dark:border-white/10 sm:px-5">
                <div>
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">تقرير {{ $this->typeLabel }}</h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">عدد السجلات: {{ $this->results->count() }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-950/10 text-right dark:border-white/10">
                            @foreach($this->columns as $col)
                                <th class="px-4 py-2.5 text-xs font-semibold whitespace-nowrap text-gray-600 dark:text-gray-400">{{ $col['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-950/5 dark:divide-white/5">
                        @forelse($this->results as $row)
                            <tr class="text-gray-900 dark:text-white">
                                @foreach($this->columns as $col)
                                    <td class="px-4 py-2.5 whitespace-nowrap text-gray-950 dark:text-white">{{ $col['value']($row) }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($this->columns) }}" class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                    لا توجد نتائج مطابقة للفلاتر المحددة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>