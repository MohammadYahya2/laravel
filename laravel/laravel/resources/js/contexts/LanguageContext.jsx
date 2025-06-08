import React, { createContext, useContext, useState } from 'react';

const LanguageContext = createContext();

export const useLanguage = () => {
    const context = useContext(LanguageContext);
    if (!context) {
        throw new Error('useLanguage must be used within a LanguageProvider');
    }
    return context;
};

const translations = {
    en: {
        // Navigation
        learningManagementSystem: "Learning Management System",
        home: "Home",
        lessons: "Lessons",
        welcome: "Welcome",

        // Lessons Page
        lessonsManagement: "Lessons Management",
        manageAndOrganize: "Manage and organize all educational lessons",
        addNewLesson: "Add New Lesson",
        noLessonsAvailable: "No lessons available",
        startByAdding: "Start by adding your first educational lesson",
        viewDetails: "View Details",
        edit: "Edit",
        delete: "Delete",
        view: "View",
        
        // Lesson Details
        lesson: "Lesson",
        course: "Course",
        duration: "Duration",
        minutes: "minutes",
        notSpecified: "Not specified",
        description: "Description",
        content: "Content",
        createdAt: "Created at",
        
        // Actions
        previous: "Previous",
        next: "Next",
        page: "Page",
        of: "of",
        
        // Create/Edit Lesson
        lessonTitle: "Lesson Title",
        lessonDescription: "Lesson Description",
        lessonContent: "Lesson Content",
        lessonDuration: "Lesson Duration (minutes)",
        selectCourse: "Select Course",
        cancel: "Cancel",
        save: "Save",
        saving: "Saving...",
        updating: "Updating...",
        saveChanges: "Save Changes",
        
        // Messages
        errorOccurred: "An error occurred",
        errorLoadingLessons: "Error loading lessons",
        confirmDelete: "Are you sure you want to delete this lesson?",
        errorDeleting: "Error deleting lesson",
        
        // Footer
        copyright: "© 2025 Learning Management System. All rights reserved.",
        
        // Placeholders
        enterLessonTitle: "Enter lesson title",
        briefDescription: "Brief description of the lesson",
        detailedContent: "Detailed lesson content",
    },
    ar: {
        // Navigation
        learningManagementSystem: "نظام إدارة التعلم",
        home: "الرئيسية",
        lessons: "الدروس",
        welcome: "مرحباً بك",

        // Lessons Page
        lessonsManagement: "إدارة الدروس",
        manageAndOrganize: "إدارة وتنظيم جميع الدروس التعليمية",
        addNewLesson: "إضافة درس جديد",
        noLessonsAvailable: "لا توجد دروس متوفرة",
        startByAdding: "ابدأ بإضافة أول درس تعليمي لك",
        viewDetails: "عرض التفاصيل",
        edit: "تعديل",
        delete: "حذف",
        view: "عرض",
        
        // Lesson Details
        lesson: "درس",
        course: "الكورس",
        duration: "المدة",
        minutes: "دقيقة",
        notSpecified: "غير محدد",
        description: "الوصف",
        content: "المحتوى",
        createdAt: "تاريخ الإنشاء",
        
        // Actions
        previous: "السابق",
        next: "التالي",
        page: "صفحة",
        of: "من",
        
        // Create/Edit Lesson
        lessonTitle: "عنوان الدرس",
        lessonDescription: "وصف الدرس",
        lessonContent: "محتوى الدرس",
        lessonDuration: "مدة الدرس (بالدقائق)",
        selectCourse: "اختر الكورس",
        cancel: "إلغاء",
        save: "حفظ",
        saving: "جاري الحفظ...",
        updating: "جاري التحديث...",
        saveChanges: "حفظ التغييرات",
        
        // Messages
        errorOccurred: "حدث خطأ",
        errorLoadingLessons: "خطأ في تحميل الدروس",
        confirmDelete: "هل أنت متأكد من حذف هذا الدرس؟",
        errorDeleting: "خطأ في حذف الدرس",
        
        // Footer
        copyright: "© 2025 نظام إدارة التعلم. جميع الحقوق محفوظة.",
        
        // Placeholders
        enterLessonTitle: "أدخل عنوان الدرس",
        briefDescription: "وصف مختصر للدرس",
        detailedContent: "محتوى الدرس التفصيلي",
    }
};

export const LanguageProvider = ({ children }) => {
    const [language, setLanguage] = useState('ar');
    const [direction, setDirection] = useState('rtl');

    const switchLanguage = (lang) => {
        setLanguage(lang);
        setDirection(lang === 'ar' ? 'rtl' : 'ltr');
        document.dir = lang === 'ar' ? 'rtl' : 'ltr';
        document.documentElement.lang = lang;
    };

    const t = (key) => {
        return translations[language][key] || key;
    };

    const value = {
        language,
        direction,
        switchLanguage,
        t,
        isRTL: language === 'ar'
    };

    return (
        <LanguageContext.Provider value={value}>
            {children}
        </LanguageContext.Provider>
    );
}; 