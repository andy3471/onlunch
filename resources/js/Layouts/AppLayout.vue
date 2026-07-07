<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems, TransitionRoot } from '@headlessui/vue';
import AppLogo from '@/Components/AppLogo.vue';
import Toast from '@/Components/Toast.vue';

const page = usePage();

const auth = computed(() => page.props.auth || { user: null });
const config = computed(() => page.props.config || {});
const flash = computed(() => page.props.flash || {});
const currentSite = computed(() => page.props.currentSite || null);
const userSites = computed(() => page.props.userSites || []);
const otherSites = computed(() => userSites.value.filter((site) => !site.isCurrent));

const currentPath = computed(() => page.url.split('?')[0]);
const isDashboard = computed(() => currentPath.value === '/');
const isManage = computed(() => currentPath.value.startsWith('/manage'));
const isTimeOffManage = computed(() => currentPath.value.startsWith('/manage/time-off-requests'));
const showManageLink = computed(() => auth.value.user?.is_admin ?? false);
const showTimeOffLink = computed(() => {
    const user = auth.value.user;

    return user?.can_manage_time_off && !user?.is_admin;
});

const userInitial = computed(() => auth.value.user?.name?.charAt(0)?.toUpperCase() ?? '?');

const navLinkClass = (active) => (active ? 'nav-link nav-link-active' : 'nav-link');
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <header class="sticky top-0 z-40 border-b border-white/5 bg-slate-950/75 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Disclosure v-slot="{ open, close }">
                    <div class="flex items-center justify-between h-14 gap-4">
                        <div class="flex items-center gap-5 min-w-0">
                            <Link href="/" class="flex items-center gap-3 min-w-0 group">
                                <AppLogo />
                                <div class="min-w-0 leading-tight">
                                    <p class="text-sm font-semibold text-white truncate group-hover:text-primary-100 transition-colors">
                                        {{ currentSite?.name ?? config.appName }}
                                    </p>
                                    <p v-if="currentSite" class="text-[11px] text-slate-500 truncate">
                                        {{ config.appName }}
                                    </p>
                                </div>
                            </Link>

                            <nav v-if="auth.user" class="hidden md:flex items-center gap-0.5">
                                <Link href="/" :class="navLinkClass(isDashboard)">
                                    Schedule
                                </Link>
                                <Link
                                    v-if="showManageLink"
                                    href="/manage/users"
                                    :class="navLinkClass(isManage)"
                                >
                                    Manage
                                </Link>
                                <Link
                                    v-if="showTimeOffLink"
                                    href="/manage/time-off-requests"
                                    :class="navLinkClass(isTimeOffManage)"
                                >
                                    Time off
                                </Link>
                            </nav>
                        </div>

                        <div class="hidden md:flex items-center gap-2 shrink-0">
                            <template v-if="auth.user">
                                <Menu as="div" class="relative">
                                    <MenuButton class="flex items-center gap-2 pl-1.5 pr-2.5 py-1 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 transition-colors">
                                        <span class="flex size-7 items-center justify-center rounded-full bg-primary-600/90 text-xs font-semibold text-white">
                                            {{ userInitial }}
                                        </span>
                                        <span class="text-sm font-medium text-slate-200 max-w-[8rem] truncate">
                                            {{ auth.user.name }}
                                        </span>
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </MenuButton>

                                    <TransitionRoot
                                        enter="transition ease-out duration-100"
                                        enter-from="transform opacity-0 scale-95"
                                        enter-to="transform opacity-100 scale-100"
                                        leave="transition ease-in duration-75"
                                        leave-from="transform opacity-100 scale-100"
                                        leave-to="transform opacity-0 scale-95"
                                    >
                                        <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl bg-slate-900/95 border border-white/10 shadow-xl shadow-black/40 backdrop-blur-md focus:outline-hidden py-1">
                                            <div
                                                v-if="currentSite"
                                                class="px-4 py-2.5 text-xs text-slate-500 border-b border-white/5"
                                            >
                                                Signed in to <span class="text-slate-300">{{ currentSite.name }}</span>
                                            </div>
                                            <template v-if="otherSites.length > 0">
                                                <div class="px-4 pt-2 pb-1 text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                                    Switch site
                                                </div>
                                                <MenuItem
                                                    v-for="site in otherSites"
                                                    :key="site.id"
                                                    v-slot="{ active }"
                                                >
                                                    <a
                                                        :href="site.url"
                                                        :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block px-4 py-2 text-sm']"
                                                    >
                                                        {{ site.name }}
                                                    </a>
                                                </MenuItem>
                                                <div class="my-1 border-b border-white/5" />
                                            </template>
                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    href="/settings"
                                                    :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block px-4 py-2 text-sm']"
                                                >
                                                    Settings
                                                </Link>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    href="/change-password"
                                                    :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block px-4 py-2 text-sm']"
                                                >
                                                    Change password
                                                </Link>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    href="/logout"
                                                    method="post"
                                                    as="button"
                                                    type="button"
                                                    :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block w-full text-left px-4 py-2 text-sm']"
                                                >
                                                    Log out
                                                </Link>
                                            </MenuItem>
                                        </MenuItems>
                                    </TransitionRoot>
                                </Menu>
                            </template>
                            <template v-else>
                                <Link href="/login" class="nav-link">
                                    Log in
                                </Link>
                                <Link
                                    v-if="config.registerEnabled"
                                    href="/register"
                                    class="btn btn-primary text-sm px-4 py-1.5"
                                >
                                    Register
                                </Link>
                            </template>
                        </div>

                        <DisclosureButton class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    v-if="!open"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    v-else
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </DisclosureButton>
                    </div>

                    <DisclosurePanel class="md:hidden py-2 border-t border-white/5 space-y-0.5">
                        <template v-if="auth.user">
                            <Link
                                href="/"
                                :class="[navLinkClass(isDashboard), 'block mx-1']"
                                @click="close"
                            >
                                Schedule
                            </Link>
                            <Link
                                v-if="showManageLink"
                                href="/manage/users"
                                :class="[navLinkClass(isManage), 'block mx-1']"
                                @click="close"
                            >
                                Manage
                            </Link>
                            <Link
                                v-if="showTimeOffLink"
                                href="/manage/time-off-requests"
                                :class="[navLinkClass(isTimeOffManage), 'block mx-1']"
                                @click="close"
                            >
                                Time off
                            </Link>
                            <a
                                v-for="site in otherSites"
                                :key="site.id"
                                :href="site.url"
                                class="block mx-1 nav-link"
                                @click="close"
                            >
                                Switch to {{ site.name }}
                            </a>
                            <Link href="/settings" class="block mx-1 nav-link" @click="close">
                                Settings
                            </Link>
                            <Link href="/change-password" class="block mx-1 nav-link" @click="close">
                                Change password
                            </Link>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                type="button"
                                class="block w-full text-left mx-1 nav-link"
                                @click="close"
                            >
                                Log out
                            </Link>
                        </template>
                        <template v-else>
                            <Link href="/login" class="block mx-1 nav-link" @click="close">
                                Log in
                            </Link>
                            <Link
                                v-if="config.registerEnabled"
                                href="/register"
                                class="block mx-1 nav-link"
                                @click="close"
                            >
                                Register
                            </Link>
                        </template>
                    </DisclosurePanel>
                </Disclosure>
            </div>
        </header>

        <Toast
            v-if="flash.message"
            :message="flash.message"
            type="success"
        />
        <Toast
            v-if="flash.error"
            :message="flash.error"
            type="error"
        />

        <main class="flex-1 py-5 sm:py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <footer class="border-t border-white/5 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                <p class="text-xs text-slate-600">
                    {{ config.appName }}
                </p>
                <div class="flex items-center gap-3">
                    <a
                        href="https://github.com/andy3471/lunchrota"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-slate-600 hover:text-slate-400 transition-colors"
                        aria-label="GitHub"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                    <a
                        href="https://ko-fi.com/andy3471#payment-widget"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-slate-600 hover:text-slate-400 transition-colors"
                        aria-label="Ko-fi"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M23.881 8.948c-.773-4.085-4.859-4.593-4.859-4.593H.723c-.604 0-.679.798-.679.798s-.082 7.324-.022 11.822c.164 2.424 2.586 2.672 2.586 2.672s8.267-.023 11.966-.049c2.438-.426 2.683-2.566 2.658-3.734 4.352.24 7.422-2.831 6.649-6.916zm-11.062 3.511c-1.246 1.453-4.011 3.976-4.011 3.976s-.121.119-.31.023c-.076-.057-.108-.09-.108-.09-.443-.441-3.368-3.049-4.034-3.954-.709-.965-1.041-2.7-.091-3.71.951-1.01 3.005-1.086 4.363.407 0 0 1.565-1.782 3.468-.963 1.904.82 1.832 3.011.723 4.311zm6.173.478c-.928.116-1.682.028-1.682.028V7.284h1.77s1.971.551 1.971 2.638c0 1.913-.985 2.667-2.059 3.015z" />
                        </svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>
