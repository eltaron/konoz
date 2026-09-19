<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificates';

    protected static ?string $title = 'الشهادات';

    protected static ?string $recordTitleAttribute = 'title_ar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('course_id')
                    ->label('الدورة')
                    ->relationship('course', 'name_ar')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('title_ar')
                    ->label('عنوان الشهادة (عربي)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_en')
                    ->label('عنوان الشهادة (English)')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('issued_at')
                    ->label('تاريخ الإصدار')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'issued' => 'صدرت',
                        'cancelled' => 'ملغية',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('الشهادة')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('course.name')
                    ->label('الدورة')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('issued_at')
                    ->label('تاريخ الإصدار')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'issued' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'issued' => 'صدرت',
                        'pending' => 'قيد الانتظار',
                        'cancelled' => 'ملغية',
                        default => $state,
                    }),
            ])
            ->defaultSort('issued_at', 'desc')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة شهادة'),
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
