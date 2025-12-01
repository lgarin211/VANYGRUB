<!DOCTYPE html>
<html 
    lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi min-h-screen',
        'dark' => filament()->hasDarkModeForced(),
    ])
>
<head>
    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::head.start') }}

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($favicon = filament()->getFavicon())
        <link rel="icon" href="{{ $favicon }}">
    @endif

    <title>
        {{ filled($title = strip_tags(($livewire ?? null)?->getTitle() ?? '')) ? "{$title} - " : null }}
        {{ filament()->getBrandName() }}
    </title>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::head.end') }}

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Custom SweetAlert2 theme for VanyGrub */
        .swal2-popup {
            font-family: inherit !important;
            border-radius: 12px !important;
        }
        
        .swal2-title {
            color: #800000 !important;
            font-weight: 600 !important;
        }
        
        .swal2-confirm {
            background-color: #800000 !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
        }
        
        .swal2-cancel {
            background-color: #6c757d !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 12px 24px !important;
            font-weight: 600 !important;
        }
        
        .swal2-success .swal2-success-ring {
            border-color: #800000 !important;
        }
        
        .swal2-success .swal2-success-fix {
            background-color: #800000 !important;
        }
        
        .swal2-success .swal2-success-circular-line-right {
            background-color: #800000 !important;
        }
        
        .swal2-success .swal2-success-circular-line-left {
            background-color: #800000 !important;
        }
    </style>

    @livewireStyles
    @filamentStyles
    @stack('styles')
</head>

<body class="fi-body">
    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::body.start') }}

    {{ $slot }}

    @livewire(\Filament\Livewire\Notifications::class)

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::body.end') }}

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize SweetAlert2 with VanyGrub theme
        const VanyAlert = Swal.mixin({
            customClass: {
                confirmButton: 'fi-btn fi-btn--primary',
                cancelButton: 'fi-btn fi-btn--secondary'
            },
            confirmButtonColor: '#800000',
            cancelButtonColor: '#6c757d',
            backdrop: `rgba(128, 0, 0, 0.1)`,
            allowOutsideClick: true,
            allowEscapeKey: true,
            showCloseButton: false,
            timer: null,
            timerProgressBar: false
        });

        // Toast notifications
        const VanyToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#1f2937',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Global functions to replace alert() and confirm()
        window.vanyAlert = {
            success: (title, text = null) => {
                return VanyAlert.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    confirmButtonText: 'OK'
                });
            },
            
            error: (title, text = null) => {
                return VanyAlert.fire({
                    icon: 'error',
                    title: title || 'Error!',
                    text: text,
                    confirmButtonText: 'OK'
                });
            },
            
            warning: (title, text = null) => {
                return VanyAlert.fire({
                    icon: 'warning',
                    title: title || 'Warning!',
                    text: text,
                    confirmButtonText: 'OK'
                });
            },
            
            info: (title, text = null) => {
                return VanyAlert.fire({
                    icon: 'info',
                    title: title,
                    text: text,
                    confirmButtonText: 'OK'
                });
            },
            
            confirm: (title, text = null, confirmText = 'Yes', cancelText = 'Cancel') => {
                return VanyAlert.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    reverseButtons: true
                });
            },
            
            toast: {
                success: (title) => {
                    return VanyToast.fire({
                        icon: 'success',
                        title: title
                    });
                },
                
                error: (title) => {
                    return VanyToast.fire({
                        icon: 'error',
                        title: title
                    });
                },
                
                warning: (title) => {
                    return VanyToast.fire({
                        icon: 'warning',
                        title: title
                    });
                },
                
                info: (title) => {
                    return VanyToast.fire({
                        icon: 'info',
                        title: title
                    });
                }
            }
        };

        // Override native alert and confirm (if needed)
        // Uncomment these lines if you want to completely replace native alerts
        /*
        window.alert = function(message) {
            return vanyAlert.info('Alert', message);
        };
        
        window.confirm = function(message) {
            return vanyAlert.confirm('Confirm', message).then(result => result.isConfirmed);
        };
        */

        // Listen for Livewire events and show appropriate alerts
        document.addEventListener('DOMContentLoaded', function() {
            // Browser event listeners for SweetAlert
            window.addEventListener('swal:success', event => {
                vanyAlert.success(event.detail.title, event.detail.text);
            });

            window.addEventListener('swal:error', event => {
                vanyAlert.error(event.detail.title, event.detail.text);
            });

            window.addEventListener('swal:warning', event => {
                vanyAlert.warning(event.detail.title, event.detail.text);
            });

            window.addEventListener('swal:info', event => {
                vanyAlert.info(event.detail.title, event.detail.text);
            });

            window.addEventListener('swal:confirm', event => {
                const data = event.detail;
                vanyAlert.confirm(
                    data.title || 'Are you sure?',
                    data.text || 'This action cannot be undone',
                    data.confirmText || 'Yes',
                    data.cancelText || 'Cancel'
                ).then((result) => {
                    if (result.isConfirmed) {
                        // Emit event back to Livewire component
                        Livewire.emit('handleConfirmed', data.action);
                    }
                });
            });

            // Toast event listeners
            window.addEventListener('swal:toast-success', event => {
                vanyAlert.toast.success(event.detail.title);
            });

            window.addEventListener('swal:toast-error', event => {
                vanyAlert.toast.error(event.detail.title);
            });

            window.addEventListener('swal:toast-warning', event => {
                vanyAlert.toast.warning(event.detail.title);
            });

            window.addEventListener('swal:toast-info', event => {
                vanyAlert.toast.info(event.detail.title);
            });

            // Legacy Livewire event listeners (for backward compatibility)
            if (typeof Livewire !== 'undefined') {
                // Success notifications
                Livewire.on('success', (data) => {
                    vanyAlert.toast.success(data.message || 'Operation completed successfully!');
                });
                
                // Error notifications  
                Livewire.on('error', (data) => {
                    vanyAlert.toast.error(data.message || 'An error occurred!');
                });
                
                // Warning notifications
                Livewire.on('warning', (data) => {
                    vanyAlert.toast.warning(data.message || 'Warning!');
                });
                
                // Info notifications
                Livewire.on('info', (data) => {
                    vanyAlert.toast.info(data.message || 'Information');
                });
                
                // Confirmation dialogs
                Livewire.on('confirm', (data) => {
                    vanyAlert.confirm(
                        data.title || 'Are you sure?',
                        data.text || 'This action cannot be undone',
                        data.confirmText || 'Yes',
                        data.cancelText || 'Cancel'
                    ).then((result) => {
                        if (result.isConfirmed) {
                            // Emit event back to Livewire component
                            Livewire.emit('confirmed', data.action);
                        }
                    });
                });
            }
        });
    </script>

    @livewireScripts
    @filamentScripts
    @stack('scripts')
</body>
</html>