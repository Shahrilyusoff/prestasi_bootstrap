<script setup>
import ms from '@/Lang/ms';
const user = usePage().props.auth.user;

const getWelcomeMessage = () => {
    if (user.isSuperAdmin) {
        return 'Selamat datang, Super Admin';
    } else if (user.isAdmin) {
        return 'Selamat datang, Admin';
    } else if (user.isPPP) {
        return 'Selamat datang, Pegawai Penilai Pertama';
    } else if (user.isPPK) {
        return 'Selamat datang, Pegawai Penilai Kedua';
    } else if (user.isPYD) {
        return 'Selamat datang, Pegawai Yang Dinilai';
    }
    return 'Selamat datang';
};
</script>

<template>
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            {{ getWelcomeMessage() }}
        </h2>
        <p class="text-gray-600">
            Sistem Penilaian Prestasi Kakitangan Kontrak Kerajaan
        </p>
    </div>
</template>