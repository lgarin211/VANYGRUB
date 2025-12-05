// Firebase Storage utilities
import {
  ref,
  uploadBytes,
  uploadBytesResumable,
  getDownloadURL,
  deleteObject,
  listAll
} from "firebase/storage";
import { storage } from "./firebase.js";

// Upload file to Firebase Storage
export const uploadFile = async (file, path, onProgress = null) => {
  try {
    const storageRef = ref(storage, path);

    if (onProgress) {
      // Upload with progress tracking
      const uploadTask = uploadBytesResumable(storageRef, file);

      return new Promise((resolve, reject) => {
        uploadTask.on('state_changed',
          (snapshot) => {
            const progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            onProgress(progress);
          },
          (error) => {
            reject({ success: false, error: error.message });
          },
          async () => {
            try {
              const downloadURL = await getDownloadURL(uploadTask.snapshot.ref);
              resolve({ success: true, url: downloadURL, path: path });
            } catch (error) {
              reject({ success: false, error: error.message });
            }
          }
        );
      });
    } else {
      // Simple upload
      const snapshot = await uploadBytes(storageRef, file);
      const downloadURL = await getDownloadURL(snapshot.ref);
      return { success: true, url: downloadURL, path: path };
    }
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Delete file from Firebase Storage
export const deleteFile = async (path) => {
  try {
    const fileRef = ref(storage, path);
    await deleteObject(fileRef);
    return { success: true };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// List all files in a directory
export const listFiles = async (path) => {
  try {
    const listRef = ref(storage, path);
    const res = await listAll(listRef);

    const files = [];
    for (const itemRef of res.items) {
      const url = await getDownloadURL(itemRef);
      files.push({
        name: itemRef.name,
        fullPath: itemRef.fullPath,
        url: url
      });
    }

    return { success: true, files: files };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// VNY Store specific functions

// Upload product image
export const uploadProductImage = async (file, productId, imageIndex = 0, onProgress = null) => {
  const timestamp = Date.now();
  const fileName = `${productId}_${imageIndex}_${timestamp}.${file.name.split('.').pop()}`;
  const path = `products/${productId}/${fileName}`;

  return await uploadFile(file, path, onProgress);
};

// Upload user avatar
export const uploadUserAvatar = async (file, userId, onProgress = null) => {
  const timestamp = Date.now();
  const fileName = `avatar_${timestamp}.${file.name.split('.').pop()}`;
  const path = `users/${userId}/${fileName}`;

  return await uploadFile(file, path, onProgress);
};

// Upload review image
export const uploadReviewImage = async (file, reviewId, imageIndex = 0, onProgress = null) => {
  const timestamp = Date.now();
  const fileName = `review_${imageIndex}_${timestamp}.${file.name.split('.').pop()}`;
  const path = `reviews/${reviewId}/${fileName}`;

  return await uploadFile(file, path, onProgress);
};

// Helper function to validate file type
export const validateImageFile = (file) => {
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
  const maxSize = 5 * 1024 * 1024; // 5MB

  if (!allowedTypes.includes(file.type)) {
    return { valid: false, error: 'File type not supported. Please use JPG, PNG, or WebP.' };
  }

  if (file.size > maxSize) {
    return { valid: false, error: 'File size too large. Maximum size is 5MB.' };
  }

  return { valid: true };
};
