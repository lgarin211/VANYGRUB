import Swal from 'sweetalert2';

// Custom SweetAlert configurations
export const showSuccess = (title: string, text?: string, timer: number = 3000) => {
  return Swal.fire({
    icon: 'success',
    title,
    text,
    timer,
    timerProgressBar: true,
    showConfirmButton: false,
    toast: true,
    position: 'top-end',
    background: '#f0f9ff',
    color: '#065f46',
    iconColor: '#10b981',
  });
};

export const showError = (title: string, text?: string) => {
  return Swal.fire({
    icon: 'error',
    title,
    text,
    showConfirmButton: true,
    confirmButtonColor: '#dc2626',
    background: '#fef2f2',
    color: '#7f1d1d',
  });
};

export const showWarning = (title: string, text?: string) => {
  return Swal.fire({
    icon: 'warning',
    title,
    text,
    showConfirmButton: true,
    confirmButtonColor: '#d97706',
    background: '#fffbeb',
    color: '#92400e',
  });
};

export const showInfo = (title: string, text?: string) => {
  return Swal.fire({
    icon: 'info',
    title,
    text,
    showConfirmButton: true,
    confirmButtonColor: '#2563eb',
    background: '#eff6ff',
    color: '#1e40af',
  });
};

export const showConfirm = (title: string, text: string) => {
  return Swal.fire({
    icon: 'question',
    title,
    text,
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    background: '#f9fafb',
  });
};

export const showCart = (title: string, text: string) => {
  return Swal.fire({
    icon: 'success',
    title,
    html: `<div class="text-left"><p class="mb-2">${text}</p></div>`,
    showConfirmButton: true,
    confirmButtonColor: '#dc2626',
    confirmButtonText: 'Lihat Keranjang',
    showCancelButton: true,
    cancelButtonColor: '#6b7280',
    cancelButtonText: 'Lanjut Belanja',
    background: '#f0fdf4',
    color: '#166534',
  });
};

export const showOrderSuccess = (orderCode: string, customMessage?: string, whatsappLink?: string) => {
  const defaultMessage = 'Silakan cek WhatsApp dan gunakan link untuk melacak pesanan Anda.';
  const message = customMessage || defaultMessage;
  
  return Swal.fire({
    icon: 'success',
    title: 'Pesanan Berhasil Dibuat!',
    html: `
      <div class="text-left space-y-3">
        <p><strong>Kode Pesanan:</strong> ${orderCode}</p>
        <p class="text-sm text-gray-600">${message}</p>
        ${whatsappLink ? `<a href="${whatsappLink}" class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg mt-3 hover:bg-green-600 transition-colors">Buka WhatsApp</a>` : ''}
      </div>
    `,
    showConfirmButton: true,
    confirmButtonColor: '#dc2626',
    confirmButtonText: 'Tutup',
    allowOutsideClick: false,
    background: '#f0fdf4',
    color: '#166534',
    width: '400px',
  });
};