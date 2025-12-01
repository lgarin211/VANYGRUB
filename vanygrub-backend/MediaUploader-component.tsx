// components/MediaUploader.tsx - React component for file upload

import React, { useState, useRef } from 'react';
import { uploadSingleMedia, uploadMultipleMedia, validateFile, MediaUploadResponse } from '../utils/mediaUpload';

interface MediaUploaderProps {
  type: 'image' | 'video' | 'document';
  folder?: string;
  multiple?: boolean;
  maxFiles?: number;
  onUploadComplete?: (response: MediaUploadResponse | MediaUploadResponse[]) => void;
  onUploadProgress?: (progress: number) => void;
  className?: string;
}

export const MediaUploader: React.FC<MediaUploaderProps> = ({
  type,
  folder = 'general',
  multiple = false,
  maxFiles = 5,
  onUploadComplete,
  onUploadProgress,
  className,
}) => {
  const [uploading, setUploading] = useState(false);
  const [uploadProgress, setUploadProgress] = useState(0);
  const [dragActive, setDragActive] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleFiles = async (files: FileList) => {
    const fileArray = Array.from(files);

    // Limit number of files if multiple upload
    const filesToUpload = multiple ? fileArray.slice(0, maxFiles) : [fileArray[0]];

    // Validate files
    for (const file of filesToUpload) {
      const validation = validateFile(file, type);
      if (!validation.valid) {
        alert(validation.message);
        return;
      }
    }

    setUploading(true);
    setUploadProgress(0);

    try {
      if (multiple && filesToUpload.length > 1) {
        const response = await uploadMultipleMedia(filesToUpload, type, folder);
        onUploadComplete?.(response.data || []);
      } else {
        const response = await uploadSingleMedia(filesToUpload[0], type, folder);
        onUploadComplete?.(response);
      }
    } catch (error) {
      console.error('Upload error:', error);
      alert('Upload failed. Please try again.');
    } finally {
      setUploading(false);
      setUploadProgress(0);
    }
  };

  const handleDrop = (e: React.DragEvent<HTMLDivElement>) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);

    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
      handleFiles(e.dataTransfer.files);
    }
  };

  const handleDrag = (e: React.DragEvent<HTMLDivElement>) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === 'dragenter' || e.type === 'dragover') {
      setDragActive(true);
    } else if (e.type === 'dragleave') {
      setDragActive(false);
    }
  };

  const openFileDialog = () => {
    fileInputRef.current?.click();
  };

  const getAcceptedTypes = () => {
    const typeMap = {
      image: 'image/*',
      video: 'video/*',
      document: '.pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx',
    };
    return typeMap[type];
  };

  return (
    <div className={`media-uploader ${className || ''}`}>
      <div
        className={`upload-area ${dragActive ? 'drag-active' : ''} ${uploading ? 'uploading' : ''}`}
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
        onClick={openFileDialog}
        style={{
          border: '2px dashed #ddd',
          borderRadius: '8px',
          padding: '2rem',
          textAlign: 'center',
          cursor: uploading ? 'not-allowed' : 'pointer',
          backgroundColor: dragActive ? '#f0f0f0' : 'transparent',
          transition: 'all 0.3s ease',
        }}
      >
        <input
          ref={fileInputRef}
          type="file"
          accept={getAcceptedTypes()}
          multiple={multiple}
          onChange={(e) => e.target.files && handleFiles(e.target.files)}
          style={{ display: 'none' }}
          disabled={uploading}
        />

        {uploading ? (
          <div>
            <div>Uploading...</div>
            <div className="progress-bar" style={{ width: '100%', height: '4px', backgroundColor: '#e0e0e0', marginTop: '1rem' }}>
              <div
                className="progress-fill"
                style={{
                  width: `${uploadProgress}%`,
                  height: '100%',
                  backgroundColor: '#007bff',
                  transition: 'width 0.3s ease',
                }}
              />
            </div>
          </div>
        ) : (
          <div>
            <div style={{ fontSize: '3rem', marginBottom: '1rem' }}>
              {type === 'image' ? '🖼️' : type === 'video' ? '🎥' : '📄'}
            </div>
            <div>
              {dragActive
                ? `Drop ${type}s here`
                : `Click to select or drag and drop ${type}s`}
            </div>
            {multiple && (
              <div style={{ fontSize: '0.9rem', color: '#666', marginTop: '0.5rem' }}>
                You can upload up to {maxFiles} files at once
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

// Example usage component
export const MediaGalleryExample: React.FC = () => {
  const [uploadedMedia, setUploadedMedia] = useState<any[]>([]);

  const handleUploadComplete = (response: any) => {
    if (Array.isArray(response)) {
      setUploadedMedia(prev => [...prev, ...response]);
    } else if (response.data) {
      setUploadedMedia(prev => [...prev, response.data]);
    }
  };

  return (
    <div className="media-gallery-example">
      <h2>Media Upload Gallery</h2>

      {/* Image Upload */}
      <div className="upload-section">
        <h3>Upload Images</h3>
        <MediaUploader
          type="image"
          folder="gallery"
          multiple={true}
          maxFiles={5}
          onUploadComplete={handleUploadComplete}
          className="mb-4"
        />
      </div>

      {/* Video Upload */}
      <div className="upload-section">
        <h3>Upload Videos</h3>
        <MediaUploader
          type="video"
          folder="videos"
          multiple={false}
          onUploadComplete={handleUploadComplete}
          className="mb-4"
        />
      </div>

      {/* Document Upload */}
      <div className="upload-section">
        <h3>Upload Documents</h3>
        <MediaUploader
          type="document"
          folder="documents"
          multiple={true}
          maxFiles={3}
          onUploadComplete={handleUploadComplete}
          className="mb-4"
        />
      </div>

      {/* Display uploaded media */}
      {uploadedMedia.length > 0 && (
        <div className="uploaded-media">
          <h3>Uploaded Media</h3>
          <div className="media-grid" style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))', gap: '1rem' }}>
            {uploadedMedia.map((media, index) => (
              <div key={index} className="media-item" style={{ border: '1px solid #ddd', padding: '1rem', borderRadius: '8px' }}>
                {media.type === 'image' && (
                  <img
                    src={media.url}
                    alt={media.original_name}
                    style={{ width: '100%', height: '150px', objectFit: 'cover', marginBottom: '0.5rem' }}
                  />
                )}
                <div style={{ fontSize: '0.9rem' }}>
                  <div><strong>{media.original_name}</strong></div>
                  <div>Type: {media.type}</div>
                  <div>Size: {media.formatted_size}</div>
                  <div style={{ fontSize: '0.8rem', color: '#666', wordBreak: 'break-all' }}>
                    URL: {media.url}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};
