<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';

type UserRow = {
    id: string;
    name: string;
    email: string;
    isAdmin: boolean;
    teamNames: string[];
    isScheduled: boolean;
};

defineProps<{
    users: UserRow[];
}>();
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
                <div>
                    <h2 class="text-lg font-semibold text-slate-100">Users</h2>
                    <p class="text-sm text-slate-400">Site members and their team assignments.</p>
                </div>
                <Link href="/manage/users/create">
                    <Button>Add user</Button>
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/50 text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">Name</th>
                            <th class="px-6 py-3 font-medium">Email</th>
                            <th class="px-6 py-3 font-medium">Teams</th>
                            <th class="px-6 py-3 font-medium">Admin</th>
                            <th class="px-6 py-3 font-medium">On schedule</th>
                            <th class="px-6 py-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <tr v-for="user in users" :key="user.id" class="text-slate-200">
                            <td class="px-6 py-3">{{ user.name }}</td>
                            <td class="px-6 py-3">{{ user.email }}</td>
                            <td class="px-6 py-3 text-slate-400">
                                {{ user.teamNames.length ? user.teamNames.join(', ') : '—' }}
                            </td>
                            <td class="px-6 py-3">{{ user.isAdmin ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-3">{{ user.isScheduled ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-3 text-right">
                                <Link
                                    :href="`/manage/users/${user.id}/edit`"
                                    class="text-indigo-400 hover:text-indigo-300"
                                >
                                    Edit
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                No users yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
