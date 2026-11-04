<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import Toast from '@/Components/Toast.vue';
import { useToast } from '@/Composables/useToast';
import { destroy as destroyTask, index as tasksIndex, store, update } from '@/routes/manage/tasks';

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
const color = ref(props.task?.color ?? '#14b8a6');
const submitting = ref(false);
const { toast, showSuccess, showFirstError } = useToast();

const submit = () => {
    submitting.value = true;

    const options = {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess(isEditing.value ? 'Task updated.' : 'Task created.');
        },
        onError: (errors: Record<string, string>) => {
            showFirstError(errors, 'Could not save task.');
        },
        onFinish: () => {
            submitting.value = false;
        },
    };

    const payload = { name: name.value, color: color.value || null };

    if (isEditing.value && props.task) {
        router.put(update.url({ task: props.task.id }), payload, options);
    } else {
        router.post(store.url(), payload, options);
    }
};

const destroy = () => {
    if (!props.task || !confirm('Remove this task?')) {
        return;
    }

    router.delete(destroyTask.url({ task: props.task.id }));
};
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card mx-auto max-w-lg space-y-6 p-6">
            <h2 class="text-lg font-semibold tracking-tight text-white">
                {{ isEditing ? 'Edit task' : 'Add task' }}
            </h2>

            <div class="space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Name</label>
                    <input
                        v-model="name"
                        type="text"
                        class="input"
                        required
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Colour</label>
                    <input
                        v-model="color"
                        type="color"
                        class="h-10 w-20 rounded-md border border-white/10 bg-dark-950"
                    >
                </div>
            </div>

            <div class="flex items-center gap-3">
                <Button
                    class="flex-1"
                    :disabled="submitting"
                    @click="submit"
                >
                    {{ isEditing ? 'Save changes' : 'Create task' }}
                </Button>
                <Link
                    :href="tasksIndex.url()"
                    class="text-sm text-slate-400 hover:text-slate-200"
                >
                    Cancel
                </Link>
            </div>

            <div
                v-if="isEditing"
                class="border-t border-white/10 pt-4"
            >
                <Button
                    variant="danger"
                    size="sm"
                    @click="destroy"
                >
                    Remove task
                </Button>
            </div>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </AppLayout>
</template>
