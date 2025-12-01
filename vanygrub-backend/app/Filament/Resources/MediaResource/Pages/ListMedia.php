<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Traits\WithSweetAlert;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListMedia extends ListRecords
{
    use WithSweetAlert;

    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Upload Media')
                ->icon('heroicon-o-plus')
                ->successNotification(null), // Disable default notification
        ];
    }

    #[On('handleConfirmed')]
    public function handleConfirmed($action)
    {
        switch ($action) {
            case 'bulkDelete':
                $this->bulkDeleteConfirmed();
                break;
        }
    }

    public function bulkDeleteConfirmed()
    {
        try {
            $selectedRecords = $this->getSelectedTableRecords();
            $count = $selectedRecords->count();

            foreach ($selectedRecords as $record) {
                // Delete file from storage
                if ($record->path && \Illuminate\Support\Facades\Storage::disk('public')->exists($record->path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($record->path);
                }

                $record->delete();
            }

            $this->showSuccessToast("Successfully deleted {$count} media file(s)");
            $this->resetTableSelection();

        } catch (\Exception $e) {
            $this->showError(
                'Delete Error',
                'Failed to delete media files: ' . $e->getMessage()
            );
        }
    }

    public function bulkDelete()
    {
        $selectedCount = count($this->getSelectedTableRecords());

        if ($selectedCount === 0) {
            $this->showWarning('No Selection', 'Please select media files to delete');
            return;
        }

        $this->showConfirm(
            'Delete Media Files?',
            "Are you sure you want to delete {$selectedCount} media file(s)? This action cannot be undone and will permanently remove the files from storage.",
            'bulkDelete',
            'Yes, delete them!',
            'Cancel'
        );
    }
}
