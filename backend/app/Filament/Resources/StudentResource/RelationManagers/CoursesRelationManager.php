<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $title = 'الدورات المسجل بها';

    protected static ?string $recordTitleAttribute = 'name_ar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\DatePicker::make('enrolled_at')
                    ->label('تاريخ التسجيل')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الدورة')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('pivot.enrolled_at')
                    ->label('تاريخ التسجيل')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label('القسم')
                    ->badge()
                    ->color('gray'),
            ])
            ->headerActions([
                Actions\AttachAction::make()
                    ->label('تسجيل في دورة')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn($query) => $query->where('is_active', true)),
            ])
            ->actions([
                Actions\ViewAction::make()->label('عرض'),
                Actions\DetachAction::make()->label('إلغاء التسجيل'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
