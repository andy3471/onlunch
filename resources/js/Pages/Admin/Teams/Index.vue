<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';

type TeamRow = {
    id: string;
    name: string;
    minimumAvailableStaff: number | null;
    tasksEnabled: boolean;
};

defineProps<{
    teams: TeamRow[];
}>();
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-100">Teams</h2>
                    <p class="text-sm text-slate-400 mt-1">Organisational subgroups within this site.</p>
                </div>
                <Link href="/manage/teams/create">
                    <Button>Create team</Button>
                </Link>
            </div>

            <div class="card overflow-hidden">
                <ul class="divide-y divide-slate-800">
                    <li
                        v-for="team in teams"
                        :key="team.id"
                        class="px-6 py-4 flex items-center justify-between gap-4"
                    >
                        <div>
                            <p class="font-medium text-slate-100">{{ team.name }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                <template v-if="team.minimumAvailableStaff">
                                    Min staff: {{ team.minimumAvailableStaff }}
                                </template>
                                <template v-else>No minimum staff rule</template>
                            </p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span
                                class="text-xs px-2 py-1 rounded-full"
                                :class="team.tasksEnabled ? 'bg-emerald-900/40 text-emerald-300' : 'bg-slate-800 text-slate-400'"
                            >
                                {{ team.tasksEnabled ? 'Tasks on' : 'Tasks off' }}
                            </span>
                            <Link
                                :href="`/manage/teams/${team.id}/edit`"
                                class="text-sm text-primary-400 hover:text-primary-300"
                            >
                                Edit
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
