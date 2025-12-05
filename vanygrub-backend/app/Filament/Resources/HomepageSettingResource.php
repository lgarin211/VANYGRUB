<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSettingResource\Pages;
use App\Models\HomepageSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;

class HomepageSettingResource extends Resource
{
    protected static ?string $model = HomepageSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Homepage Settings';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Homepage Sections')
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Section Configuration')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Forms\Components\Select::make('section_name')
                                                    ->label('Section Name')
                                                    ->options([
                                                        'welcome' => '👋 Welcome Section',
                                                        'brands' => '🏢 Brands Section',
                                                        'values' => '⭐ Values Section',
                                                        'portfolio' => '📁 Portfolio Section',
                                                    ])
                                                    ->required()
                                                    ->unique(ignoreRecord: true),

                                                Forms\Components\Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true)
                                                    ->inline(false),

                                                Forms\Components\TextInput::make('display_order')
                                                    ->label('Display Order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Welcome Section')
                            ->icon('heroicon-o-hand-raised')
                            ->schema([
                                Section::make('Welcome Content')
                                    ->description('Konten untuk section Welcome di homepage')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('welcome_badge')
                                                    ->label('Badge Text')
                                                    ->placeholder('Selamat Datang')
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('welcome_title')
                                                    ->label('Title')
                                                    ->placeholder('VANY GROUP')
                                                    ->maxLength(255),
                                            ]),

                                        Forms\Components\TextInput::make('welcome_tagline')
                                            ->label('Tagline')
                                            ->placeholder('Keunggulan Tradisi & Inovasi Modern')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('welcome_description')
                                            ->label('Description')
                                            ->placeholder('Deskripsi lengkap tentang VANY GROUP...')
                                            ->rows(4),

                                        Forms\Components\FileUpload::make('welcome_image')
                                            ->label('Welcome Image')
                                            ->image()
                                            ->directory('homepage/welcome')
                                            ->maxSize(2048),
                                    ]),

                                Section::make('Highlights')
                                    ->description('Statistik atau highlight angka')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('highlight_1_number')
                                                    ->label('Highlight 1 Number')
                                                    ->placeholder('3+')
                                                    ->maxLength(50),

                                                Forms\Components\TextInput::make('highlight_1_text')
                                                    ->label('Highlight 1 Text')
                                                    ->placeholder('Brand Premium')
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('highlight_2_number')
                                                    ->label('Highlight 2 Number')
                                                    ->placeholder('100%')
                                                    ->maxLength(50),

                                                Forms\Components\TextInput::make('highlight_2_text')
                                                    ->label('Highlight 2 Text')
                                                    ->placeholder('Kualitas Terjamin')
                                                    ->maxLength(255),
                                            ]),
                                    ]),

                                Section::make('Button')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('welcome_button_text')
                                                    ->label('Button Text')
                                                    ->placeholder('Jelajahi Brand')
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('welcome_button_link')
                                                    ->label('Button Link')
                                                    ->placeholder('#brands-section')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Brands Section')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Section::make('Brands Content')
                                    ->description('Konten untuk section Brands')
                                    ->schema([
                                        Forms\Components\TextInput::make('brand_section_title')
                                            ->label('Section Title')
                                            ->placeholder('Brand Kami')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('brand_section_description')
                                            ->label('Section Description')
                                            ->rows(2),

                                        Forms\Components\TextInput::make('brand_featured_title')
                                            ->label('Featured Brand Title')
                                            ->placeholder('Koleksi Premium VANY GROUP')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('brand_featured_description')
                                            ->label('Featured Brand Description')
                                            ->rows(3),

                                        Forms\Components\FileUpload::make('brand_featured_image')
                                            ->label('Featured Brand Image')
                                            ->image()
                                            ->directory('homepage/brands')
                                            ->maxSize(2048),

                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('brand_button_text')
                                                    ->label('Button Text')
                                                    ->placeholder('Jelajahi Brand VNY')
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('brand_button_link')
                                                    ->label('Button Link')
                                                    ->placeholder('/vny')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Values Section')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Section::make('Value 1 - Quality Craftsmanship')
                                    ->description('Section pertama tentang kualitas')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('value_1_number')
                                                    ->label('Number')
                                                    ->placeholder('01')
                                                    ->maxLength(10),

                                                Forms\Components\TextInput::make('value_1_title')
                                                    ->label('Title')
                                                    ->placeholder('Kualitas Kerajinan Tangan')
                                                    ->maxLength(255),
                                            ]),

                                        Forms\Components\Textarea::make('value_1_description')
                                            ->label('Description')
                                            ->rows(3),

                                        Forms\Components\FileUpload::make('value_1_image')
                                            ->label('Image')
                                            ->image()
                                            ->directory('homepage/values')
                                            ->maxSize(2048),

                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('value_1_button_text')
                                                    ->label('Button Text')
                                                    ->placeholder('Pelajari Lebih Lanjut')
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('value_1_button_link')
                                                    ->label('Button Link')
                                                    ->placeholder('#portfolio-section')
                                                    ->maxLength(255),
                                            ]),
                                    ]),

                                Section::make('Value 2 - Heritage & Innovation')
                                    ->description('Section kedua tentang warisan budaya')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('value_2_number')
                                                    ->label('Number')
                                                    ->placeholder('02')
                                                    ->maxLength(10),

                                                Forms\Components\TextInput::make('value_2_title')
                                                    ->label('Title')
                                                    ->placeholder('Warisan Budaya & Inovasi')
                                                    ->maxLength(255),
                                            ]),

                                        Forms\Components\Textarea::make('value_2_description')
                                            ->label('Description')
                                            ->rows(3),

                                        Forms\Components\FileUpload::make('value_2_image')
                                            ->label('Image')
                                            ->image()
                                            ->directory('homepage/values')
                                            ->maxSize(2048),

                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('value_2_button_text')
                                                    ->label('Button Text')
                                                    ->placeholder('Jelajahi Warisan')
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('value_2_button_link')
                                                    ->label('Button Link')
                                                    ->placeholder('/vny/about')
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                            ]),

                        Tabs\Tab::make('Portfolio Section')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Section::make('Portfolio Content')
                                    ->description('Konten untuk section Portfolio')
                                    ->schema([
                                        Forms\Components\TextInput::make('portfolio_title')
                                            ->label('Section Title')
                                            ->placeholder('Portofolio Brand Kami')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('portfolio_subtitle')
                                            ->label('Section Subtitle')
                                            ->placeholder('Jelajahi beragam brand...')
                                            ->rows(2),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section_name')
                    ->label('Section')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'welcome' => 'success',
                        'brands' => 'info',
                        'values' => 'warning',
                        'portfolio' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'welcome' => '👋 Welcome',
                        'brands' => '🏢 Brands',
                        'values' => '⭐ Values',
                        'portfolio' => '📁 Portfolio',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('welcome_title')
                    ->label('Title/Content')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('display_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('section_name')
                    ->label('Section')
                    ->options([
                        'welcome' => 'Welcome',
                        'brands' => 'Brands',
                        'values' => 'Values',
                        'portfolio' => 'Portfolio',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHomepageSettings::route('/'),
            'create' => Pages\CreateHomepageSetting::route('/create'),
            'edit' => Pages\EditHomepageSetting::route('/{record}/edit'),
        ];
    }
}
