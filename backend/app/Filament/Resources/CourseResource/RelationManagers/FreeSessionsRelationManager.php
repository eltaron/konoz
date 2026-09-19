<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class FreeSessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'freeSessions';

    protected static ?string $title = 'الجلسات المجانية';

    protected static ?string $recordTitleAttribute = 'title_ar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title_ar')
                    ->label('عنوان الجلسة (عربي)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_en')
                    ->label('عنوان الجلسة (English)')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description_ar')
                    ->label('الوصف (عربي)')
                    ->rows(3),
                Forms\Components\Textarea::make('description_en')
                    ->label('الوصف (English)')
                    ->rows(3),
                Forms\Components\TextInput::make('video_url')
                    ->label('رابط الفيديو')
                    ->url()
                    ->placeholder('https://www.youtube.com/watch?v=...'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('الجلسة')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(40)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة جلسة مجانية'),
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
