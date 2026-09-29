<?php

namespace App\Policies;

use App\Models\Pengaduan;
use App\Models\User;

class PengaduanPolicy
{
    /** Admin dapat melihat/mengelola semua pengaduan. */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Pengaduan $pengaduan): bool
    {
        if ($user->isWarga()) {
            return $pengaduan->user_id === $user->id;
        }

        if ($user->isKadus()) {
            return $pengaduan->dusunAsal() === $user->dusun_id;
        }

        return false;
    }

    /** Kepala Dusun boleh menanggapi & menandai status pengaduan dari dusunnya sendiri. */
    public function respond(User $user, Pengaduan $pengaduan): bool
    {
        return $this->view($user, $pengaduan);
    }
}
