<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ms from '@/Lang/ms';

const props = defineProps({
    evaluation: Object,
    workTarget: Object,
    type: String,
});

const form = useForm({
    items: props.workTarget?.items || [],
    pyd_report: props.workTarget?.pyd_report || '',
    ppp_report: props.workTarget?.ppp_report || '',
    approved: props.workTarget?.approved || false,
});

const addItem = () => {
    form.items.push({
        activity: '',
        performance_indicator: '',
        is_added: props.type === 'pertengahan_tahun',
        is_removed: false,
    });
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};

const submit = () => {
    if (props.workTarget) {
        form.put(route('work-targets.update', props.workTarget.id));
    } else {
        form.post(route('evaluations.work-targets.store', {
            evaluation: props.evaluation.id,
            type: props.type,
        }));
    }
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ ms.work_target.types[type] }}
            </h3>

            <div v-for="(item, index) in form.items" :key="index" class="mb-6 p-4 border border-gray-200 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="activity" :value="ms.work_target.activity" />
                        <TextInput
                            id="activity"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="item.activity"
                            required
                        />
                    </div>
                    <div>
                        <InputLabel for="indicator" :value="ms.work_target.indicator" />
                        <TextInput
                            id="indicator"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="item.performance_indicator"
                            required
                        />
                    </div>
                </div>
                <button
                    type="button"
                    @click="removeItem(index)"
                    class="mt-2 text-sm text-red-600 hover:text-red-900"
                >
                    {{ ms.buttons.delete }}
                </button>
            </div>

            <button
                type="button"
                @click="addItem"
                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                {{ ms.work_target.add_item }}
            </button>
        </div>

        <div v-if="type === 'akhir_tahun'" class="mt-6">
            <InputLabel for="pyd_report" :value="ms.work_target.pyd_report" />
            <textarea
                id="pyd_report"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                v-model="form.pyd_report"
                rows="4"
            ></textarea>
        </div>

        <div v-if="type === 'akhir_tahun' && $page.props.auth.user.isPPP" class="mt-6">
            <InputLabel for="ppp_report" :value="ms.work_target.ppp_report" />
            <textarea
                id="ppp_report"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                v-model="form.ppp_report"
                rows="4"
            ></textarea>
        </div>

        <div v-if="type === 'awal_tahun' && $page.props.auth.user.isPPP" class="mt-6">
            <label class="flex items-center">
                <input
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    v-model="form.approved"
                />
                <span class="ml-2 text-sm text-gray-600">{{ ms.work_target.approve }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ ms.buttons.save }}
            </PrimaryButton>
        </div>
    </form>
</template>