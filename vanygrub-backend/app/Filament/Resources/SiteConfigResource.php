<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteConfigResource\Pages;
use App\Models\SiteConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;

class SiteConfigResource extends Resource
{
    protected static ?string $model = SiteConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Configuration';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Configuration Details')
                    ->description('Manage site configuration values')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('group')
                                    ->label('Configuration Group')
                                    ->options([
                                        'meta' => '🔍 META (SEO & Page Info)',
                                        'hero_section' => '🎭 HERO SECTION (Landing Page)',
                                        'colors' => '🎨 COLORS & BRANDING',
                                        'animation' => '⚡ ANIMATION SETTINGS',
                                        'contact' => '📞 CONTACT INFORMATION',
                                        'social_media' => '📱 SOCIAL MEDIA LINKS',
                                        'navigation' => '🧭 NAVIGATION MENUS',
                                        'site_config' => '🌐 GENERAL SITE SETTINGS'
                                    ])
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => 
                                        $set('key', null)
                                    ),

                                Forms\Components\Select::make('key')
                                    ->label('Configuration Key')
                                    ->options(function (callable $get) {
                                        $group = $get('group');
                                        return match ($group) {
                                            'meta' => [
                                                'title' => 'Page Title',
                                                'description' => 'Meta Description',
                                                'keywords' => 'Keywords (Array)'
                                            ],
                                            'hero_section' => [
                                                'title' => 'Hero Title',
                                                'subtitle' => 'Hero Subtitle',
                                                'description' => 'Hero Description'
                                            ],
                                            'colors' => [
                                                'primary' => 'Primary Color',
                                                'secondary' => 'Secondary Color',
                                                'accent' => 'Accent Color',
                                                'gradient' => 'Gradient CSS'
                                            ],
                                            'animation' => [
                                                'carousel_interval' => 'Carousel Interval (ms)',
                                                'transition_duration' => 'Transition Duration (ms)'
                                            ],
                                            'contact' => [
                                                'email' => 'Contact Email',
                                                'phone' => 'Phone Number',
                                                'address' => 'Physical Address'
                                            ],
                                            'social_media' => [
                                                'facebook' => 'Facebook URL',
                                                'instagram' => 'Instagram URL',
                                                'twitter' => 'Twitter URL',
                                                'youtube' => 'YouTube URL'
                                            ],
                                            'site_config' => [
                                                'site_name' => 'Site Name',
                                                'tagline' => 'Site Tagline',
                                                'description' => 'Site Description'
                                            ],
                                            default => []
                                        };
                                    })
                                    ->required()
                                    ->reactive(),
                            ]),

                        Forms\Components\Select::make('type')
                            ->label('Value Type')
                            ->options([
                                'text' => '📝 Text',
                                'textarea' => '📄 Long Text',
                                'url' => '🔗 URL',
                                'email' => '✉️ Email',
                                'color' => '🎨 Color',
                                'number' => '🔢 Number',
                                'array' => '📋 Array/List',
                                'json' => '📊 JSON Object'
                            ])
                            ->required()
                            ->reactive()
                            ->default('text'),

                        // Dynamic value field based on type
                        Forms\Components\TextInput::make('value_text')
                            ->label('Value')
                            ->visible(fn (callable $get) => in_array($get('type'), ['text', 'url', 'email']))
                            ->required(fn (callable $get) => in_array($get('type'), ['text', 'url', 'email']))
                            ->email(fn (callable $get) => $get('type') === 'email')
                            ->url(fn (callable $get) => $get('type') === 'url')
                            ->dehydrateStateUsing(fn ($state) => $state),

                        Forms\Components\Textarea::make('value_textarea')
                            ->label('Value')
                            ->visible(fn (callable $get) => $get('type') === 'textarea')
                            ->required(fn (callable $get) => $get('type') === 'textarea')
                            ->rows(4)
                            ->dehydrateStateUsing(fn ($state) => $state),

                        Forms\Components\ColorPicker::make('value_color')
                            ->label('Color Value')
                            ->visible(fn (callable $get) => $get('type') === 'color')
                            ->required(fn (callable $get) => $get('type') === 'color')
                            ->dehydrateStateUsing(fn ($state) => $state),

                        Forms\Components\TextInput::make('value_number')
                            ->label('Number Value')
                            ->visible(fn (callable $get) => $get('type') === 'number')
                            ->required(fn (callable $get) => $get('type') === 'number')
                            ->numeric()
                            ->dehydrateStateUsing(fn ($state) => (int)$state),

                        Forms\Components\TagsInput::make('value_array')
                            ->label('Array Values')
                            ->visible(fn (callable $get) => $get('type') === 'array')
                            ->required(fn (callable $get) => $get('type') === 'array')
                            ->dehydrateStateUsing(fn ($state) => $state),

                        Forms\Components\Textarea::make('value_json')
                            ->label('JSON Value')
                            ->visible(fn (callable $get) => $get('type') === 'json')
                            ->required(fn (callable $get) => $get('type') === 'json')
                            ->rows(6)
                            ->helperText('Enter valid JSON format')
                            ->dehydrateStateUsing(fn ($state) => json_decode($state, true)),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Optional description of this configuration')
                            ->rows(2),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Enable/disable this configuration'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'meta' => 'primary',
                        'hero_section' => 'success',
                        'colors' => 'warning',
                        'animation' => 'info',
                        'contact' => 'secondary',
                        'social_media' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->limit(50)
                    ->formatStateUsing(function ($state, $record) {
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return is_string($state) ? $state : json_encode($state);
                    })
                    ->tooltip(function ($state, $record) {
                        if (is_array($state)) {
                            return 'Array: ' . json_encode($state, JSON_PRETTY_PRINT);
                        }
                        return is_string($state) ? $state : json_encode($state, JSON_PRETTY_PRINT);
                    }),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'meta' => 'META',
                        'hero_section' => 'HERO SECTION',
                        'colors' => 'COLORS',
                        'animation' => 'ANIMATION',
                        'contact' => 'CONTACT',
                        'social_media' => 'SOCIAL MEDIA',
                        'navigation' => 'NAVIGATION',
                        'site_config' => 'SITE CONFIG'
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group')
            ->groups([
                Tables\Grouping\Group::make('group')
                    ->label('Configuration Group'),
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
            'index' => Pages\ListSiteConfigs::route('/'),
            'create' => Pages\CreateSiteConfig::route('/create'),
            'edit' => Pages\EditSiteConfig::route('/{record}/edit'),
        ];
    }

    // Custom method to handle value field hydration
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->processValueField($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->processValueField($data);
    }

    private function processValueField(array $data): array
    {
        $type = $data['type'] ?? 'text';
        
        $data['value'] = match ($type) {
            'text', 'url', 'email' => $data['value_text'] ?? '',
            'textarea' => $data['value_textarea'] ?? '',
            'color' => $data['value_color'] ?? '',
            'number' => (int)($data['value_number'] ?? 0),
            'array' => $data['value_array'] ?? [],
            'json' => $data['value_json'] ?? null,
            default => $data['value_text'] ?? ''
        };

        // Remove temporary fields
        unset($data['value_text'], $data['value_textarea'], $data['value_color'], 
              $data['value_number'], $data['value_array'], $data['value_json']);

        return $data;
    }
}