<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const currentPath = computed(() => page.url.split('?')[0]);

const isSiteAdmin = computed(() => page.props.auth?.user?.is_admin ?? false);

const adminLinks = [
    { href: '/manage/users', label: 'Users' },
    { href: '/manage/teams', label: 'Teams' },
    { href: '/manage/tasks', label: 'Tasks' },
    { href: '/manage/settings', label: 'Settings' },
    { href: '/manage/time-off-requests', label: 'Time off' },
];

const approverLinks = [
    { href: '/manage/time-off-requests', label: 'Time off' },
];

const links = computed(() => (isSiteAdmin.value ? adminLinks : approverLinks));

const isActive = (href: string): boolean => currentPath.value === href || currentPath.value.startsWith(`${href}/`);
</script>

<template>
    <div class="mb-6">
        <h1 class="text-lg font-semibold text-white mb-3">{{ isSiteAdmin ? 'Manage' : 'Time off' }}</h1>
        <nav class="flex flex-wrap gap-1 p-1 rounded-xl bg-white/5 border border-white/5">
            <Link
                v-for="link in links"
                :key="link.href"
                :href="link.href"
                class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                :class="isActive(link.href) ? 'nav-link-active' : 'nav-link'"
            >
                {{ link.label }}
            </Link>
        </nav>
    </div>
</template>
