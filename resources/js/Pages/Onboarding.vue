<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/Button.vue';
import type { WorkingHourPresetData } from '@/Types/generated';

const props = defineProps<{
    presets: WorkingHourPresetData[];
}>();

const page = usePage();
const currentSite = computed(() => page.props.currentSite as { name: string } | null);

const selectedPresetId = ref(props.presets[0]?.id ?? '');
const submitting = ref(false);

const submit = () => {
    if (!selectedPresetId.value || submitting.value) return;

    submitting.value = true;

    router.post('/onboarding', {
        working_hour_preset_id: selectedPresetId.value,
    }, {
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
                <div class="text-center space-y-2">
                    <h1 class="text-xl font-semibold text-slate-100">Choose your working hours</h1>
                    <p class="text-sm text-slate-400">
                        <template v-if="currentSite">Set up your schedule for {{ currentSite.name }}.</template>
                        <template v-else>Set up your schedule for this site.</template>
                        You can change this later in settings.
                    </p>
                </div>

                <div class="space-y-3">
                    <label
                        v-for="preset in presets"
                        :key="preset.id"
                        class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-colors"
                        :class="selectedPresetId === preset.id
                            ? 'border-primary-500 bg-primary-500/10'
                            : 'border-slate-700 bg-slate-900/40 hover:border-slate-600'"
                    >
                        <input
                            v-model="selectedPresetId"
                            type="radio"
                            :value="preset.id"
                            class="text-primary-500 focus:ring-primary-500"
                        />
                        <div>
                            <div class="font-medium text-slate-100">{{ preset.name }}</div>
                            <div class="text-sm text-slate-400">{{ preset.startTime }} – {{ preset.endTime }}</div>
                        </div>
                    </label>
                </div>

                <Button
                    class="w-full"
                    :disabled="!selectedPresetId || submitting"
                    @click="submit"
                >
                    Continue
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
