<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentRequestResource\Pages;
use App\Models\EnrollmentRequest;
use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class EnrollmentRequestResource extends Resource
{
    protected static ?string $model = EnrollmentRequest::class;

    protected static ?string $recordTitleAttribute = 'id';

    // القواعد الصارمة للـ Type hints والمسارات
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string|\UnitEnum|null $navigationGroup = 'شؤون الطلاب';

    protected static ?string $navigationLabel = 'طلبات الالتحاق';
    protected static ?string $pluralLabel = 'طلبات الالتحاق بالدورات';
    protected static ?string $label = 'طلب التحاق';
    protected static ?int $navigationSort = 2;

    // شارة ذكية تعرض عدد الطلبات المعلقة (Pending) فقط للتنبيه
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::where('status', 'pending')->exists() ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('مراجعة بيانات الطلب')
                    ->description('تفاصيل الطالبة والدورة المراد الالتحاق بها')
                    ->aside()
                    ->icon('heroicon-m-user-plus')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('student.name_ar')
                                    ->label('اسم الطالبة')
                                    ->content(fn($record): string => $record?->student?->name_ar ?? '-')
                                    ->columnSpanFull(),

                                Placeholder::make('student.phone')
                                    ->label('رقم الجوال')
                                    ->content(fn($record): string => $record?->student?->phone ?? '-'),

                                Placeholder::make('student.gender')
                                    ->label('الجنس')
                                    ->content(fn($record): string => match ($record?->student?->gender) {
                                        'woman' => 'امرأة',
                                        'girl' => 'بنت',
                                        'boy' => 'ولد',
                                        default => '-',
                                    }),

                                Placeholder::make('course.name_ar')
                                    ->label('الدورة التعليمية')
                                    ->content(fn($record): string => $record?->course?->name_ar ?? '-')
                                    ->columnSpanFull(),

                                Textarea::make('notes')
                                    ->label('ملاحظات الإدارة / أسباب الرفض')
                                    ->placeholder('اكتب ملاحظاتك هنا...')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Placeholder::make('status')
                                    ->label('حالة الطلب الحالية')
                                    ->content(fn($record): string => match ($record?->status) {
                                        'pending' => '⏳ قيد المراجعة',
                                        'approved' => '✅ مقبول',
                                        'rejected' => '❌ مرفوض',
                                        default => '-',
                                    }),

                                Placeholder::make('created_at')
                                    ->label('تاريخ تقديم الطلب')
                                    ->content(fn($record): string => $record?->created_at ? $record->created_at->format('Y-m-d H:i') : '-'),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->color('gray')
                    ->size('sm'),

                TextColumn::make('student.name_ar')
                    ->label('الطالبة')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(EnrollmentRequest $record): string => $record->student?->phone ?? ''),

                TextColumn::make('student.phone')
                    ->label('الجوال')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('course.name_ar')
                    ->label('الدورة')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'قيد المراجعة',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('approved_at')
                    ->label('تاريخ القبول')
                    ->dateTime('Y/m/d H:i')
                    ->placeholder('لم يتم القبول')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'pending' => 'قيد المراجعة',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->actions([
                // دمج الأكشنز الأساسية والخاصة في ActionGroup واحد
                ActionGroup::make([
                    Actions\ViewAction::make(),

                    // أكشن القبول مع المنطق البرمجي الخاص به
                    Actions\Action::make('approve')
                        ->label('قبول الطالبة')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->visible(fn(EnrollmentRequest $record): bool => $record->status === 'pending')
                        ->action(function (EnrollmentRequest $record) {
                            $record->update(['status' => 'approved', 'approved_at' => now()]);

                            // ربط الطالبة بالدورة في الجدول الوسيط (Pivot Table)
                            $record->student->courses()->syncWithoutDetaching([
                                $record->course_id => ['enrolled_at' => now()->toDateString()]
                            ]);

                            Notification::make()
                                ->title('تم قبول الطلب بنجاح')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('تأكيد قبول الطالبة')
                        ->modalDescription('بمجرد القبول، سيتم إدراج الطالبة تلقائياً في قائمة طلاب الدورة.')
                        ->modalSubmitActionLabel('نعم، قبول'),

                    // أكشن الرفض مع كتابة سبب الرفض
                    Actions\Action::make('reject')
                        ->label('رفض الطلب')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->visible(fn(EnrollmentRequest $record): bool => $record->status === 'pending')
                        ->form([
                            Forms\Components\Textarea::make('notes')
                                ->label('سبب الرفض')
                                ->placeholder('اكتب سبب الرفض هنا...')
                                ->rows(4)
                                ->required(),
                        ])
                        ->action(function (EnrollmentRequest $record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'notes' => $data['notes'],
                            ]);

                            Notification::make()
                                ->title('تم رفض الطلب')
                                ->body('تم تسجيل سبب الرفض')
                                ->danger()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('تأكيد رفض الطلب')
                        ->modalDescription('يرجى كتابة سبب الرفض ليتم إبلاغ الطالبة به.'),

                    Actions\Action::make('viewStudent')
                        ->label('عرض بيانات الطالبة')
                        ->color('info')
                        ->icon('heroicon-o-user')
                        ->url(fn(EnrollmentRequest $record): string => StudentResource::getUrl('edit', ['record' => $record->student_id])),

                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الطلب')
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('bulkApprove')
                        ->label('قبول المحدد')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $records->each(function (EnrollmentRequest $record) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'approved', 'approved_at' => now()]);
                                    $record->student->courses()->syncWithoutDetaching([
                                        $record->course_id => ['enrolled_at' => now()->toDateString()]
                                    ]);
                                }
                            });
                            Notification::make()
                                ->title('تم قبول ' . $records->count() . ' طلب بنجاح')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('تأكيد القبول الجماعي')
                        ->modalDescription('سيتم قبول جميع الطلبات المحددة وإدراج الطالبات في الدورات.'),

                    Actions\BulkAction::make('bulkReject')
                        ->label('رفض المحدد')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->form([
                            Forms\Components\Textarea::make('notes')
                                ->label('سبب الرفض (للجميع)')
                                ->placeholder('سبب الرفض المشترك...')
                                ->rows(3),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data) {
                            $records->each(function (EnrollmentRequest $record) use ($data) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'rejected',
                                        'notes' => $data['notes'] ?? null,
                                    ]);
                                }
                            });
                            Notification::make()
                                ->title('تم رفض ' . $records->count() . ' طلب')
                                ->danger()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('تأكيد الرفض الجماعي'),

                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollmentRequests::route('/'),
            'view' => Pages\ViewEnrollmentRequest::route('/{record}'),
        ];
    }
}
