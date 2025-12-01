import React from 'react';

interface ApiLoadingProps {
  message?: string;
  fullScreen?: boolean;
  size?: 'sm' | 'md' | 'lg';
  color?: string;
}

const ApiLoading: React.FC<ApiLoadingProps> = ({ 
  message = 'Loading...', 
  fullScreen = true,
  size = 'lg',
  color = 'border-red-500'
}) => {
  const sizeClasses = {
    sm: 'h-8 w-8',
    md: 'h-16 w-16', 
    lg: 'h-32 w-32'
  };

  const containerClasses = fullScreen 
    ? 'min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black'
    : 'flex items-center justify-center p-8';

  return (
    <div className={containerClasses}>
      <div className="text-center">
        <div className={`animate-spin rounded-full border-t-2 border-b-2 ${color} ${sizeClasses[size]} mx-auto mb-4`}></div>
        <p className="text-white text-xl">{message}</p>
      </div>
    </div>
  );
};

export default ApiLoading;