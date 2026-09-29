<?php

namespace App\Policies;

use App\Models\SuratRequest;
use App\Models\User;

class SuratRequestPolicy
{
    /** Admin dapat melihat/mengelola semua permohonan surat. */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, SuratRequest $surat): bool
    {
        return $user->isWarga() && $surat->user_id === $user->id;
    }

    public function download(User $user, SuratRequest $surat): bool
    {
        return $this->view($user, $surat) && in_array($surat->status, ['ditandatangani', 'selesai'], true);
    }
}
