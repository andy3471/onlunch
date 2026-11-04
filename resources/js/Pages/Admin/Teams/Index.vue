<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import type { TeamRowData } from '@/Types/generated';
import { create, edit } from '@/routes/manage/teams';

defineProps<{
    teams: TeamRowData[];
}>();
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-white/10 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-white">Teams</h2>
                    <p class="mt-0.5 text-sm text-slate-400">Organisational subgroups within this site.</p>
                </div>
                <Link :href="create.url()">
                    <Button>Create team</Button>
                </Link>
            </div>

            <ul class="divide-y divide-white/5">
                <li
                    v-for="team in teams"
                    :key="team.id"
                    class="flex items-center justify-between gap-4 px-6 py-4"
                >
                    <div>
                        <p class="font-medium text-white">{{ team.name }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">
                            <template v-if="team.minimumAvailableStaff">
                                Min staff: {{ team.minimumAvailableStaff }}
                            </template>
                            <template v-else>No minimum staff rule</template>
                        </p>
                    </div>
                    <div class="flex shrink-0 items-center gap-3">
                        <span
                            class="rounded-md px-2 py-0.5 text-xs font-medium"
                            :class="team.tasksEnabled ? 'bg-primary-600/15 text-primary-300' : 'bg-white/5 text-slate-400'"
                        >
                            {{ team.tasksEnabled ? 'Tasks on' : 'Tasks off' }}
                        </span>
                        <Link
                            :href="edit.url({ team: team.id })"
                            class="text-sm text-primary-300 hover:text-primary-200"
                        >
                            Edit
                        </Link>
                    </div>
                </li>
                <li
                    v-if="teams.length === 0"
                    class="px-6 py-8 text-center text-sm text-slate-500"
                >
                    No teams yet.
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
