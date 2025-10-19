@php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
    $acceptedFileTypes = $getAcceptedFileTypes();
    $maxFileSize = $getMaxFileSize();
    $isMultiple = $isMultiple();
    $uploadUrl = $getUploadUrl();
@endphp

<div
    x-data="s3DirectUpload({
        id: '{{ $id }}',
        disabled: {{ $isDisabled ? 'true' : 'false' }},
        statePath: '{{ $statePath }}',
        acceptedFileTypes: {{ json_encode($acceptedFileTypes) }},
        maxFileSize: {{ $maxFileSize }},
        multiple: {{ $isMultiple ? 'true' : 'false' }},
        uploadUrl: '{{ $uploadUrl }}',
        state: @js($getState()),
    })"
    class="space-y-2"
>
    <!-- منطقة السحب والإفلات -->
    <div
        x-show="!uploading"
        :class="{
            'border-primary-500 bg-primary-50': isDragOver,
            'border-gray-300': !isDragOver,
        }"
        class="relative border-2 border-dashed rounded-lg p-6 text-center transition-colors duration-200"
        @dragover.prevent="isDragOver = true"
        @dragleave.prevent="isDragOver = false"
        @drop.prevent="handleDrop($event)"
    >
        <input
            x-ref="fileInput"
            type="file"
            :multiple="multiple"
            :accept="acceptedFileTypes.join(',')"
            @change="handleFileSelect($event)"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            {{ $isDisabled ? 'disabled' : '' }}
        />

        <div class="space-y-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>

            <div class="text-sm text-gray-600">
                <p class="font-medium">
                    اسحب وأفلت الملفات هنا، أو
                    <span class="text-primary-600 hover:text-primary-500 cursor-pointer" @click="$refs.fileInput.click()">تصفح</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    أنواع الملفات المسموحة: {{ implode(', ', array_map(fn($type) => str_replace('image/', '', $type), $acceptedFileTypes)) }}
                </p>
                <p class="text-xs text-gray-500">
                    الحد الأقصى لحجم الملف: {{ number_format($maxFileSize / 1024 / 1024, 1) }}MB
                </p>
            </div>
        </div>
    </div>

    <!-- شريط التقدم -->
    <div x-show="uploading" class="space-y-2">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-700">جاري رفع الملف...</span>
            <span class="text-gray-500" x-text="uploadProgress + '%'"></span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2">
            <div
                class="bg-primary-600 h-2 rounded-full transition-all duration-300"
                :style="`width: ${uploadProgress}%`"
            ></div>
        </div>

        <button
            type="button"
            @click="cancelUpload()"
            class="text-xs text-red-600 hover:text-red-800"
        >
            إلغاء الرفع
        </button>
    </div>

    <!-- عرض الملف المرفوع -->
    <div x-show="uploadedFile" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
        <div class="flex-shrink-0">
            <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate" x-text="uploadedFile.name"></p>
            <p class="text-xs text-gray-500" x-text="formatFileSize(uploadedFile.size)"></p>
        </div>

        <button
            type="button"
            @click="removeFile()"
            class="text-red-600 hover:text-red-800 p-1"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- رسائل الخطأ -->
    <div x-show="errorMessage" class="text-sm text-red-600 bg-red-50 p-3 rounded-lg" x-text="errorMessage"></div>

    <!-- حقل مخفي لتخزين القيمة -->
    <input
        type="hidden"
        :name="statePath"
        x-model="state"
    />
</div>

<script>
function s3DirectUpload(config) {
    return {
        id: config.id,
        disabled: config.disabled,
        statePath: config.statePath,
        acceptedFileTypes: config.acceptedFileTypes,
        maxFileSize: config.maxFileSize,
        multiple: config.multiple,
        uploadUrl: config.uploadUrl,
        state: config.state,

        isDragOver: false,
        uploading: false,
        uploadProgress: 0,
        uploadedFile: null,
        errorMessage: '',
        abortController: null,

        handleDrop(event) {
            this.isDragOver = false;
            const files = Array.from(event.dataTransfer.files);
            this.processFiles(files);
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            this.processFiles(files);
        },

        processFiles(files) {
            if (this.disabled) return;

            if (!this.multiple && files.length > 1) {
                this.showError('يمكن رفع ملف واحد فقط');
                return;
            }

            files.forEach(file => {
                if (!this.validateFile(file)) return;
                this.uploadFile(file);
            });
        },

        validateFile(file) {
            if (file.size > this.maxFileSize) {
                this.showError(`حجم الملف "${file.name}" كبير جداً`);
                return false;
            }

            if (!this.acceptedFileTypes.includes(file.type)) {
                this.showError(`نوع الملف "${file.name}" غير مدعوم`);
                return false;
            }

            return true;
        },

        async uploadFile(file) {
            this.uploading = true;
            this.uploadProgress = 0;
            this.errorMessage = '';
            this.abortController = new AbortController();

            try {
                // الحصول على presigned URL
                const presignedResponse = await this.getPresignedUrl(file);

                // رفع الملف إلى S3
                await this.uploadToS3(presignedResponse.upload_url, file);

                // حفظ معلومات الملف
                this.uploadedFile = {
                    name: file.name,
                    size: file.size,
                    finalName: presignedResponse.final_filename_to_save
                };

                // تحديث الحالة
                this.state = presignedResponse.final_filename_to_save;

                // إشعار Filament
                this.$dispatch('file-uploaded', {
                    statePath: this.statePath,
                    filename: presignedResponse.final_filename_to_save
                });

            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.showError(error.message || 'حدث خطأ أثناء رفع الملف');
                }
            } finally {
                this.uploading = false;
                this.abortController = null;
            }
        },

        async getPresignedUrl(file) {
            const response = await fetch(this.uploadUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    filename: file.name,
                    filetype: file.type
                }),
                signal: this.abortController.signal
            });

            if (!response.ok) {
                throw new Error('فشل في الحصول على رابط الرفع');
            }

            const data = await response.json();
            return data;
        },

        async uploadToS3(presignedUrl, file) {
            const response = await fetch(presignedUrl, {
                method: 'PUT',
                body: file,
                headers: {
                    'Content-Type': file.type,
                },
                signal: this.abortController.signal
            });

            if (!response.ok) {
                throw new Error('فشل في رفع الملف إلى S3');
            }
        },

        cancelUpload() {
            if (this.abortController) {
                this.abortController.abort();
            }
            this.uploading = false;
            this.uploadProgress = 0;
        },

        removeFile() {
            this.uploadedFile = null;
            this.state = null;
            this.errorMessage = '';

            // إشعار Filament
            this.$dispatch('file-removed', {
                statePath: this.statePath
            });
        },

        showError(message) {
            this.errorMessage = message;
            setTimeout(() => {
                this.errorMessage = '';
            }, 5000);
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        getAuthToken() {
            // الحصول على التوكن من meta tag أو localStorage
            return document.querySelector('meta[name="api-token"]')?.getAttribute('content') ||
                   localStorage.getItem('auth_token') || '';
        }
    }
}
</script>