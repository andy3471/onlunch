<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import type { UserRowData } from '@/Types/generated';
import { create, edit } from '@/routes/manage/users';

defineProps<{
    users: UserRowData[];
}>();
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="flex items-center justify-between gap-4 border-b border-white/10 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-white">Users</h2>
                    <p class="mt-0.5 text-sm text-slate-400">Site members and their team assignments.</p>
                </div>
                <Link :href="create.url()">
                    <Button>Add user</Button>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="px-6">Name</th>
                            <th class="px-6">Email</th>
                            <th class="px-6">Teams</th>
                            <th class="px-6">Admin</th>
                            <th class="px-6">On schedule</th>
                            <th class="px-6" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="text-slate-200"
                        >
                            <td class="px-6">{{ user.name }}</td>
                            <td class="px-6">{{ user.email }}</td>
                            <td class="px-6 text-slate-400">
                                {{ user.teamNames.length ? user.teamNames.join(', ') : '—' }}
                            </td>
                            <td class="px-6">
                                <span
                                    class="rounded-md px-2 py-0.5 text-xs font-medium"
                                    :class="user.isAdmin ? 'bg-primary-600/15 text-primary-300' : 'bg-white/5 text-slate-400'"
                                >
                                    {{ user.isAdmin ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6">
                                <span
                                    class="rounded-md px-2 py-0.5 text-xs font-medium"
                                    :class="user.isScheduled ? 'bg-primary-600/15 text-primary-300' : 'bg-white/5 text-slate-400'"
                                >
                                    {{ user.isScheduled ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 text-right">
                                <Link
                                    :href="edit.url({ user: user.id })"
                                    class="text-sm text-primary-300 hover:text-primary-200"
                                >
                                    Edit
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td
                                colspan="6"
                                class="px-6 py-8 text-center text-slate-500"
                            >
                                No users yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
