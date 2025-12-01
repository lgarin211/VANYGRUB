<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Hidden;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Manajemen Toko';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Order')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Nomor Order')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn() => 'ORD-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT)),

                        Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Terkirim',
                                'cancelled' => 'Dibatalkan'
                            ])
                            ->default('pending')
                            ->required()
                            ->searchable(),
                    ])->columns(2),

                Section::make('Informasi Customer')
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('customer_email')
                            ->label('Email Customer')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        Textarea::make('shipping_address')
                            ->label('Alamat Pengiriman')
                            ->required()
                            ->rows(3),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2),
                    ])->columns(2),

                Section::make('Informasi Pembayaran')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('discount_amount')
                            ->label('Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        TextInput::make('total_amount')
                            ->label('Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ])->columns(3),

                Hidden::make('user_id')->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Nomor Order')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Nama Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-cog' => 'processing',
                        'heroicon-o-truck' => 'shipped',
                        'heroicon-o-check-circle' => 'delivered',
                        'heroicon-o-x-circle' => 'cancelled',
                    ]),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Terkirim',
                        'cancelled' => 'Dibatalkan'
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->form([
                        Select::make('status')
                            ->label('Status Baru')
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Terkirim',
                                'cancelled' => 'Dibatalkan'
                            ])
                            ->required()
                            ->default(fn(Order $record) => $record->status),

                        Textarea::make('notes')
                            ->label('Catatan Update')
                            ->placeholder('Opsional: Tambahkan catatan untuk update status')
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => $data['status']
                        ]);

                        // Update notes if provided
                        if (!empty($data['notes'])) {
                            $record->update([
                                'notes' => $record->notes . "\n\n" . date('d/m/Y H:i') . " - Status diubah dari {$oldStatus} ke {$data['status']}: " . $data['notes']
                            ]);
                        }

                        Notification::make()
                            ->title('Status berhasil diupdate')
                            ->body("Order {$record->order_number} statusnya diubah menjadi {$data['status']}")
                            ->success()
                            ->send();
                    }),

                Action::make('printInvoice')
                    ->label('Print Invoice')
                    ->icon('heroicon-o-printer')
                    ->color('secondary')
                    ->url(fn(Order $record): string => route('orders.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->form([
                            Select::make('status')
                                ->label('Status Baru')
                                ->options([
                                    'pending' => 'Menunggu Pembayaran',
                                    'processing' => 'Diproses',
                                    'shipped' => 'Dikirim',
                                    'delivered' => 'Terkirim',
                                    'cancelled' => 'Dibatalkan'
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function (Order $record) use ($data) {
                                $record->update(['status' => $data['status']]);
                            });

                            Notification::make()
                                ->title('Status berhasil diupdate')
                                ->body($records->count() . ' order berhasil diupdate ke status ' . $data['status'])
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
