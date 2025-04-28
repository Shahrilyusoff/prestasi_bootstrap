<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ms from '@/Lang/ms';

defineProps({
    evaluations: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <AppLayout>
        <Head :title="ms.evaluation.sections.bahagian_i" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Senarai Penilaian
                </h2>
                <Link v-if="$page.props.auth.user.isPPP || $page.props.auth.user.isAdmin || $page.props.auth.user.isSuperAdmin" 
                      :href="route('evaluations.create')" 
                      class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ ms.evaluation.create }}
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            PYD
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            PPP
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tahun
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kumpulan
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
                                    <tr v-for="evaluation in evaluations" :key="evaluation.id">
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ evaluation.ppp.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ evaluation.year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ evaluation.pyd_group.name }}
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
                                            <Link :href="route('evaluations.show', evaluation.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Lihat
                                            </Link>
                                            <Link v-if="($page.props.auth.user.isAdmin || $page.props.auth.user.isSuperAdmin) && evaluation.status === 'draf'" 
                                                  :href="route('evaluations.edit', evaluation.id)" 
                                                  class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                Edit
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>