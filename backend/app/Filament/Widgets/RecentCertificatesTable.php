<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentCertificatesTable extends BaseWidget
{
    protected static ?string $heading = 'أحدث الشهادات الصادرة';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Certificate::with(['student', 'course'])
                    ->whereNotNull('student_id')
                    ->whereNotNull('course_id')
                    ->latest('issued_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('student.name_ar')
                    ->label('اسم الطالب')
                    ->searchable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('course.name_ar')
                    ->label('الدورة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_ar')
                    ->label('عنوان الشهادة')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('issued_at')
                    ->label('تاريخ الإصدار')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivered' => 'success',
                        'issued' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'delivered', 'issued' => 'صادرة',
                        'pending' => 'قيد الإصدار',
                        'draft' => 'مسودة',
                        default => $state,
                    }),
            ])
            ->defaultSort('issued_at', 'desc')
            ->paginated(false);
    }
}