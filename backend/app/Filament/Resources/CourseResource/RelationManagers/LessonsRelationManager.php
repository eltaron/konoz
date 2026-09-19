<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $title = 'الدروس';

    protected static ?string $recordTitleAttribute = 'name_ar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name_ar')
                    ->label('اسم الدرس (عربي)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_en')
                    ->label('اسم الدرس (English)')
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الدرس')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->date('Y/m/d')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة درس'),
            ])
            ->actions([
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
