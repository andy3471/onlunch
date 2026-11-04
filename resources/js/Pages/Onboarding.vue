<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/Button.vue';
import type { WorkingHourPresetData } from '@/Types/generated';
import { store as storeOnboarding } from '@/routes/onboarding';

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

    router.post(storeOnboarding.url(), {
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

                <div
                    v-if="presets.length === 0"
                    class="rounded-lg border border-amber-500/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-100/90"
                >
                    No working-hour presets are set up for this site yet. Ask an admin to add them in Manage → Site settings.
                </div>

                <div
                    v-else
                    class="space-y-3"
                >
                    <label
                        v-for="preset in presets"
                        :key="preset.id"
                        class="flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition-colors"
                        :class="selectedPresetId === preset.id
                            ? 'border-primary-500 bg-primary-500/10'
                            : 'border-white/10 bg-dark-900/40 hover:border-white/20'"
                    >
                        <input
                            v-model="selectedPresetId"
                            type="radio"
                            :value="preset.id"
                            class="text-primary-500 focus:ring-primary-500"
                        >
                        <div>
                            <div class="font-medium text-slate-100">{{ preset.name }}</div>
                            <div class="text-sm text-slate-400">{{ preset.startTime }} – {{ preset.endTime }}</div>
                        </div>
                    </label>
                </div>

                <Button
                    class="w-full"
                    :disabled="!selectedPresetId || submitting || presets.length === 0"
                    @click="submit"
                >
                    Continue
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
