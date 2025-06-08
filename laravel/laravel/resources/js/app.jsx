import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { LanguageProvider } from './contexts/LanguageContext';
import Layout from './components/Layout';
import Home from './components/Home';
import LessonsIndex from './components/Lessons/LessonsIndex';
import LessonShow from './components/Lessons/LessonShow';
import LessonCreate from './components/Lessons/LessonCreate';
import LessonEdit from './components/Lessons/LessonEdit';

function App() {
    return (
        <LanguageProvider>
            <Router>
                <Layout>
                    <Routes>
                        <Route path="/app" element={<Home />} />
                        <Route path="/app/lessons" element={<LessonsIndex />} />
                        <Route path="/app/lessons/create" element={<LessonCreate />} />
                        <Route path="/app/lessons/:id" element={<LessonShow />} />
                        <Route path="/app/lessons/:id/edit" element={<LessonEdit />} />
                        {/* Catch all route */}
                        <Route path="*" element={
                            <div className="text-center py-12">
                                <h1 className="text-2xl font-bold text-gray-800 mb-4">الصفحة غير موجودة</h1>
                                <p className="text-gray-600 mb-6">الصفحة التي تبحث عنها غير متوفرة</p>
                                <a href="/app" className="bg-blue-600 text-white px-6 py-3 rounded-lg">
                                    العودة للرئيسية
                                </a>
                            </div>
                        } />
                    </Routes>
                </Layout>
            </Router>
        </LanguageProvider>
    );
}

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(<App />); 