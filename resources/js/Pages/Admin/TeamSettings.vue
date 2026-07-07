<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import Toast from '@/Components/Toast.vue';

type PresetRow = {
    id: string | null;
    name: string;
    startTime: string;
    endTime: string;
};

type SettingsData = {
    workingHourPresets: PresetRow[];
    timeOffAutoApprove: boolean;
    defaultTask: string;
    registerEnabled: boolean;
    resetPasswordEnabled: boolean;
};

const props = defineProps<{
    siteName: string;
    settings: SettingsData;
    taskOptions: string[];
}>();

const presets = ref<PresetRow[]>(props.settings.workingHourPresets.map((preset) => ({ ...preset })));
const timeOffAutoApprove = ref(props.settings.timeOffAutoApprove);
const defaultTask = ref(props.settings.defaultTask);
const registerEnabled = ref(props.settings.registerEnabled);
const resetPasswordEnabled = ref(props.settings.resetPasswordEnabled);
const submitting = ref(false);
const toast = ref<{ type: string; message: string } | null>(null);

const addPreset = () => {
    presets.value.push({
        id: null,
        name: '',
        startTime: '09:00',
        endTime: '17:00',
    });
};

const removePreset = (index: number) => {
    if (presets.value.length <= 1) {
        return;
    }

    presets.value.splice(index, 1);
};

const submit = () => {
    submitting.value = true;

    router.put('/manage/settings', {
        working_hour_presets: presets.value.map((preset) => ({
            id: preset.id,
            name: preset.name,
            start_time: preset.startTime,
            end_time: preset.endTime,
        })),
        time_off_auto_approve: timeOffAutoApprove.value,
        default_task: defaultTask.value,
        register_enabled: registerEnabled.value,
        reset_password_enabled: resetPasswordEnabled.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.value = { type: 'success', message: 'Team settings saved.' };
        },
        onError: (errors: Record<string, string | string[]>) => {
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
        <AdminNav />

        <div class="space-y-6">
            <div class="card p-6">
                <h2 class="text-lg font-semibold text-slate-100">{{ siteName }}</h2>
                <p class="text-sm text-slate-400 mt-1">Site settings and default team configuration.</p>
            </div>

            <div class="card p-6 space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-100">Working hour presets</h2>
                    <p class="text-sm text-slate-400">Patterns members can choose during onboarding.</p>
                </div>

                <div
                    v-for="(preset, index) in presets"
                    :key="index"
                    class="grid grid-cols-1 sm:grid-cols-4 gap-3 p-4 rounded-lg bg-slate-900/50 border border-slate-800"
                >
                    <input v-model="preset.name" type="text" class="input" placeholder="Name" required>
                    <input v-model="preset.startTime" type="time" class="input" required>
                    <input v-model="preset.endTime" type="time" class="input" required>
                    <Button
                        v-if="presets.length > 1"
                        variant="ghost"
                        size="sm"
                        @click="removePreset(index)"
                    >
                        Remove
                    </Button>
                </div>

                <Button variant="secondary" size="sm" @click="addPreset">
                    Add preset
                </Button>
            </div>

            <div class="card p-6 space-y-4">
                <h2 class="text-lg font-semibold text-slate-100">Scheduling</h2>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Default task</label>
                    <select v-model="defaultTask" class="input">
                        <option value="none">None</option>
                        <option v-for="task in taskOptions" :key="task" :value="task">
                            {{ task }}
                        </option>
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input v-model="timeOffAutoApprove" type="checkbox" class="rounded border-slate-600 bg-slate-800 text-indigo-500">
                    Auto-approve time off (team default)
                </label>
            </div>

            <div class="card p-6 space-y-4">
                <h2 class="text-lg font-semibold text-slate-100">Authentication</h2>
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input v-model="registerEnabled" type="checkbox" class="rounded border-slate-600 bg-slate-800 text-indigo-500">
                    Allow user registration
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input v-model="resetPasswordEnabled" type="checkbox" class="rounded border-slate-600 bg-slate-800 text-indigo-500">
                    Allow password reset
                </label>
            </div>

            <Button class="w-full" :disabled="submitting" @click="submit">
                Save team settings
            </Button>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </AppLayout>
</template>
