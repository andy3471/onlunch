<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { index as manageUsers } from '@/routes/manage/users';
import { index as manageTeams } from '@/routes/manage/teams';
import { index as manageTasks } from '@/routes/manage/tasks';
import { show as manageSettings } from '@/routes/manage/settings';
import { index as manageTimeOff } from '@/routes/manage/time-off-requests';

const page = usePage();

const currentPath = computed(() => page.url.split('?')[0] ?? '');

const isSiteAdmin = computed(() => page.props.auth.user?.is_admin ?? false);

type AdminNavLink = {
    path: string;
    href: string;
    label: string;
};

const adminLinks: AdminNavLink[] = [
    { path: '/manage/users', href: manageUsers.url(), label: 'Users' },
    { path: '/manage/teams', href: manageTeams.url(), label: 'Teams' },
    { path: '/manage/tasks', href: manageTasks.url(), label: 'Tasks' },
    { path: '/manage/settings', href: manageSettings.url(), label: 'Site settings' },
    { path: '/manage/time-off-requests', href: manageTimeOff.url(), label: 'Time off' },
];

const approverLinks: AdminNavLink[] = [
    { path: '/manage/time-off-requests', href: manageTimeOff.url(), label: 'Time off' },
];

const links = computed(() => (isSiteAdmin.value ? adminLinks : approverLinks));

const isActive = (path: string): boolean => currentPath.value === path || currentPath.value.startsWith(`${path}/`);
</script>

<template>
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-white mb-3">{{ isSiteAdmin ? 'Manage' : 'Time off' }}</h1>
        <nav class="flex flex-wrap gap-1 p-1 rounded-xl bg-white/5 border border-white/5">
            <Link
                v-for="link in links"
                :key="link.path"
                :href="link.href"
                class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                :class="isActive(link.path) ? 'nav-link-active' : 'nav-link'"
            >
                {{ link.label }}
            </Link>
        </nav>
    </div>
</template>
