<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use App\Support\SlugGenerator;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title_ar';

    // القواعد الصارمة للـ Type hints والمسارات
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|\UnitEnum|null $navigationGroup = 'واجهة الموقع';

    protected static ?string $navigationLabel = 'المقالات';
    protected static ?string $pluralLabel = 'المقالات';
    protected static ?string $label = 'مقال';
    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title_ar', 'title_en', 'tag', 'slug'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('إنشاء وتحرير المقال')
                    ->description('أدخل تفاصيل المقال، الوسوم، والمحتوى اللغوي')
                    ->aside()
                    ->icon('heroicon-m-pencil-square')
                    ->schema([
                        Tabs::make('Post Management')
                            ->tabs([
                                // التبويب الأول: المحتوى العربي
                                TabsTab::make('المحتوى العربي')
                                    ->icon('heroicon-m-language')
                                    ->schema([
                                        TextInput::make('title_ar')
                                            ->label('عنوان المقال (عربي)')
                                            ->required()
                                            ->maxLength(255)
                                            ->live()
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugGenerator::make($state, 'post')))
                                            ->columnSpanFull(),

                                        RichEditor::make('content_ar')
                                            ->label('نص المقال (عربي)')
                                            ->required()
                                            ->columnSpanFull(), // استغلال المساحة كاملة
                                    ]),

                                // التبويب الثاني: المحتوى الإنجليزي
                                TabsTab::make('English Content')
                                    ->icon('heroicon-m-globe-alt')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Post Title (English)')
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugGenerator::make($state, 'post')))
                                            ->columnSpanFull(),

                                        RichEditor::make('content_en')
                                            ->label('Post Content (English)')
                                            ->columnSpanFull(),
                                    ]),

                                // التبويب الثالث: الإعدادات والوسائط
                                TabsTab::make('الإعدادات والوسائط')
                                    ->icon('heroicon-m-cog-6-tooth')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('slug')
                                                ->label('رابط المقال (Slug)')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->live()
                                                ->disabled()
                                                ->dehydrated()
                                                ->columnSpanFull(),

                                            TextInput::make('tag')
                                                ->label('الوسم / التصنيف')
                                                ->required()
                                                ->placeholder('مثال: أخبار، نصائح قرآنيّة'),

                                            TextInput::make('read_time')
                                                ->label('وقت القراءة (دقائق)')
                                                ->numeric()
                                                ->default(5)
                                                ->suffix('دقائق'),

                                            FileUpload::make('image')
                                                ->label('صورة المقال الرئيسية')
                                                ->image()
                                                ->imageEditor()
                                                ->disk('public')
                                                ->directory('posts-images')
                                                ->columnSpanFull(),

                                            Toggle::make('is_published')
                                                ->label('نشر المقال الآن')
                                                ->default(true)
                                                ->onColor('success')
                                                ->columnSpanFull(),
                                        ]),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->square(),

                Tables\Columns\TextColumn::make('title_ar')
                    ->label('عنوان المقال')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Post $record): string => $record->tag),

                Tables\Columns\TextColumn::make('read_time')
                    ->label('وقت القراءة')
                    ->suffix(' دقيقة')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('منشور'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ النشر')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->description(fn($record) => $record->created_at->diffForHumans()),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('حالة النشر'),
                Tables\Filters\SelectFilter::make('tag')
                    ->label('تصفية حسب الوسم')
                    ->options(fn() => Post::distinct()->pluck('tag', 'tag')->toArray()),
            ])
            ->actions([
                // استخدام ActionGroup من Filament\Actions
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات المقال')
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
