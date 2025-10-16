@php
    $record = $getRecord();
    $imageUrl = $record && $record->image ? '/storage/' . ltrim($record->image, '/') : null;
@endphp

@if($imageUrl)
    <div class="space-y-2">
        <div class="relative inline-block">
            <img src="{{ $imageUrl }}" 
                 alt="صورة المنتج الحالية" 
                 class="max-w-full h-auto rounded-lg shadow-md"
                 style="max-height: 200px;">
            <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs">
                الصورة الحالية
            </div>
        </div>
        <p class="text-sm text-gray-600">
            اسم الملف: {{ basename($record->image) }}
        </p>
    </div>
@else
    <div class="text-gray-500 text-sm">
        لا توجد صورة حالية
    </div>
@endif
