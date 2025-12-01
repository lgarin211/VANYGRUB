<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Product;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'product.name';

    protected static ?string $title = 'Item Order';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $product = Product::find($state);
                        if ($product) {
                            $set('price', $product->price);
                            $set('total', $product->price * 1); // Default quantity 1
                        }
                    }),

                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, callable $set) {
                        $price = $get('price') ?? 0;
                        $set('total', $price * $state);
                    }),

                Forms\Components\TextInput::make('price')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, callable $set) {
                        $quantity = $get('quantity') ?? 1;
                        $set('total', $state * $quantity);
                    }),

                Forms\Components\TextInput::make('total')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('product.image_url')
                    ->label('Gambar')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->alignCenter()
                    ->badge(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->weight('bold'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Ensure total is calculated correctly
                        $data['total'] = $data['price'] * $data['quantity'];
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Ensure total is calculated correctly on edit
                        $data['total'] = $data['price'] * $data['quantity'];
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
