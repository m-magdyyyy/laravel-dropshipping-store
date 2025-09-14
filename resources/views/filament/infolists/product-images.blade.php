@php
    $record = $getRecord();
@endphp

<div class="bg-gray-50 p-6 rounded-lg">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">🖼️ صور المنتج</h3>
    
    @if($record->getRawOriginal('image'))
        <div class="mb-6">
            <h4 class="font-medium mb-3 text-gray-700">الصورة الرئيسية:</h4>
            <div class="relative inline-block">
                <img src="/storage/{{ ltrim($record->getRawOriginal('image'), '/') }}" 
                     alt="صورة المنتج الرئيسية" 
                     class="max-w-sm h-auto rounded-lg shadow-md border-2 border-gray-200">
                <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs">
                    رئيسية
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">📁 {{ basename($record->getRawOriginal('image')) }}</p>
        </div>
    @endif
    
    @if($record->gallery && is_array($record->gallery) && count($record->gallery) > 0)
        <div>
            <h4 class="font-medium mb-3 text-gray-700">معرض الصور ({{ count($record->gallery) }} صورة):</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($record->gallery as $index => $galleryImage)
                    <div class="relative group">
                        <img src="/storage/{{ ltrim($galleryImage, '/') }}" 
                             alt="صورة معرض {{ $index + 1 }}" 
                             class="w-full h-32 object-cover rounded-lg shadow-sm border border-gray-200 group-hover:shadow-lg transition-shadow">
                        <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded text-xs">
                            {{ $index + 1 }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1 truncate">{{ basename($galleryImage) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    @if(!$record->getRawOriginal('image') && (!$record->gallery || count($record->gallery) === 0))
        <div class="text-center text-gray-500 py-8">
            <div class="text-4xl mb-2">📷</div>
            <p>لا توجد صور لهذا المنتج</p>
        </div>
    @endif
</div>
