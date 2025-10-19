<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class S3DirectUpload extends Field
{
    protected string $view = 'filament.forms.components.s3-direct-upload';

    protected array $acceptedFileTypes = [];
    protected int $maxFileSize = 50 * 1024 * 1024; // 50MB
    protected bool $multiple = false;
    protected ?string $directory = null;
    protected ?string $diskName = null;

    public function acceptedFileTypes(array $types): static
    {
        $this->acceptedFileTypes = $types;
        return $this;
    }

    public function maxFileSize(int $size): static
    {
        $this->maxFileSize = $size;
        return $this;
    }

    public function multiple(bool $condition = true): static
    {
        $this->multiple = $condition;
        return $this;
    }

    public function directory(string $directory): static
    {
        $this->directory = $directory;
        return $this;
    }

    public function disk(string $diskName): static
    {
        $this->diskName = $diskName;
        return $this;
    }

    public function getAcceptedFileTypes(): array
    {
        return $this->acceptedFileTypes ?: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function getDiskName(): ?string
    {
        return $this->diskName ?: 's3_processed';
    }

    public function getUploadUrl(): string
    {
        return '/api/s3/presigned-url';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(static function (S3DirectUpload $component, $state): void {
            if (!$state) {
                return;
            }

            // إذا كان الـ state عبارة عن URL، نحتفظ به
            if (is_string($state) && (str_starts_with($state, 'http') || str_starts_with($state, 'https'))) {
                $component->state($state);
                return;
            }

            // إذا كان الـ state عبارة عن مسار ملف، نحوله إلى URL كامل
            if (is_string($state)) {
                try {
                    // بناء URL يدوياً لـ S3
                    $diskConfig = config('filesystems.disks.' . $component->getDiskName());
                    if (isset($diskConfig['url'])) {
                        $url = rtrim($diskConfig['url'], '/') . '/' . $state;
                        $component->state($url);
                    } else {
                        $component->state($state);
                    }
                } catch (\Exception $e) {
                    // في حالة الخطأ، نحتفظ بالمسار الأصلي
                    $component->state($state);
                }
            }
        });

        $this->dehydrateStateUsing(static function (S3DirectUpload $component, $state) {
            // إذا كان الـ state عبارة عن URL كامل، نحوله إلى مسار نسبي
            if (is_string($state) && str_starts_with($state, 'http')) {
                // استخراج اسم الملف من URL
                $path = parse_url($state, PHP_URL_PATH);
                $filename = basename($path);
                return $filename;
            }

            return $state;
        });
    }
}