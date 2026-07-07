<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import Toast from '@/Components/Toast.vue';

type TaskFormData = {
    id: string;
    name: string;
    color: string | null;
};

const props = defineProps<{
    task: TaskFormData | null;
}>();

const isEditing = computed(() => props.task !== null);

const name = ref(props.task?.name ?? '');
const color = ref(props.task?.color ?? '#6366f1');
const submitting = ref(false);
const toast = ref<{ type: string; message: string } | null>(null);

const submit = () => {
    submitting.value = true;

    const options = {
        preserveScroll: true,
        onSuccess: () => {
            toast.value = { type: 'success', message: isEditing.value ? 'Task updated.' : 'Task created.' };
        },
        onError: (errors: Record<string, string | string[]>) => {
            const firstError = Object.values(errors)[0];
            toast.value = {
                type: 'error',
                message: Array.isArray(firstError) ? firstError[0] : 'Could not save task.',
            };
        },
        onFinish: () => {
            submitting.value = false;
        },
    };

    const payload = { name: name.value, color: color.value || null };

    if (isEditing.value && props.task) {
        router.put(`/manage/tasks/${props.task.id}`, payload, options);
    } else {
        router.post('/manage/tasks', payload, options);
    }
};

const destroy = () => {
    if (!props.task || !confirm('Remove this task?')) {
        return;
    }

    router.delete(`/manage/tasks/${props.task.id}`);
};
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="max-w-lg mx-auto card p-6 space-y-6">
            <h2 class="text-lg font-semibold text-slate-100">
                {{ isEditing ? 'Edit task' : 'Add task' }}
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Name</label>
                    <input v-model="name" type="text" class="input" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Colour</label>
                    <input v-model="color" type="color" class="h-10 w-20 rounded border border-slate-700 bg-slate-800">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <Button class="flex-1" :disabled="submitting" @click="submit">
                    {{ isEditing ? 'Save changes' : 'Create task' }}
                </Button>
                <Link href="/manage/tasks" class="text-sm text-slate-400 hover:text-slate-300">
                    Cancel
                </Link>
            </div>

            <div v-if="isEditing" class="pt-4 border-t border-slate-800">
                <Button variant="danger" size="sm" @click="destroy">
                    Remove task
                </Button>
            </div>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </AppLayout>
</template>
