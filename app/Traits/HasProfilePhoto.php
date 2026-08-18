<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    public function uploadProfilePhoto(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        if ($this->profile_photo_path) {
            Storage::disk('public')->delete($this->profile_photo_path);
        }

        return $file->store('profile-photos', 'public');
    }

    public function resetProfilePhoto(): void
    {
        if ($this->profile_photo_path) {
            Storage::disk('public')->delete($this->profile_photo_path);
            $this->update(['profile_photo_path' => null]);
        }
    }
}
