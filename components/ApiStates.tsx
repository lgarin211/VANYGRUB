// Loading component untuk API data loading states
import React from 'react';

interface ApiLoadingProps {
  message?: string;
  fullScreen?: boolean;
}

export const ApiLoading: React.FC<ApiLoadingProps> = ({ 
  message = "Loading...", 
  fullScreen = false 
}) => {
  const containerClasses = fullScreen 
    ? "fixed inset-0 flex items-center justify-center bg-white z-50"
    : "flex items-center justify-center py-16";

  return (
    <div className={containerClasses}>
      <div className="text-center">
        <div className="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600 mb-4"></div>
        <p className="text-gray-600">{message}</p>
      </div>
    </div>
  );
};

// Error component untuk API error states
interface ApiErrorProps {
  message?: string;
  onRetry?: () => void;
  fullScreen?: boolean;
}

export const ApiError: React.FC<ApiErrorProps> = ({ 
  message = "Failed to load data", 
  onRetry,
  fullScreen = false 
}) => {
  const containerClasses = fullScreen 
    ? "fixed inset-0 flex items-center justify-center bg-white z-50"
    : "flex items-center justify-center py-16";

  return (
    <div className={containerClasses}>
      <div className="text-center">
        <div className="text-red-500 text-6xl mb-4">⚠</div>
        <p className="text-gray-600 mb-4">{message}</p>
        {onRetry && (
          <button 
            onClick={onRetry}
            className="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition-colors"
          >
            Try Again
          </button>
        )}
      </div>
    </div>
  );
};

// Empty state component
interface ApiEmptyProps {
  message?: string;
  icon?: string;
}

export const ApiEmpty: React.FC<ApiEmptyProps> = ({ 
  message = "No data available",
  icon = "📦"
}) => {
  return (
    <div className="flex items-center justify-center py-16">
      <div className="text-center">
        <div className="text-6xl mb-4">{icon}</div>
        <p className="text-gray-600">{message}</p>
      </div>
    </div>
  );
};