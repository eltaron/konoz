<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ExamsRelationManager extends RelationManager
{
    protected static string $relationship = 'exams';

    protected static ?string $title = 'الامتحانات';

    protected static ?string $recordTitleAttribute = 'title_ar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title_ar')
                    ->label('عنوان الامتحان (عربي)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_en')
                    ->label('عنوان الامتحان (English)')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->label('تاريخ الامتحان')
                    ->default(now())
                    ->required(),
                Forms\Components\Repeater::make('questions')
                    ->relationship('questions')
                    ->label('أسئلة الامتحان')
                    ->schema([
                        Forms\Components\Textarea::make('question_ar')
                            ->label('السؤال (عربي)')
                            ->required()
                            ->rows(2),
                        Forms\Components\Textarea::make('question_en')
                            ->label('السؤال (English)')
                            ->rows(2),
                        Forms\Components\Repeater::make('options')
                            ->label('خيارات الإجابة')
                            ->schema([
                                Forms\Components\TextInput::make('option_text')
                                    ->label('نص الخيار')
                                    ->required(),
                            ])
                            ->grid(2)
                            ->addActionLabel('إضافة خيار'),
                        Forms\Components\TextInput::make('correct_answer')
                            ->label('الإجابة الصحيحة')
                            ->placeholder('النص المطابق للإجابة الصحيحة')
                            ->required(),
                        Forms\Components\TextInput::make('order')
                            ->label('ترتيب السؤال')
                            ->numeric()
                            ->default(0),
                    ])
                    ->itemLabel(fn(array $state): ?string => $state['question_ar'] ?? 'سؤال جديد')
                    ->collapsible()
                    ->collapsed()
                    ->cloneable()
                    ->columnSpanFull()
                    ->addActionLabel('إضافة سؤال'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('الامتحان')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('الأسئلة')
                    ->counts('questions')
                    ->badge(),
                Tables\Columns\TextColumn::make('avg_score')
                    ->label('متوسط الدرجات')
                    ->suffix('%')
                    ->color('success'),
            ])
            ->defaultSort('date', 'desc')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة امتحان'),
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
