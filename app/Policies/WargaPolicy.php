<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warga;

class WargaPolicy
{
    /** Admin dapat melihat/mengelola semua data warga lintas dusun. */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function update(User $user, Warga $warga): bool
    {
        return $user->isKadus() && $warga->dusun_id === $user->dusun_id;
    }
}
