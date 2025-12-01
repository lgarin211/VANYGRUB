<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductGridResource\Pages;
use App\Filament\Resources\ProductGridResource\RelationManagers;
use App\Models\ProductGrid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductGridResource extends Resource
{
    protected static ?string $model = ProductGrid::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Grid Item')
                    ->description('Configure product grid display item')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Nike Pegasus Premium'),
                        Forms\Components\TextInput::make('subtitle')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. For the Ultimate Energised Ride'),
                        Forms\Components\FileUpload::make('image')
                            ->label('Product Image')
                            ->image()
                            ->directory('temp')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->imagePreviewHeight('150')
                            ->required(),
                        Forms\Components\TextInput::make('button_text')
                            ->label('Button Text')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('e.g. Shop, Notify Me'),
                    ])->columns(2),

                Forms\Components\Section::make('Styling & Layout')
                    ->description('Configure visual appearance')
                    ->schema([
                        Forms\Components\Select::make('bg_color')
                            ->label('Background Color')
                            ->required()
                            ->options([
                                'from-gray-900 to-gray-700' => 'Dark Gray',
                                'from-gray-800 to-gray-900' => 'Deep Gray',
                                'from-yellow-600 to-yellow-800' => 'Golden Yellow',
                                'from-gray-300 to-gray-100' => 'Light Gray',
                                'from-blue-600 to-blue-800' => 'Deep Blue',
                                'from-red-600 to-red-800' => 'Deep Red',
                                'from-green-600 to-green-800' => 'Deep Green',
                                'from-purple-600 to-purple-800' => 'Deep Purple',
                            ])
                            ->default('from-gray-900 to-gray-700'),
                        Forms\Components\Select::make('bg_image')
                            ->label('Gradient Direction')
                            ->required()
                            ->options([
                                'bg-gradient-to-br' => 'Bottom Right',
                                'bg-gradient-to-bl' => 'Bottom Left',
                                'bg-gradient-to-tr' => 'Top Right',
                                'bg-gradient-to-tl' => 'Top Left',
                                'bg-gradient-to-r' => 'Right',
                                'bg-gradient-to-l' => 'Left',
                                'bg-gradient-to-t' => 'Top',
                                'bg-gradient-to-b' => 'Bottom',
                            ])
                            ->default('bg-gradient-to-br'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->size(60)
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable()
                    ->limit(40)
                    ->color('gray'),
                Tables\Columns\TextColumn::make('button_text')
                    ->label('Button')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('bg_color')
                    ->label('Background')
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        str_contains($state, 'yellow') => 'warning',
                        str_contains($state, 'gray-300') => 'gray',
                        str_contains($state, 'blue') => 'info',
                        str_contains($state, 'red') => 'danger',
                        str_contains($state, 'green') => 'success',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn(string $state): string => ucwords(str_replace(['from-', 'to-', '-'], ['', ' to ', ' '], $state))),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
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
            'index' => Pages\ListProductGrids::route('/'),
            'create' => Pages\CreateProductGrid::route('/create'),
            'edit' => Pages\EditProductGrid::route('/{record}/edit'),
        ];
    }
}
