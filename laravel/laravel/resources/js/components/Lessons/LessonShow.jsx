import React, { useState, useEffect } from 'react';
import { Link, useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';

const LessonShow = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [lesson, setLesson] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetchLesson();
    }, [id]);

    const fetchLesson = async () => {
        try {
            setLoading(true);
            const response = await axios.get(`/api/lessons/${id}`);
            setLesson(response.data);
        } catch (err) {
            setError('خطأ في تحميل الدرس');
            console.error('Error fetching lesson:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleDelete = async () => {
        if (window.confirm('هل أنت متأكد من حذف هذا الدرس؟')) {
            try {
                await axios.delete(`/api/lessons/${id}`);
                navigate('/lessons');
            } catch (err) {
                alert('خطأ في حذف الدرس');
                console.error('Error deleting lesson:', err);
            }
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>
        );
    }

    if (error || !lesson) {
        return (
            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {error || 'الدرس غير موجود'}
            </div>
        );
    }

    return (
        <div className="bg-white shadow overflow-hidden sm:rounded-lg">
            <div className="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 className="text-lg leading-6 font-medium text-gray-900">
                        تفاصيل الدرس
                    </h3>
                    <p className="mt-1 max-w-2xl text-sm text-gray-500">
                        معلومات مفصلة عن الدرس
                    </p>
                </div>
                <Link
                    to="/lessons"
                    className="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                >
                    العودة للقائمة
                </Link>
            </div>

            <div className="border-t border-gray-200">
                <dl>
                    <div className="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt className="text-sm font-medium text-gray-500">
                            عنوان الدرس
                        </dt>
                        <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {lesson.title}
                        </dd>
                    </div>
                    
                    <div className="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt className="text-sm font-medium text-gray-500">
                            الوصف
                        </dt>
                        <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {lesson.description || 'لا يوجد وصف'}
                        </dd>
                    </div>
                    
                    <div className="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt className="text-sm font-medium text-gray-500">
                            الكورس
                        </dt>
                        <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {lesson.course?.title || 'غير محدد'}
                        </dd>
                    </div>
                    
                    <div className="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt className="text-sm font-medium text-gray-500">
                            المدة
                        </dt>
                        <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {lesson.duration} دقيقة
                        </dd>
                    </div>
                    
                    {lesson.content && (
                        <div className="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt className="text-sm font-medium text-gray-500">
                                المحتوى
                            </dt>
                            <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <div className="whitespace-pre-wrap">
                                    {lesson.content}
                                </div>
                            </dd>
                        </div>
                    )}
                    
                    <div className="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt className="text-sm font-medium text-gray-500">
                            تاريخ الإنشاء
                        </dt>
                        <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {new Date(lesson.created_at).toLocaleDateString('ar-EG')}
                        </dd>
                    </div>
                </dl>
            </div>

            {/* Action Buttons */}
            <div className="px-4 py-3 border-t border-gray-200 flex space-x-3 space-x-reverse">
                <Link
                    to={`/lessons/${lesson.id}/edit`}
                    className="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                >
                    تعديل الدرس
                </Link>
                <button
                    onClick={handleDelete}
                    className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                >
                    حذف الدرس
                </button>
            </div>
        </div>
    );
};

export default LessonShow; 