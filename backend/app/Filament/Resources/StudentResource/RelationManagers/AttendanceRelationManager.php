<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'سجل الحضور';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('session_id')
                    ->label('الجلسة')
                    ->relationship('session', 'title_ar')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'excused' => 'معذر',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('quiz_score')
                    ->label('درجة الاختبار')
                    ->numeric(),
                Forms\Components\TextInput::make('quiz_total')
                    ->label('الدرجة القصوى')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session.date')
                    ->label('التاريخ')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('session.title')
                    ->label('الجلسة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        'excused' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'excused' => 'معذر',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('quiz_score')
                    ->label('الاختبار')
                    ->formatStateUsing(fn($state, $record): string => $state && $record->quiz_total ? "{$state}/{$record->quiz_total}" : ($state ?: '-')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y/m/d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'present' => 'حاضر',
                        'absent' => 'غائب',
                        'late' => 'متأخر',
                        'excused' => 'معذر',
                    ]),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة حضور'),
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
            ->defaultSort('session.date', 'desc');
    }
}
