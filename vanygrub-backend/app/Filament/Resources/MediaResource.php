<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Filament\Resources\MediaResource\RelationManagers;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $pluralLabel = 'Media Gallery';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Upload Media')
                    ->description('Upload your media files and fill in the details')
                    ->schema([
                        FileUpload::make('file')
                            ->label('📁 Select File to Upload')
                            ->required()
                            ->directory('media/temp')
                            ->disk('public')
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes([
                                // Images
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                                'image/gif',
                                'image/webp',
                                'image/svg+xml',
                                'image/bmp',
                                // Videos
                                'video/mp4',
                                'video/avi',
                                'video/quicktime',
                                'video/x-msvideo',
                                'video/webm',
                                'video/x-ms-wmv',
                                'video/x-flv',
                                'video/ogg',
                                // Documents
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'text/plain',
                                'application/rtf'
                            ])
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->helperText('Max 10MB. Supported: Images (JPEG, PNG, GIF, WebP), Videos (MP4, WebM, MOV), Documents (PDF, DOC, DOCX, TXT)')
                            ->reactive()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    // Handle both single file and array of files
                                    $filePath = is_array($state) ? $state[0] : $state;

                                    if (!$filePath)
                                        return;

                                    // Auto-fill original name first
                                    $originalName = pathinfo($filePath, PATHINFO_BASENAME);
                                    $set('original_name', $originalName);

                                    // Get file extension for type detection
                                    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                                    // If no extension, try to detect from temp file or set default
                                    if (empty($extension)) {
                                        try {
                                            $mimeType = Storage::disk('public')->mimeType($filePath);
                                            
                                            // Simple MIME to extension mapping
                                            $mimeToExt = [
                                                'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif',
                                                'image/webp' => 'webp', 'video/mp4' => 'mp4', 'video/avi' => 'avi',
                                                'application/pdf' => 'pdf', 'text/plain' => 'txt'
                                            ];
                                            
                                            $extension = $mimeToExt[$mimeType] ?? 'bin';
                                        } catch (\Exception $e) {
                                            $extension = 'bin'; // fallback
                                        }
                                    }

                                    // Define file types by extension (PRIORITY METHOD)
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff', 'ico'];
                                    $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'ogv', 'mp3', 'wav'];
                                    $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];

                                    // FORCE type detection based on extension - THIS IS THE PRIMARY METHOD
                                    $detectedType = 'document'; // default fallback
                    
                                    if (in_array($extension, $imageExtensions)) {
                                        $detectedType = 'image';
                                    } elseif (in_array($extension, $videoExtensions)) {
                                        $detectedType = 'video';
                                    } elseif (in_array($extension, $documentExtensions)) {
                                        $detectedType = 'document';
                                    }

                                    // IMMEDIATELY set the detected type
                                    $set('type', $detectedType);

                                    // Generate filename - ALWAYS ensure extension is included
                                    $filename = pathinfo($originalName, PATHINFO_FILENAME);
                                    $sluggedName = Str::slug($filename) . '-' . time();
                                    
                                    // CRITICAL: Always append the extension
                                    $finalFilename = $sluggedName . '.' . $extension;
                                    $set('filename', $finalFilename);

                                    // Auto-set folder based on current date
                                    $set('folder', now()->format('Y-m-d'));
                                }
                            }),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('📂 Media Type')
                                    ->options([
                                        'image' => '🖼️ Image',
                                        'video' => '🎥 Video',
                                        'document' => '📄 Document',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->dehydrated(),
                                Forms\Components\Select::make('folder')
                                    ->label('📁 Folder')
                                    ->required()
                                    ->default('general')
                                    ->options([
                                        'general' => '📁 General',
                                        'gallery' => '🖼️ Gallery',
                                        'products' => '🛍️ Products',
                                        'heroes' => '🎭 Heroes',
                                        'banners' => '📢 Banners',
                                        'documents' => '📄 Documents',
                                        'videos' => '🎥 Videos'
                                    ])
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Folder Name')
                                            ->required()
                                            ->maxLength(50)
                                            ->alpha()
                                    ]),
                            ]),
                    ]),

                Section::make('📝 File Details & Metadata')
                    ->description('File information will be auto-generated, you can add optional metadata')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('filename')
                                    ->label('🏷️ Generated Filename')
                                    ->maxLength(255)
                                    ->helperText('Auto-generated unique filename')
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('original_name')
                                    ->label('📄 Original Filename')
                                    ->maxLength(255)
                                    ->helperText('Your original file name')
                                    ->disabled()
                                    ->dehydrated(),
                            ]),

                        Forms\Components\TextInput::make('alt_text')
                            ->label('🏷️ Alt Text (SEO)')
                            ->maxLength(255)
                            ->helperText('Alternative text for images - important for SEO & accessibility')
                            ->placeholder('Describe what\'s in the image...'),

                        Forms\Components\Textarea::make('caption')
                            ->label('💬 Caption/Description')
                            ->rows(3)
                            ->helperText('Optional caption or description for your media')
                            ->placeholder('Add a caption or description...'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('url')
                    ->label('Preview')
                    ->height(60)
                    ->width(60)
                    ->visibility('private')
                    ->getStateUsing(function (Media $record) {
                        return $record->is_image ? $record->url : null;
                    }),
                TextColumn::make('filename')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('original_name')
                    ->label('Original Name')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'image' => 'success',
                        'video' => 'warning',
                        'document' => 'info',
                    }),
                TextColumn::make('folder')
                    ->sortable(),
                TextColumn::make('formatted_size')
                    ->label('Size'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('url')
                    ->label('URL')
                    ->copyable()
                    ->limit(50)
                    ->tooltip('Click to copy'),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'image' => 'Images',
                        'video' => 'Videos',
                        'document' => 'Documents',
                    ]),
                SelectFilter::make('folder')
                    ->options(function () {
                        return Media::distinct('folder')->pluck('folder', 'folder')->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Media $record) {
                        // Delete file from storage when deleting record
                        if (Storage::disk('public')->exists($record->path)) {
                            Storage::disk('public')->delete($record->path);
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Delete files from storage when bulk deleting
                            foreach ($records as $record) {
                                if (Storage::disk('public')->exists($record->path)) {
                                    Storage::disk('public')->delete($record->path);
                                }
                            }
                        }),
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
