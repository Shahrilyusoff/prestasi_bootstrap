<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ms from '@/Lang/ms';

defineProps({
    notifications: Array,
});

const markAllAsRead = () => {
    router.put(route('notifications.mark-all-read'));
};

const deleteNotification = (notificationId) => {
    if (confirm('Adakah anda pasti ingin memadam notifikasi ini?')) {
        router.delete(route('notifications.destroy', notificationId));
    }
};
</script>

<template>
    <AppLayout>
        <Head :title="ms.navigation.notifications" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ ms.navigation.notifications }}
                </h2>
                <PrimaryButton @click="markAllAsRead" class="ml-4">
                    {{ ms.notification.mark_all_read }}
                </PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ul class="divide-y divide-gray-200">
                            <li v-for="notification in notifications" :key="notification.id" class="py-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <Link :href="notification.action_url" class="block hover:bg-gray-50">
                                            <p class="text-sm font-medium text-gray-900" :class="{ 'font-normal': notification.is_read }">
                                                {{ notification.message }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ notification.created_at }}
                                            </p>
                                        </Link>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <button
                                            @click="deleteNotification(notification.id)"
                                            class="text-red-600 hover:text-red-900 text-sm"
                                        >
                                            {{ ms.notification.delete }}
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div v-if="notifications.length === 0" class="text-center py-8">
                            <p class="text-gray-500">{{ ms.notification.no_notifications }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>