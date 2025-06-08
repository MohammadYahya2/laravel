import React from 'react';
import { Link } from 'react-router-dom';

const Home = () => {
    return (
        <div className="space-y-8">
            {/* Quick Actions */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Link
                    to="/app/lessons"
                    className="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200"
                >
                    <div className="text-center">
                        <div className="bg-blue-100 group-hover:bg-blue-200 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 transition-colors duration-200">
                            <span className="text-3xl">📚</span>
                        </div>
                        <h3 className="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Lessons Management
                        </h3>
                        <p className="text-gray-600 group-hover:text-gray-700 transition-colors duration-200">
                            View and manage all lessons
                        </p>
                    </div>
                </Link>

                <Link
                    to="/app/lessons/create"
                    className="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-green-200"
                >
                    <div className="text-center">
                        <div className="bg-green-100 group-hover:bg-green-200 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 transition-colors duration-200">
                            <span className="text-3xl">➕</span>
                        </div>
                        <h3 className="text-xl font-bold text-gray-800 mb-2 group-hover:text-green-600 transition-colors duration-200">
                            Add New Lesson
                        </h3>
                        <p className="text-gray-600 group-hover:text-gray-700 transition-colors duration-200">
                            Create a new lesson
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    );
};

export default Home; 