<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerReviewResource\Pages;
use App\Filament\Resources\CustomerReviewResource\RelationManagers;
use App\Models\CustomerReview;
use App\Models\Order;
use App\Services\ReviewService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerReviewResource extends Resource
{
    protected static ?string $model = CustomerReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Customer Reviews';

    protected static ?string $modelLabel = 'Customer Review';

    protected static ?string $pluralModelLabel = 'Customer Reviews';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Information')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Order')
                            ->options(Order::all()->pluck('order_number', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('customer_name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('customer_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Review Content')
                    ->schema([
                        Forms\Components\FileUpload::make('photo_url')
                            ->label('Customer Photo')
                            ->image()
                            ->imageEditor()
                            ->directory('customer-reviews')
                            ->disk('public')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('800')
                            ->maxSize(2048)
                            ->required(),

                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '1 Star - Poor',
                                2 => '2 Stars - Fair',
                                3 => '3 Stars - Good',
                                4 => '4 Stars - Very Good',
                                5 => '5 Stars - Excellent'
                            ])
                            ->required()
                            ->default(5),

                        Forms\Components\Textarea::make('review_text')
                            ->label('Review Text')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Review Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approved for Display')
                            ->helperText('Approved reviews will be shown on the website')
                            ->default(false),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Review')
                            ->helperText('Featured reviews will be highlighted on the homepage')
                            ->default(false),

                        Forms\Components\TextInput::make('review_token')
                            ->label('Review Token')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('Photo')
                    ->circular()
                    ->size(60)
                    ->getStateUsing(function (CustomerReview $record): ?string {
                        return $record->photo_url ? asset('storage/' . $record->photo_url) : null;
                    }),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn(string $state): string => str_repeat('⭐', (int) $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('review_text')
                    ->label('Review')
                    ->limit(50)
                    ->tooltip(fn(CustomerReview $record): string => $record->review_text ?? 'No review text'),

                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approval Status'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),

                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        '5' => '5 Stars',
                        '4' => '4 Stars',
                        '3' => '3 Stars',
                        '2' => '2 Stars',
                        '1' => '1 Star'
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (CustomerReview $record) {
                        $record->update(['is_approved' => true]);

                        Notification::make()
                            ->title('Review approved successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(CustomerReview $record): bool => !$record->is_approved),

                Tables\Actions\Action::make('feature')
                    ->label('Feature')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function (CustomerReview $record) {
                        $record->update(['is_featured' => !$record->is_featured]);

                        $message = $record->is_featured ? 'Featured' : 'Unfeatured';
                        Notification::make()
                            ->title("Review {$message} successfully")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_approved' => true]);

                            Notification::make()
                                ->title(count($records) . ' reviews approved')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('feature')
                        ->label('Feature Selected')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each->update(['is_featured' => true]);

                            Notification::make()
                                ->title(count($records) . ' reviews featured')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCustomerReviews::route('/'),
            'create' => Pages\CreateCustomerReview::route('/create'),
            'edit' => Pages\EditCustomerReview::route('/{record}/edit'),
        ];
    }
}
