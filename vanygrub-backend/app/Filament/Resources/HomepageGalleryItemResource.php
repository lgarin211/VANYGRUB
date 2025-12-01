<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageGalleryItemResource\Pages;
use App\Models\HomepageGalleryItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

class HomepageGalleryItemResource extends Resource
{
    protected static ?string $model = HomepageGalleryItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Homepage Gallery';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Gallery Item Details')
                    ->description('Configure homepage gallery items that appear on the landing page')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('🏷️ Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Vany Songket'),

                                Forms\Components\Select::make('category')
                                    ->label('📂 Category')
                                    ->options([
                                        'Traditional Fashion' => 'Traditional Fashion',
                                        'Footwear' => 'Footwear',
                                        'Hospitality' => 'Hospitality',
                                        'Real Estate' => 'Real Estate',
                                        'Beauty & Wellness' => 'Beauty & Wellness',
                                        'Culinary' => 'Culinary',
                                        'Travel' => 'Travel',
                                        'Health & Fitness' => 'Health & Fitness',
                                        'Home & Living' => 'Home & Living',
                                    ])
                                    ->required()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ]),
                            ]),

                        Forms\Components\FileUpload::make('image')
                            ->label('🖼️ Gallery Image')
                            ->image()
                            ->required()
                            ->directory('homepage/gallery')
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                                '3:4',
                                '9:16'
                            ])
                            ->maxSize(5120) // 5MB
                            ->helperText('Upload high-quality image for gallery. Recommended: 600x800px or similar aspect ratio.'),

                        Forms\Components\Textarea::make('description')
                            ->label('📝 Description')
                            ->required()
                            ->rows(4)
                            ->maxLength(500)
                            ->placeholder('Write an engaging description for this gallery item...'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('target')
                                    ->label('🔗 Link Target')
                                    ->required()
                                    ->default('/')
                                    ->placeholder('/vny, /gallery, https://external-link.com')
                                    ->helperText('Where should this gallery item link to?'),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('🔢 Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first'),
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('✅ Active')
                            ->default(true)
                            ->helperText('Show/hide this item on the homepage'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Traditional Fashion' => 'warning',
                        'Footwear' => 'info',
                        'Hospitality' => 'success',
                        'Real Estate' => 'primary',
                        'Beauty & Wellness' => 'pink',
                        'Culinary' => 'orange',
                        'Travel' => 'blue',
                        'Health & Fitness' => 'green',
                        'Home & Living' => 'purple',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->description;
                    }),

                Tables\Columns\TextColumn::make('target')
                    ->label('Link')
                    ->limit(30)
                    ->copyable()
                    ->copyableState(fn ($record) => $record->target)
                    ->tooltip('Click to copy'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Traditional Fashion' => 'Traditional Fashion',
                        'Footwear' => 'Footwear',
                        'Hospitality' => 'Hospitality',
                        'Real Estate' => 'Real Estate',
                        'Beauty & Wellness' => 'Beauty & Wellness',
                        'Culinary' => 'Culinary',
                        'Travel' => 'Travel',
                        'Health & Fitness' => 'Health & Fitness',
                        'Home & Living' => 'Home & Living',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => $record->target)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->target !== '/'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => true])))
                        ->color('success')
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => false])))
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageGalleryItems::route('/'),
            'create' => Pages\CreateHomepageGalleryItem::route('/create'),
            'edit' => Pages\EditHomepageGalleryItem::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::active()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::active()->count();
        return $count > 0 ? 'success' : 'gray';
    }
}