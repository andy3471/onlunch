<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';

type TaskRow = {
    id: string;
    name: string;
    color: string | null;
};

defineProps<{
    tasks: TaskRow[];
}>();
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
                <div>
                    <h2 class="text-lg font-semibold text-slate-100">Default tasks</h2>
                    <p class="text-sm text-slate-400">Task names and colours shown as suggestions on the schedule.</p>
                </div>
                <Link href="/manage/tasks/create">
                    <Button>Add task</Button>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/50 text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Colour</th>
                            <th class="px-6 py-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <tr v-for="task in tasks" :key="task.id" class="text-slate-200">
                            <td class="px-6 py-3">{{ task.name }}</td>
                            <td class="px-6 py-3">
                                <span
                                    v-if="task.color"
                                    class="inline-block w-5 h-5 rounded border border-slate-700"
                                    :style="{ backgroundColor: task.color }"
                                />
                                <span v-else class="text-slate-500">—</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <Link
                                    :href="`/manage/tasks/${task.id}/edit`"
                                    class="text-indigo-400 hover:text-indigo-300"
                                >
                                    Edit
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="tasks.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                No tasks yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
