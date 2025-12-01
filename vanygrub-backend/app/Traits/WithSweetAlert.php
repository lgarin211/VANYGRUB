<?php

namespace App\Traits;

trait WithSweetAlert
{
    /**
     * Show success alert
     */
    public function showSuccess($title, $text = null)
    {
        $this->dispatchBrowserEvent('swal:success', [
            'title' => $title,
            'text' => $text,
        ]);
    }

    /**
     * Show error alert
     */
    public function showError($title, $text = null)
    {
        $this->dispatchBrowserEvent('swal:error', [
            'title' => $title,
            'text' => $text,
        ]);
    }

    /**
     * Show warning alert
     */
    public function showWarning($title, $text = null)
    {
        $this->dispatchBrowserEvent('swal:warning', [
            'title' => $title,
            'text' => $text,
        ]);
    }

    /**
     * Show info alert
     */
    public function showInfo($title, $text = null)
    {
        $this->dispatchBrowserEvent('swal:info', [
            'title' => $title,
            'text' => $text,
        ]);
    }

    /**
     * Show confirmation dialog
     */
    public function showConfirm($title, $text = null, $action = null, $confirmText = 'Yes', $cancelText = 'Cancel')
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => $title,
            'text' => $text,
            'action' => $action,
            'confirmText' => $confirmText,
            'cancelText' => $cancelText,
        ]);
    }

    /**
     * Show success toast
     */
    public function showSuccessToast($title)
    {
        $this->dispatchBrowserEvent('swal:toast-success', [
            'title' => $title,
        ]);
    }

    /**
     * Show error toast
     */
    public function showErrorToast($title)
    {
        $this->dispatchBrowserEvent('swal:toast-error', [
            'title' => $title,
        ]);
    }

    /**
     * Show warning toast
     */
    public function showWarningToast($title)
    {
        $this->dispatchBrowserEvent('swal:toast-warning', [
            'title' => $title,
        ]);
    }

    /**
     * Show info toast
     */
    public function showInfoToast($title)
    {
        $this->dispatchBrowserEvent('swal:toast-info', [
            'title' => $title,
        ]);
    }

    /**
     * Handle confirmed action
     */
    public function handleConfirmed($action)
    {
        // This method should be overridden in the component
        // to handle specific actions after confirmation
        switch ($action) {
            case 'delete':
                $this->delete();
                break;
            case 'save':
                $this->save();
                break;
            default:
                // Handle other actions
                break;
        }
    }

    /**
     * Show delete confirmation
     */
    public function confirmDelete($message = 'This action cannot be undone!')
    {
        $this->showConfirm(
            'Are you sure?',
            $message,
            'delete',
            'Yes, delete it!',
            'Cancel'
        );
    }

    /**
     * Show save confirmation
     */
    public function confirmSave($message = 'Do you want to save the changes?')
    {
        $this->showConfirm(
            'Save Changes?',
            $message,
            'save',
            'Save',
            'Cancel'
        );
    }
}
