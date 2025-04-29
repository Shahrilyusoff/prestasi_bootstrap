<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import WelcomeBanner from '@/Components/WelcomeBanner.vue';
import { Head, Link } from '@inertiajs/vue3';
import ms from '@/Lang/ms';

defineProps({
    pendingEvaluations: {
        type: Array,
        default: () => [],
    },
    recentNotifications: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <AppLayout>
        <Head :title="ms.app.title" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ms.navigation.dashboard }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <WelcomeBanner />

                <!-- Pending Evaluations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6" v-if="pendingEvaluations.length > 0">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Penilaian Menunggu Tindakan Anda
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            PYD
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tahun
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tindakan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="evaluation in pendingEvaluations" :key="evaluation.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ evaluation.pyd.name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ evaluation.pyd.position }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ evaluation.year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                                  :class="{
                                                      'bg-yellow-100 text-yellow-800': evaluation.status === 'draf',
                                                      'bg-blue-100 text-blue-800': evaluation.status === 'pyd_submit',
                                                      'bg-green-100 text-green-800': evaluation.status === 'ppp_submit',
                                                      'bg-purple-100 text-purple-800': evaluation.status === 'selesai',
                                                  }">
                                                {{ ms.evaluation.statuses[evaluation.status] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <Link :href="route('evaluations.show', evaluation.id)" class="text-indigo-600 hover:text-indigo-900">
                                                Lihat
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Notifications -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="recentNotifications.length > 0">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Notifikasi Terkini
                        </h3>
                        <ul class="divide-y divide-gray-200">
                            <li v-for="notification in recentNotifications" :key="notification.id" class="py-4">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <Link :href="notification.action_url" class="hover:underline">
                                                {{ notification.message }}
                                            </Link>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ notification.created_at }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-4">
                            <Link :href="route('notifications.index')" class="text-sm text-indigo-600 hover:text-indigo-900">
                                Lihat semua notifikasi
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>