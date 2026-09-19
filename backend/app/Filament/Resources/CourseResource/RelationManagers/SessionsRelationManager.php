<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sessions';

    protected static ?string $title = 'الجلسات المباشرة';

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
                Forms\Components\DatePicker::make('date')
                    ->label('تاريخ الجلسة')
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('status')
                    ->label('حالة الجلسة')
                    ->options([
                        'upcoming' => 'قادمة (لم تبدأ)',
                        'completed' => 'مكتملة (انتهت)',
                        'cancelled' => 'ملغاة',
                    ])
                    ->default('upcoming')
                    ->native(false)
                    ->required(),
                Forms\Components\TimePicker::make('time_from')
                    ->label('وقت البدء')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('time_to')
                    ->label('وقت الانتهاء')
                    ->seconds(false)
                    ->required(),
                Forms\Components\TextInput::make('stream_url')
                    ->label('رابط البث (Zoom / Google Meet)')
                    ->url()
                    ->placeholder('https://zoom.us/j/...'),
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
                Tables\Columns\TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('Y/m/d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_from')
                    ->label('من'),
                Tables\Columns\TextColumn::make('time_to')
                    ->label('إلى'),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'upcoming' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'upcoming' => 'قادمة',
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغاة',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('stream_url')
                    ->label('الرابط')
                    ->icon('heroicon-m-link')
                    ->color('primary')
                    ->limit(20)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'upcoming' => 'قادمة',
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغاة',
                    ]),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة جلسة'),
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
