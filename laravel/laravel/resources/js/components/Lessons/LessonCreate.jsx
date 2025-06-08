import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';

const LessonCreate = () => {
    const navigate = useNavigate();
    
    const [formData, setFormData] = useState({
        title: '',
        description: '',
        content: '',
        duration: '',
        course_id: ''
    });
    
    const [courses, setCourses] = useState([]);
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});

    useEffect(() => {
        fetchCourses();
    }, []);

    const fetchCourses = async () => {
        try {
            const response = await axios.get('/api/courses');
            setCourses(response.data);
        } catch (error) {
            console.error('Error fetching courses:', error);
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
        
        // Clear error when user starts typing
        if (errors[name]) {
            setErrors(prev => ({
                ...prev,
                [name]: ''
            }));
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setErrors({});

        try {
            const response = await axios.post('/api/lessons', formData);
            if (response.status === 201) {
                navigate('/app/lessons');
            }
        } catch (error) {
            if (error.response?.status === 422) {
                setErrors(error.response.data.errors || {});
            } else {
                alert('An error occurred');
            }
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="max-w-4xl mx-auto">
            {/* Floating Save Button */}
            <div className="fixed top-4 right-4 z-50">
                <button
                    type="submit"
                    form="lesson-form"
                    disabled={loading}
                    className={`inline-flex items-center justify-center px-6 py-3 rounded-full font-bold text-sm transition-all duration-200 transform hover:scale-110 shadow-2xl ${
                        loading
                            ? 'bg-gray-400 cursor-not-allowed text-white'
                            : 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white border-2 border-green-600 hover:border-green-700'
                    }`}
                    title="Save Lesson"
                >
                    {loading ? (
                        <div className="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
                    ) : (
                        <>
                            <span className="text-lg">💾</span>
                            <span className="ml-2 hidden sm:inline">Save</span>
                        </>
                    )}
                </button>
            </div>

            {/* Header */}
            <div className="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div className="bg-white px-6 py-8 border-b border-gray-200">
                    <div className="flex items-center justify-between">
                        <div className="text-gray-900">
                            <h1 className="text-3xl font-bold flex items-center text-gray-900">
                                <span className="bg-green-100 rounded-lg p-2 mr-3">➕</span>
                                Add New Lesson
                            </h1>
                            <p className="mt-2 text-gray-600 text-lg">
                                Start by adding a new lesson
                            </p>
                        </div>
                        <div className="flex space-x-3">
                            <button
                                type="submit"
                                form="lesson-form"
                                disabled={loading}
                                className={`inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg ${
                                    loading
                                        ? 'bg-gray-400 cursor-not-allowed text-white'
                                        : 'bg-green-600 text-white hover:bg-green-700 border-2 border-green-600 hover:border-green-700'
                                }`}
                            >
                                {loading ? (
                                    <>
                                        <div className="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent mr-2"></div>
                                        Saving...
                                    </>
                                ) : (
                                    <>
                                        <span className="text-xl mr-2">💾</span>
                                        Save Lesson
                                    </>
                                )}
                            </button>
                            <Link
                                to="/app/lessons"
                                className="inline-flex items-center bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg border border-gray-600"
                            >
                                <span className="mr-2">←</span>
                                Back to Lessons
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            {/* Form */}
            <div className="bg-white rounded-xl shadow-lg overflow-hidden">
                <form id="lesson-form" onSubmit={handleSubmit} className="p-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {/* Right Column */}
                        <div className="space-y-6">
                            {/* Title */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    <span className="flex items-center">
                                        <span className="mr-2 text-red-500">*</span>
                                        <span className="text-lg">📝</span>
                                        <span className="ml-2">Lesson Title</span>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    value={formData.title}
                                    onChange={handleChange}
                                    placeholder="Enter lesson title"
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 ${
                                        errors.title ? 'border-red-500 bg-red-50' : 'border-gray-300'
                                    }`}
                                    required
                                />
                                {errors.title && (
                                    <p className="mt-1 text-sm text-red-600 flex items-center">
                                        <span className="mr-1">⚠️</span>
                                        {errors.title[0]}
                                    </p>
                                )}
                            </div>

                            {/* Duration & Course */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {/* Duration */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        <span className="flex items-center">
                                            <span className="mr-2 text-red-500">*</span>
                                            <span className="text-lg">⏱️</span>
                                            <span className="ml-2">Lesson Duration</span>
                                        </span>
                                    </label>
                                    <input
                                        type="number"
                                        name="duration"
                                        value={formData.duration}
                                        onChange={handleChange}
                                        placeholder="30"
                                        min="1"
                                        className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 ${
                                            errors.duration ? 'border-red-500 bg-red-50' : 'border-gray-300'
                                        }`}
                                        required
                                    />
                                    {errors.duration && (
                                        <p className="mt-1 text-sm text-red-600 flex items-center">
                                            <span className="mr-1">⚠️</span>
                                            {errors.duration[0]}
                                        </p>
                                    )}
                                </div>

                                {/* Course */}
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-2">
                                        <span className="flex items-center">
                                            <span className="text-lg">🎓</span>
                                            <span className="ml-2">Select Course</span>
                                        </span>
                                    </label>
                                    <select
                                        name="course_id"
                                        value={formData.course_id}
                                        onChange={handleChange}
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                    >
                                        <option value="">Select Course</option>
                                        {courses.map(course => (
                                            <option key={course.id} value={course.id}>
                                                {course.title}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>

                            {/* Description */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    <span className="flex items-center">
                                        <span className="text-lg">📋</span>
                                        <span className="ml-2">Lesson Description</span>
                                    </span>
                                </label>
                                <textarea
                                    name="description"
                                    value={formData.description}
                                    onChange={handleChange}
                                    placeholder="Brief description"
                                    rows="4"
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-vertical ${
                                        errors.description ? 'border-red-500 bg-red-50' : 'border-gray-300'
                                    }`}
                                />
                                {errors.description && (
                                    <p className="mt-1 text-sm text-red-600 flex items-center">
                                        <span className="mr-1">⚠️</span>
                                        {errors.description[0]}
                                    </p>
                                )}
                            </div>
                        </div>

                        {/* Left Column */}
                        <div className="space-y-6">
                            {/* Content */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    <span className="flex items-center">
                                        <span className="text-lg">📖</span>
                                        <span className="ml-2">Lesson Content</span>
                                    </span>
                                </label>
                                <textarea
                                    name="content"
                                    value={formData.content}
                                    onChange={handleChange}
                                    placeholder="Detailed content"
                                    rows="12"
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-vertical ${
                                        errors.content ? 'border-red-500 bg-red-50' : 'border-gray-300'
                                    }`}
                                />
                                {errors.content && (
                                    <p className="mt-1 text-sm text-red-600 flex items-center">
                                        <span className="mr-1">⚠️</span>
                                        {errors.content[0]}
                                    </p>
                                )}
                            </div>

                            {/* Preview Card */}
                            <div className="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                                <h3 className="text-lg font-semibold text-green-800 mb-3 flex items-center">
                                    <span className="mr-2">👁️</span>
                                    Lesson Preview
                                </h3>
                                <div className="space-y-2 text-sm">
                                    <div className="flex items-center justify-between">
                                        <span className="text-green-700">Title:</span>
                                        <span className="font-medium text-green-900">
                                            {formData.title || 'Not specified'}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-green-700">Duration:</span>
                                        <span className="font-medium text-green-900">
                                            {formData.duration ? `${formData.duration} minutes` : 'Not specified'}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-green-700">Course:</span>
                                        <span className="font-medium text-green-900">
                                            {formData.course_id 
                                                ? courses.find(c => c.id == formData.course_id)?.title || 'Not specified'
                                                : 'Not specified'
                                            }
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* ACTION BUTTONS */}
                    <div className="bg-gradient-to-r from-gray-50 to-green-50 p-8 rounded-lg mt-8 border-2 border-green-200">
                        <div className="text-center mb-6">
                            <h3 className="text-lg font-bold text-gray-800 mb-2">
                                🎯 Save your lesson now!
                            </h3>
                            <p className="text-sm text-gray-600">
                                Make sure to fill all required fields then click save
                            </p>
                        </div>
                        
                        <div className="flex flex-col sm:flex-row justify-center gap-4">
                            <Link
                                to="/app/lessons"
                                className="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 hover:border-gray-400 font-bold text-lg transition-all duration-200 transform hover:scale-105 shadow-md order-2 sm:order-1"
                            >
                                <span className="text-2xl mr-3">✕</span>
                                Cancel
                            </Link>
                            
                            <button
                                type="submit"
                                disabled={loading}
                                className={`inline-flex items-center justify-center px-16 py-5 rounded-xl font-black text-xl transition-all duration-200 transform hover:scale-105 shadow-2xl min-w-[250px] order-1 sm:order-2 ${
                                    loading
                                        ? 'bg-gray-400 cursor-not-allowed text-white'
                                        : 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white border-4 border-green-600 hover:border-green-700 animate-pulse'
                                }`}
                            >
                                {loading ? (
                                    <>
                                        <div className="animate-spin rounded-full h-7 w-7 border-3 border-white border-t-transparent mr-3"></div>
                                        <span className="text-xl">Saving...</span>
                                    </>
                                ) : (
                                    <>
                                        <span className="text-3xl mr-3">💾</span>
                                        <span className="font-black text-xl">Save Lesson</span>
                                    </>
                                )}
                            </button>
                        </div>
                        
                        {/* Progress Indicator */}
                        <div className="mt-6 text-center">
                            <div className="inline-flex items-center space-x-2 text-sm text-gray-600">
                                <span className={formData.title ? 'text-green-600' : 'text-gray-400'}>
                                    {formData.title ? '✅' : '⭕'} Title
                                </span>
                                <span className={formData.duration ? 'text-green-600' : 'text-gray-400'}>
                                    {formData.duration ? '✅' : '⭕'} Duration
                                </span>
                                <span className={formData.description ? 'text-green-600' : 'text-gray-400'}>
                                    {formData.description ? '✅' : '⭕'} Description
                                </span>
                                <span className={formData.content ? 'text-green-600' : 'text-gray-400'}>
                                    {formData.content ? '✅' : '⭕'} Content
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default LessonCreate; 