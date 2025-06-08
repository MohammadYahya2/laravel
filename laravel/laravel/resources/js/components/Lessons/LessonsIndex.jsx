import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

const LessonsIndex = () => {
    const [lessons, setLessons] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);

    useEffect(() => {
        fetchLessons(currentPage);
    }, [currentPage]);

    const fetchLessons = async (page = 1) => {
        try {
            setLoading(true);
            const response = await axios.get(`/api/lessons?page=${page}`);
            setLessons(response.data.data);
            setCurrentPage(response.data.current_page);
            setTotalPages(response.data.last_page);
        } catch (err) {
            setError('Error loading lessons');
            console.error('Error fetching lessons:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async (id) => {
        if (window.confirm('Are you sure you want to delete this lesson?')) {
            try {
                await axios.delete(`/api/lessons/${id}`);
                fetchLessons(currentPage);
            } catch (err) {
                alert('Error deleting lesson');
                console.error('Error deleting lesson:', err);
            }
        }
    };

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US');
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="relative">
                    <div className="animate-spin rounded-full h-16 w-16 border-4 border-blue-200"></div>
                    <div className="animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent absolute top-0 left-0"></div>
                    <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span className="text-blue-600 font-medium text-sm">Loading...</span>
                    </div>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error}
            </div>
        );
    }

    return (
        <div className="space-y-6">
            {/* Simple Header */}
            <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                <div className="bg-white px-6 py-8 border-b border-gray-200">
                    <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                        <div className="text-gray-900">
                            <h1 className="text-3xl font-bold flex items-center text-gray-900">
                                <span className="bg-blue-100 rounded-lg p-2 mr-3">📚</span>
                                Lessons Management
                            </h1>
                            <p className="mt-2 text-gray-600 text-lg">
                                Manage and organize your lessons
                            </p>
                        </div>
                        <Link
                            to="/app/lessons/create"
                            className="inline-flex items-center bg-blue-600 text-white hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg"
                        >
                            <span className="mr-2 text-xl">➕</span>
                            Add New Lesson
                        </Link>
                    </div>
                </div>
            </div>

            {lessons.length === 0 ? (
                <div className="bg-white rounded-xl shadow-lg p-12 text-center">
                    <div className="max-w-md mx-auto">
                        <div className="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                            <span className="text-6xl text-gray-400">📖</span>
                        </div>
                        <h3 className="text-2xl font-bold text-gray-800 mb-3">
                            No Lessons Available
                        </h3>
                        <p className="text-gray-600 mb-8 text-lg">
                            Start by adding your first lesson
                        </p>
                        <Link
                            to="/app/lessons/create"
                            className="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg"
                        >
                            <span className="mr-3 text-xl">➕</span>
                            Add New Lesson
                        </Link>
                    </div>
                </div>
            ) : (
                <>
                    {/* Lessons Table */}
                    <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Lesson
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Course
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Duration
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created At
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {lessons.map((lesson) => (
                                        <tr key={lesson.id} className="hover:bg-gray-50 transition-colors duration-200">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center">
                                                    <div className="bg-blue-100 rounded-lg p-2 mr-3">
                                                        <span className="text-blue-600">📖</span>
                                                    </div>
                                                    <div>
                                                        <div className="text-sm font-medium text-gray-900">{lesson.title}</div>
                                                        {lesson.description && (
                                                            <div className="text-sm text-gray-500 truncate max-w-xs">
                                                                {lesson.description}
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className="text-sm text-gray-900">
                                                    {lesson.course?.title || 'Not Specified'}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {lesson.duration} minutes
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {formatDate(lesson.created_at)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div className="flex items-center space-x-3">
                                                    <Link
                                                        to={`/app/lessons/${lesson.id}`}
                                                        className="text-blue-600 hover:text-blue-900 font-medium transition-colors duration-200"
                                                    >
                                                        View
                                                    </Link>
                                                    <Link
                                                        to={`/app/lessons/${lesson.id}/edit`}
                                                        className="text-indigo-600 hover:text-indigo-900 font-medium transition-colors duration-200"
                                                    >
                                                        Edit
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(lesson.id)}
                                                        className="text-red-600 hover:text-red-900 font-medium transition-colors duration-200"
                                                    >
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Pagination */}
                    {totalPages > 1 && (
                        <div className="bg-white rounded-xl shadow-lg p-6">
                            <div className="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                                <div className="text-sm text-gray-700">
                                    Showing <span className="font-medium">{(currentPage - 1) * 15 + 1}</span> to{' '}
                                    <span className="font-medium">
                                        {Math.min(currentPage * 15, lessons.length)}
                                    </span>{' '}
                                    of <span className="font-medium">{lessons.length}</span> results
                                </div>

                                <div className="flex items-center space-x-2">
                                    <button
                                        onClick={() => setCurrentPage(Math.max(1, currentPage - 1))}
                                        disabled={currentPage === 1}
                                        className={`flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 ${
                                            currentPage === 1
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400'
                                        }`}
                                    >
                                        <span className="mr-1">←</span>
                                        Previous
                                    </button>

                                    <div className="flex items-center space-x-1">
                                        {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                                            let pageNum;
                                            if (totalPages <= 5) {
                                                pageNum = i + 1;
                                            } else if (currentPage <= 3) {
                                                pageNum = i + 1;
                                            } else if (currentPage >= totalPages - 2) {
                                                pageNum = totalPages - 4 + i;
                                            } else {
                                                pageNum = currentPage - 2 + i;
                                            }
                                            
                                            return (
                                                <button
                                                    key={pageNum}
                                                    onClick={() => setCurrentPage(pageNum)}
                                                    className={`px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 ${
                                                        currentPage === pageNum
                                                            ? 'bg-blue-600 text-white shadow-lg'
                                                            : 'bg-white text-gray-700 border border-gray-300 hover:bg-blue-50 hover:border-blue-300'
                                                    }`}
                                                >
                                                    {pageNum}
                                                </button>
                                            );
                                        })}
                                    </div>

                                    <button
                                        onClick={() => setCurrentPage(Math.min(totalPages, currentPage + 1))}
                                        disabled={currentPage === totalPages}
                                        className={`flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 ${
                                            currentPage === totalPages
                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400'
                                        }`}
                                    >
                                        Next
                                        <span className="ml-1">→</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </>
            )}
        </div>
    );
};

export default LessonsIndex; 