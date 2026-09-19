<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ExamResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    protected static ?string $title = 'نتائج الطلاب';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('student_id')
                    ->label('الطالب')
                    ->relationship('student', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('score')
                    ->label('الدرجة')
                    ->numeric(),
                Forms\Components\TextInput::make('correct_count')
                    ->label('عدد الإجابات الصحيحة')
                    ->numeric(),
                Forms\Components\TextInput::make('total_questions')
                    ->label('إجمالي الأسئلة')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('الطالب')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('score')
                    ->label('الدرجة')
                    ->sortable()
                    ->color(fn($state): string => match (true) {
                        $state >= 75 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('correct_count')
                    ->label('الصحيح'),
                Tables\Columns\TextColumn::make('total_questions')
                    ->label('الإجمالي'),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('تاريخ التسليم')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->defaultSort('score', 'desc')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة نتيجة'),
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
            ]);
    }
}
