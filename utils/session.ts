// Session management utility for cart
export const getSessionId = (): string => {
  if (typeof window === 'undefined') return '';
  
  let sessionId = localStorage.getItem('cart_session_id');
  if (!sessionId) {
    sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    localStorage.setItem('cart_session_id', sessionId);
  }
  return sessionId;
};

export const clearSession = (): void => {
  if (typeof window !== 'undefined') {
    localStorage.removeItem('cart_session_id');
  }
};