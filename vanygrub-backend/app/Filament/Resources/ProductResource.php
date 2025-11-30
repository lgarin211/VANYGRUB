<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Information')
                    ->description('Basic product information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $context, $state, $set) {
                                if ($context === 'edit') {
                                    return;
                                }
                                $set('slug', \Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('detailed_description')
                            ->required()
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('short_description')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Product Details')
                    ->description('SKU, pricing and stock information')
                    ->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('serial_number')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('price')
                            ->label('Price (IDR)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000)
                            ->minValue(0),
                        Forms\Components\TextInput::make('sale_price')
                            ->label('Sale Price (IDR)')
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000)
                            ->minValue(0)
                            ->lt('price'),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Stock Quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        Forms\Components\Toggle::make('manage_stock')
                            ->label('Manage Stock')
                            ->default(true),
                        Forms\Components\Toggle::make('in_stock')
                            ->label('In Stock')
                            ->default(true),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'draft' => 'Draft',
                                'archived' => 'Archived',
                            ])
                            ->default('active'),
                    ])->columns(2),

                Forms\Components\Section::make('Images')
                    ->description('Product images and gallery')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Main Product Image')
                            ->image()
                            ->directory('temp')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imagePreviewHeight('150')
                            ->required(),
                        Forms\Components\FileUpload::make('main_image')
                            ->label('Featured Image')
                            ->image()
                            ->directory('temp')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imagePreviewHeight('150'),
                        Forms\Components\FileUpload::make('gallery')
                            ->label('Product Gallery')
                            ->image()
                            ->imageEditor()
                            ->multiple()
                            ->directory('temp')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->maxSize(2048)
                            ->maxFiles(10)
                            ->reorderable()
                            ->appendFiles()
                            ->panelLayout('grid')
                            ->imagePreviewHeight('100')
                            ->loadingIndicatorPosition('center')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Product Attributes')
                    ->description('Colors, sizes and specifications')
                    ->schema([
                        Forms\Components\TagsInput::make('colors')
                            ->label('Available Colors')
                            ->placeholder('Add colors...')
                            ->suggestions([
                                'Black',
                                'White',
                                'Red',
                                'Blue',
                                'Green',
                                'Yellow',
                                'Pink',
                                'Purple',
                                'Orange',
                                'Gray',
                                'Brown',
                                'Black/Red',
                                'White/Black',
                                'Navy Blue',
                                'Dark Gray'
                            ]),
                        Forms\Components\TagsInput::make('sizes')
                            ->label('Available Sizes')
                            ->placeholder('Add sizes...')
                            ->suggestions(['38', '39', '40', '41', '42', '43', '44', '45', '46']),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (grams)')
                            ->numeric()
                            ->suffix('g')
                            ->minValue(0),
                        Forms\Components\TextInput::make('dimensions')
                            ->label('Dimensions (L x W x H)')
                            ->placeholder('e.g., 30 x 20 x 12 cm')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country_origin')
                            ->label('Country of Origin')
                            ->maxLength(255)
                            ->placeholder('e.g., Indonesia, Vietnam, China'),
                        Forms\Components\TextInput::make('warranty')
                            ->label('Warranty Period')
                            ->maxLength(255)
                            ->placeholder('e.g., 1 Year, 6 Months'),
                        Forms\Components\DatePicker::make('release_date')
                            ->label('Release Date')
                            ->native(false),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Product')
                            ->default(false),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->money('IDR')
                    ->sortable()
                    ->color('success')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state > 50 => 'success',
                        $state > 10 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\IconColumn::make('in_stock')
                    ->label('In Stock')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'draft' => 'warning',
                        'archived' => 'gray',
                        default => 'primary',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('colors')
                    ->label('Colors')
                    ->badge()
                    ->separator(',')
                    ->limit(3)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sizes')
                    ->label('Sizes')
                    ->badge()
                    ->separator(',')
                    ->limit(5)
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueColor('warning')
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('release_date')
                    ->label('Release Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'draft' => 'Draft',
                        'archived' => 'Archived',
                    ]),
                Tables\Filters\TernaryFilter::make('in_stock')
                    ->label('In Stock')
                    ->boolean()
                    ->trueLabel('In Stock')
                    ->falseLabel('Out of Stock')
                    ->native(false),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueLabel('Featured')
                    ->falseLabel('Not Featured')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
