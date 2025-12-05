// Firebase Firestore utilities
import {
  collection,
  doc,
  getDocs,
  getDoc,
  addDoc,
  updateDoc,
  deleteDoc,
  query,
  where,
  orderBy,
  limit,
  onSnapshot,
  serverTimestamp,
  increment
} from "firebase/firestore";
import { db } from "./firebase.js";

// Generic functions for Firestore operations

// Get all documents from a collection
export const getCollection = async (collectionName) => {
  try {
    const querySnapshot = await getDocs(collection(db, collectionName));
    const documents = [];
    querySnapshot.forEach((doc) => {
      documents.push({ id: doc.id, ...doc.data() });
    });
    return { success: true, data: documents };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Get a single document by ID
export const getDocument = async (collectionName, docId) => {
  try {
    const docRef = doc(db, collectionName, docId);
    const docSnap = await getDoc(docRef);

    if (docSnap.exists()) {
      return { success: true, data: { id: docSnap.id, ...docSnap.data() } };
    } else {
      return { success: false, error: "Document not found" };
    }
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Add a new document
export const addDocument = async (collectionName, data) => {
  try {
    const docRef = await addDoc(collection(db, collectionName), {
      ...data,
      createdAt: serverTimestamp()
    });
    return { success: true, id: docRef.id };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Update a document
export const updateDocument = async (collectionName, docId, data) => {
  try {
    const docRef = doc(db, collectionName, docId);
    await updateDoc(docRef, {
      ...data,
      updatedAt: serverTimestamp()
    });
    return { success: true };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Delete a document
export const deleteDocument = async (collectionName, docId) => {
  try {
    const docRef = doc(db, collectionName, docId);
    await deleteDoc(docRef);
    return { success: true };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Query documents with conditions
export const queryDocuments = async (collectionName, conditions = [], orderByField = null, limitCount = null) => {
  try {
    let q = collection(db, collectionName);

    // Apply where conditions
    conditions.forEach(condition => {
      q = query(q, where(condition.field, condition.operator, condition.value));
    });

    // Apply ordering
    if (orderByField) {
      q = query(q, orderBy(orderByField.field, orderByField.direction || 'asc'));
    }

    // Apply limit
    if (limitCount) {
      q = query(q, limit(limitCount));
    }

    const querySnapshot = await getDocs(q);
    const documents = [];
    querySnapshot.forEach((doc) => {
      documents.push({ id: doc.id, ...doc.data() });
    });

    return { success: true, data: documents };
  } catch (error) {
    return { success: false, error: error.message };
  }
};

// Real-time listener for a collection
export const listenToCollection = (collectionName, callback, conditions = []) => {
  let q = collection(db, collectionName);

  // Apply where conditions
  conditions.forEach(condition => {
    q = query(q, where(condition.field, condition.operator, condition.value));
  });

  return onSnapshot(q, (querySnapshot) => {
    const documents = [];
    querySnapshot.forEach((doc) => {
      documents.push({ id: doc.id, ...doc.data() });
    });
    callback(documents);
  });
};

// VNY Store specific functions
export const addOrder = async (orderData) => {
  return await addDocument('orders', {
    ...orderData,
    status: 'pending',
    orderNumber: `VNY-${Date.now()}`
  });
};

export const getUserOrders = async (userId) => {
  return await queryDocuments('orders', [
    { field: 'userId', operator: '==', value: userId }
  ], { field: 'createdAt', direction: 'desc' });
};

export const addToWishlist = async (userId, productId) => {
  return await addDocument('wishlists', {
    userId,
    productId,
    addedAt: serverTimestamp()
  });
};

export const getUserWishlist = async (userId) => {
  return await queryDocuments('wishlists', [
    { field: 'userId', operator: '==', value: userId }
  ]);
};

export const addReview = async (reviewData) => {
  return await addDocument('reviews', {
    ...reviewData,
    createdAt: serverTimestamp(),
    helpful: 0
  });
};

export const getProductReviews = async (productId) => {
  return await queryDocuments('reviews', [
    { field: 'productId', operator: '==', value: productId }
  ], { field: 'createdAt', direction: 'desc' });
};
