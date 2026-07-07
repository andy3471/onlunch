<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import Toast from '@/Components/Toast.vue';

type TeamFormData = {
    id: string;
    name: string;
    minimumAvailableStaff: number | null;
};

const props = defineProps<{
    team: TeamFormData | null;
}>();

const isEditing = computed(() => props.team !== null);

const name = ref(props.team?.name ?? '');
const minimumAvailableStaff = ref(props.team?.minimumAvailableStaff?.toString() ?? '');
const submitting = ref(false);
const toast = ref<{ type: string; message: string } | null>(null);

const payload = () => ({
    name: name.value,
    minimum_available_staff: minimumAvailableStaff.value ? Number(minimumAvailableStaff.value) : null,
});

const submit = () => {
    submitting.value = true;

    const options = {
        preserveScroll: true,
        onSuccess: () => {
            toast.value = { type: 'success', message: isEditing.value ? 'Team updated.' : 'Team created.' };
        },
        onError: (errors: Record<string, string | string[]>) => {
            const firstError = Object.values(errors)[0];
            toast.value = {
                type: 'error',
                message: Array.isArray(firstError) ? firstError[0] : 'Could not save team.',
            };
        },
        onFinish: () => {
            submitting.value = false;
        },
    };

    if (isEditing.value && props.team) {
        router.put(`/manage/teams/${props.team.id}`, payload(), options);
    } else {
        router.post('/manage/teams', payload(), options);
    }
};
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="max-w-lg mx-auto card p-6 space-y-6">
            <h2 class="text-lg font-semibold text-slate-100">
                {{ isEditing ? 'Edit team' : 'Create team' }}
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Name</label>
                    <input v-model="name" type="text" class="input" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Minimum staff available</label>
                    <input
                        v-model="minimumAvailableStaff"
                        type="number"
                        min="1"
                        class="input"
                        placeholder="Leave empty to disable"
                    >
                    <p class="text-xs text-slate-500 mt-1">
                        Prevents too many people booking lunch or time off at once when this would drop available staff below this number.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <Button :disabled="submitting" @click="submit">
                    {{ isEditing ? 'Save changes' : 'Create team' }}
                </Button>
                <Link href="/manage/teams" class="text-sm text-slate-400 hover:text-slate-300">
                    Cancel
                </Link>
            </div>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </AppLayout>
</template>
