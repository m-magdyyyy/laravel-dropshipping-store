import axios from 'axios';

class S3DirectUpload {
    constructor(options = {}) {
        this.apiUrl = options.apiUrl || '/api/s3/presigned-url';
        this.onProgress = options.onProgress || (() => {});
        this.onSuccess = options.onSuccess || (() => {});
        this.onError = options.onError || (() => {});
        this.maxFileSize = options.maxFileSize || 50 * 1024 * 1024; // 50MB
        this.allowedTypes = options.allowedTypes || ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    }

    async uploadFile(file) {
        try {
            // التحقق من حجم الملف
            if (file.size > this.maxFileSize) {
                throw new Error(`حجم الملف كبير جداً. الحد الأقصى: ${this.maxFileSize / (1024 * 1024)}MB`);
            }

            // التحقق من نوع الملف
            if (!this.allowedTypes.includes(file.type)) {
                throw new Error('نوع الملف غير مدعوم');
            }

            // الحصول على presigned URL
            const presignedResponse = await this.getPresignedUrl(file.name, file.type);

            // رفع الملف مباشرة إلى S3
            await this.uploadToS3(presignedResponse.upload_url, file);

            // إرجاع اسم الملف النهائي
            this.onSuccess(presignedResponse.final_filename_to_save);

            return presignedResponse.final_filename_to_save;

        } catch (error) {
            this.onError(error.message || 'حدث خطأ أثناء رفع الملف');
            throw error;
        }
    }

    async getPresignedUrl(filename, filetype) {
        const response = await axios.post(this.apiUrl, {
            filename: filename,
            filetype: filetype
        }, {
            headers: {
                'Authorization': `Bearer ${this.getAuthToken()}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (response.data && response.data.upload_url && response.data.final_filename_to_save) {
            return response.data;
        } else {
            throw new Error('استجابة غير صحيحة من الخادم');
        }
    }

    async uploadToS3(presignedUrl, file) {
        const response = await axios.put(presignedUrl, file, {
            headers: {
                'Content-Type': file.type,
            },
            onUploadProgress: (progressEvent) => {
                const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                this.onProgress(percentCompleted);
            }
        });

        if (response.status !== 200) {
            throw new Error('فشل في رفع الملف إلى S3');
        }
    }

    getAuthToken() {
        // يمكن تخصيص هذا حسب نظام المصادقة المستخدم
        // في حالة Sanctum، يمكن الحصول على التوكن من localStorage أو cookie
        return localStorage.getItem('auth_token') || document.querySelector('meta[name="api-token"]')?.getAttribute('content') || '';
    }

    // طريقة للتحقق من صحة التوكن
    async validateToken() {
        try {
            await axios.get('/api/user', {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`
                }
            });
            return true;
        } catch (error) {
            return false;
        }
    }
}

// تصدير الفئة للاستخدام
export default S3DirectUpload;