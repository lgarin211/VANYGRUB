<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\BadgeEntry;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Order Information')
                    ->schema([
                        TextEntry::make('order_number')->label('Order Number'),
                        BadgeEntry::make('status')->label('Status'),
                        BadgeEntry::make('order_status')->label('Order Status'),
                        TextEntry::make('created_at')->label('Order Date')->dateTime(),
                        TextEntry::make('tracking_number')->label('Tracking Number'),
                    ])
                    ->columns(2),
                Section::make('Customer Information')
                    ->schema([
                        TextEntry::make('customer_name')->label('Name'),
                        TextEntry::make('customer_email')->label('Email'),
                        TextEntry::make('customer_phone')->label('Phone'),
                    ])
                    ->columns(2),
                Section::make('Shipping Information')
                    ->schema([
                        TextEntry::make('shipping_address')->label('Address'),
                        TextEntry::make('shipping_city')->label('City'),
                        TextEntry::make('shipping_postal_code')->label('Postal Code'),
                    ])
                    ->columns(2),
                Section::make('Financial Information')
                    ->schema([
                        TextEntry::make('subtotal')->money('IDR')->label('Subtotal'),
                        TextEntry::make('discount_amount')->money('IDR')->label('Discount'),
                        TextEntry::make('shipping_cost')->money('IDR')->label('Shipping'),
                        TextEntry::make('tax_amount')->money('IDR')->label('Tax'),
                        TextEntry::make('total_amount')->money('IDR')->label('Total')->size('lg')->weight('bold'),
                    ])
                    ->columns(2),
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('notes')->label('Notes'),
                    ]),
            ]);
    }
}
