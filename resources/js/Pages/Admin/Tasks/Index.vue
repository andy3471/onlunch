<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import type { TaskOptionData } from '@/Types/generated';
import { create, edit } from '@/routes/manage/tasks';

defineProps<{
    tasks: TaskOptionData[];
}>();
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-white/10 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-white">Default tasks</h2>
                    <p class="mt-0.5 text-sm text-slate-400">Task names and colours shown as suggestions on the schedule.</p>
                </div>
                <Link :href="create.url()">
                    <Button>Add task</Button>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="px-6">Name</th>
                            <th class="px-6">Colour</th>
                            <th class="px-6" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="task in tasks"
                            :key="task.id"
                            class="text-slate-200"
                        >
                            <td class="px-6">{{ task.name }}</td>
                            <td class="px-6">
                                <span
                                    v-if="task.color"
                                    class="inline-block h-5 w-5 rounded border border-white/15"
                                    :style="{ backgroundColor: task.color }"
                                />
                                <span
                                    v-else
                                    class="text-slate-500"
                                >—</span>
                            </td>
                            <td class="px-6 text-right">
                                <Link
                                    :href="edit.url({ task: task.id })"
                                    class="text-sm text-primary-300 hover:text-primary-200"
                                >
                                    Edit
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="tasks.length === 0">
                            <td
                                colspan="3"
                                class="px-6 py-8 text-center text-slate-500"
                            >
                                No tasks yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
