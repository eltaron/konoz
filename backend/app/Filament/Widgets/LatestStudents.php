<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStudents extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'أحدث الطلاب';

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::withCount('courses')->latest()->limit(10))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
                Tables\Columns\TextColumn::make('level')->label('المستوى')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'متقدم' => 'success',
                        'متوسط' => 'warning',
                        'مبتدئ' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('courses_count')->label('المسجلة')->counts('courses'),
                Tables\Columns\TextColumn::make('status')->label('الحالة')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => $state === 'active' ? 'نشط' : ($state === 'suspended' ? 'موقوف' : $state)),
                Tables\Columns\TextColumn::make('created_at')->label('التسجيل')->date('Y-m-d'),
            ]);
    }
}
