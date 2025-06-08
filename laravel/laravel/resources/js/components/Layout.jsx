import React from 'react';
import { Link, useLocation } from 'react-router-dom';

const Layout = ({ children }) => {
    const location = useLocation();

    const isActive = (path) => {
        return location.pathname === path;
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
            {/* Enhanced Navigation */}
            <nav className="bg-white shadow-lg border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <div className="flex-shrink-0 flex items-center">
                                <div className="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg mr-3">
                                    <span className="text-white text-xl">📚</span>
                                </div>
                                <Link 
                                    to="/app" 
                                    className="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                                >
                                    Learning Management System
                                </Link>
                            </div>
                            <div className="hidden sm:flex sm:space-x-8 sm:ml-6">
                                <Link
                                    to="/app"
                                    className={`${
                                        isActive('/app') 
                                            ? 'border-indigo-500 text-indigo-600 bg-indigo-50' 
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 hover:bg-gray-50'
                                    } inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg transition-all duration-200`}
                                >
                                    <span className="mr-2">🏠</span>
                                    Home
                                </Link>
                                <Link
                                    to="/app/lessons"
                                    className={`${
                                        isActive('/app/lessons') || location.pathname.startsWith('/app/lessons')
                                            ? 'border-indigo-500 text-indigo-600 bg-indigo-50' 
                                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 hover:bg-gray-50'
                                    } inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg transition-all duration-200`}
                                >
                                    <span className="mr-2">📖</span>
                                    Lessons
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Main Content */}
            <main className="py-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="px-4 py-6 sm:px-0">
                        {children}
                    </div>
                </div>
            </main>

            {/* Footer */}
            <footer className="bg-white border-t border-gray-200 mt-12">
                <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <p className="text-gray-500 text-sm">
                            © 2024 Learning Management System. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
};

export default Layout; 