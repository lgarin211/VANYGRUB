// utils/mediaUpload.ts - Utility functions for media upload in Next.js

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'https://vanygroup.id/api';

export interface MediaUploadResponse {
  success: boolean;
  message: string;
  data?: {
    id: number;
    filename: string;
    original_name: string;
    path: string;
    url: string;
    size: number;
    mime_type: string;
    type: 'image' | 'video' | 'document';
    folder: string;
    formatted_size: string;
  };
  errors?: any;
}

export interface MultipleMediaUploadResponse {
  success: boolean;
  message: string;
  data?: MediaUploadResponse['data'][];
  errors?: any;
}

/**
 * Upload single file to media gallery
 */
export async function uploadSingleMedia(
  file: File,
  type: 'image' | 'video' | 'document',
  folder: string = 'general'
): Promise<MediaUploadResponse> {
  const formData = new FormData();
  formData.append('file', file);
  formData.append('type', type);
  formData.append('folder', folder);

  try {
    const response = await fetch(`${API_BASE_URL}/vny/media/upload`, {
      method: 'POST',
      body: formData,
    });

    return await response.json();
  } catch (error) {
    return {
      success: false,
      message: 'Upload failed: ' + (error as Error).message,
    };
  }
}

/**
 * Upload multiple files to media gallery
 */
export async function uploadMultipleMedia(
  files: File[],
  type: 'image' | 'video' | 'document',
  folder: string = 'general'
): Promise<MultipleMediaUploadResponse> {
  const formData = new FormData();

  files.forEach((file) => {
    formData.append('files[]', file);
  });
  formData.append('type', type);
  formData.append('folder', folder);

  try {
    const response = await fetch(`${API_BASE_URL}/vny/media/upload-multiple`, {
      method: 'POST',
      body: formData,
    });

    return await response.json();
  } catch (error) {
    return {
      success: false,
      message: 'Upload failed: ' + (error as Error).message,
    };
  }
}

/**
 * Delete media file
 */
export async function deleteMedia(path: string): Promise<MediaUploadResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/vny/media/delete`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ path }),
    });

    return await response.json();
  } catch (error) {
    return {
      success: false,
      message: 'Delete failed: ' + (error as Error).message,
    };
  }
}

/**
 * Get media files list
 */
export async function getMediaList(
  type: 'image' | 'video' | 'document' = 'image',
  folder: string = 'general',
  page: number = 1,
  perPage: number = 20
) {
  try {
    const params = new URLSearchParams({
      type,
      folder,
      page: page.toString(),
      per_page: perPage.toString(),
    });

    const response = await fetch(`${API_BASE_URL}/vny/media/list?${params}`);
    return await response.json();
  } catch (error) {
    return {
      success: false,
      message: 'Failed to get media list: ' + (error as Error).message,
    };
  }
}

/**
 * Validate file before upload
 */
export function validateFile(
  file: File,
  type: 'image' | 'video' | 'document',
  maxSize: number = 10 * 1024 * 1024 // 10MB default
): { valid: boolean; message?: string } {
  // Check file size
  if (file.size > maxSize) {
    return {
      valid: false,
      message: `File size must be less than ${maxSize / 1024 / 1024}MB`,
    };
  }

  // Check file type
  const allowedTypes = {
    image: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
    video: ['video/mp4', 'video/avi', 'video/quicktime', 'video/webm'],
    document: [
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'text/plain',
    ],
  };

  if (!allowedTypes[type].includes(file.type)) {
    return {
      valid: false,
      message: `Invalid file type. Allowed types for ${type}: ${allowedTypes[type].join(', ')}`,
    };
  }

  return { valid: true };
}
