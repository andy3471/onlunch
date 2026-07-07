<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/Button.vue';
import Toast from '@/Components/Toast.vue';
import type { WorkingHourPresetData } from '@/Types/generated';

const props = defineProps<{
    presets: WorkingHourPresetData[];
    workingHourPresetId: string | null;
    timeOffRequiresApproval: boolean;
}>();

const page = usePage();
const currentSite = computed(() => page.props.currentSite as { name: string } | null);
const workingHourPresetId = ref(props.workingHourPresetId ?? props.presets[0]?.id ?? '');
const submitting = ref(false);
const toast = ref<{ type: string; message: string } | null>(null);

const submit = () => {
    submitting.value = true;

    router.put('/settings', {
        working_hour_preset_id: workingHourPresetId.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.value = { type: 'success', message: 'Settings saved.' };
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.value = {
                type: 'error',
                message: Array.isArray(firstError) ? firstError[0] : 'Could not save settings.',
            };
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="max-w-lg mx-auto">
            <div class="card p-6 space-y-6">
                <div>
                    <h1 class="text-xl font-semibold text-slate-100">Your settings</h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Working hours for
                        <span v-if="currentSite" class="text-slate-300">{{ currentSite.name }}</span>
                        <span v-else>this site</span>.
                    </p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Working hours</label>
                        <select v-model="workingHourPresetId" class="input">
                            <option v-for="preset in presets" :key="preset.id" :value="preset.id">
                                {{ preset.label }}
                            </option>
                        </select>
                    </div>

                    <p class="text-sm text-slate-400 rounded-lg bg-slate-900/50 border border-slate-800 px-4 py-3">
                        <template v-if="timeOffRequiresApproval">
                            Time off requests need admin approval before they appear on the schedule.
                        </template>
                        <template v-else>
                            Time off requests are approved automatically by your team settings.
                        </template>
                    </p>
                </div>

                <Button class="w-full" :disabled="submitting" @click="submit">
                    Save settings
                </Button>
            </div>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </AppLayout>
</template>
