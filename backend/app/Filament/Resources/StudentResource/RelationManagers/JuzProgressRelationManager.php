<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class JuzProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'juzProgress';

    protected static ?string $title = 'تقدم الحفظ';

    protected static ?string $recordTitleAttribute = 'juz_number';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('juz_number')
                    ->label('رقم الجزء')
                    ->options(static::getJuzOptions())
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'not_started' => 'لم يبدأ',
                        'in_progress' => 'قيد الحفظ',
                        'completed' => 'مكتمل',
                        'review' => 'مراجعة',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('started_at')
                    ->label('تاريخ البداية'),
                Forms\Components\DatePicker::make('completed_at')
                    ->label('تاريخ الإكمال'),
                Forms\Components\DatePicker::make('target_review_at')
                    ->label('تاريخ المراجعة المستهدف'),
                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('juz_number')
                    ->label('الجزء')
                    ->sortable()
                    ->formatStateUsing(fn($state): string => "الجزء {$state}")
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('juz_name')
                    ->label('اسم الجزء')
                    ->formatStateUsing(fn($record): string => static::getJuzName($record->juz_number)),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'review' => 'info',
                        'not_started' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'completed' => 'مكتمل',
                        'in_progress' => 'قيد الحفظ',
                        'review' => 'مراجعة',
                        'not_started' => 'لم يبدأ',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('تاريخ البداية')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('تاريخ الإكمال')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_review_at')
                    ->label('مراجعة في')
                    ->date('Y/m/d')
                    ->color(fn($state): ?string => $state ? ($state->isPast() ? 'danger' : null) : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'completed' => 'مكتمل',
                        'in_progress' => 'قيد الحفظ',
                        'review' => 'مراجعة',
                        'not_started' => 'لم يبدأ',
                    ]),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة جزء'),
            ])
            ->actions([
                Actions\ViewAction::make()->label('عرض'),
                Actions\EditAction::make()->label('تعديل')->color('info'),
                Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('juz_number');
    }

    public static function getJuzOptions(): array
    {
        $names = static::getJuzNames();
        $options = [];
        foreach ($names as $num => $name) {
            $options[$num] = "الجزء {$num} - {$name}";
        }
        return $options;
    }

    public static function getJuzName(int $juz): string
    {
        return static::getJuzNames()[$juz] ?? '';
    }

    public static function getJuzNames(): array
    {
        return [
            1 => 'الفاتحة',
            2 => 'البقرة (١)',
            3 => 'البقرة (٢)',
            4 => 'آل عمران',
            5 => 'النساء',
            6 => 'المائدة',
            7 => 'الأنعام',
            8 => 'الأعراف',
            9 => 'الأنفال',
            10 => 'التوبة',
            11 => 'يونس',
            12 => 'هود',
            13 => 'يوسف',
            14 => 'إبراهيم',
            15 => 'الحجر',
            16 => 'النحل',
            17 => 'الإسراء',
            18 => 'الكهف',
            19 => 'مريم',
            20 => 'طه',
            21 => 'الأنبياء',
            22 => 'الحج',
            23 => 'المؤمنون',
            24 => 'النور',
            25 => 'الفرقان',
            26 => 'الشعراء',
            27 => 'النمل',
            28 => 'القصص',
            29 => 'العنكبوت',
            30 => 'الناس',
        ];
    }
}
