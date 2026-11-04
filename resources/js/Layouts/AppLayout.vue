<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems, TransitionRoot } from '@headlessui/vue';
import BrandLogo from '@/Components/BrandLogo.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import Toast from '@/Components/Toast.vue';
import { home, login, register, logout } from '@/routes/index';
import { index as manageUsersIndex } from '@/routes/manage/users';
import { index as timeOffRequestsIndex } from '@/routes/manage/time-off-requests';
import { show as settingsShow } from '@/routes/settings';
import { change as changePasswordShow } from '@/routes/password';

const page = usePage();

const auth = computed(() => page.props.auth);
const config = computed(() => page.props.config);
const flash = computed(() => page.props.flash);
const currentSite = computed(() => page.props.currentSite);
const userSites = computed(() => page.props.userSites);
const otherSites = computed(() => userSites.value.filter((site) => !site.isCurrent));

const currentPath = computed(() => page.url.split('?')[0] ?? '');
const isDashboard = computed(() => currentPath.value === '/');
const isManage = computed(() => currentPath.value.startsWith('/manage'));
const isTimeOffManage = computed(() => currentPath.value.startsWith('/manage/time-off-requests'));
const showManageLink = computed(() => auth.value.user?.is_admin ?? false);
const showTimeOffLink = computed(() => {
    const user = auth.value.user;

    return Boolean(user?.can_manage_time_off && !user.is_admin);
});

const userInitial = computed(() => auth.value.user?.name.charAt(0).toUpperCase() ?? '?');

const navLinkClass = (active: boolean): string => (active ? 'nav-chip nav-chip-active' : 'nav-chip');
</script>

<template>
    <div class="relative flex min-h-screen flex-col text-gray-100">
        <a href="#main" class="skip-link">Skip to content</a>

        <header class="sticky top-0 z-40 border-b border-white/10 bg-dark-950/90">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <Disclosure v-slot="{ open, close }">
                    <div class="flex h-12 items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-5">
                            <Link :href="home.url()" class="group flex min-w-0 items-center gap-3">
                                <BrandLogo
                                    as="span"
                                    size="sm"
                                />
                                <div
                                    v-if="currentSite"
                                    class="hidden min-w-0 leading-tight sm:block"
                                >
                                    <p class="truncate text-sm font-medium text-slate-200 group-hover:text-white">
                                        {{ currentSite.name }}
                                    </p>
                                </div>
                            </Link>

                            <nav
                                v-if="auth.user"
                                class="hidden items-center gap-0.5 md:flex"
                                aria-label="Primary"
                            >
                                <Link
                                    :href="home.url()"
                                    :class="navLinkClass(isDashboard)"
                                >
                                    Schedule
                                </Link>
                                <Link
                                    v-if="showManageLink"
                                    :href="manageUsersIndex.url()"
                                    :class="navLinkClass(isManage)"
                                >
                                    Manage
                                </Link>
                                <Link
                                    v-if="showTimeOffLink"
                                    :href="timeOffRequestsIndex.url()"
                                    :class="navLinkClass(isTimeOffManage)"
                                >
                                    Time off
                                </Link>
                            </nav>
                        </div>

                        <div class="hidden shrink-0 items-center gap-2 md:flex">
                            <template v-if="auth.user">
                                <Menu as="div" class="relative">
                                    <MenuButton class="flex items-center gap-2 border border-white/10 bg-dark-950/50 py-1 pr-2.5 pl-1.5 transition-colors hover:bg-white/5">
                                        <span class="flex size-7 items-center justify-center bg-primary-600/90 text-xs font-semibold text-white">
                                            {{ userInitial }}
                                        </span>
                                        <span class="max-w-[8rem] truncate text-sm font-medium text-slate-200">
                                            {{ auth.user.name }}
                                        </span>
                                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <MenuItems class="absolute right-0 mt-2 w-56 origin-top-right border border-white/10 bg-dark-950 py-1 shadow-lg shadow-black/30 focus:outline-hidden">
                                            <div
                                                v-if="currentSite"
                                                class="border-b border-white/5 px-4 py-2.5 text-xs text-slate-500"
                                            >
                                                Signed in to <span class="text-slate-300">{{ currentSite.name }}</span>
                                            </div>
                                            <template v-if="otherSites.length > 0">
                                                <div class="px-4 pt-2 pb-1 text-[10px] font-semibold tracking-wide text-slate-500 uppercase">
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
                                                    :href="settingsShow.url()"
                                                    :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block px-4 py-2 text-sm']"
                                                >
                                                    Settings
                                                </Link>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    :href="changePasswordShow.url()"
                                                    :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block px-4 py-2 text-sm']"
                                                >
                                                    Change password
                                                </Link>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    :href="logout.url()"
                                                    method="post"
                                                    as="button"
                                                    type="button"
                                                    :class="[active ? 'bg-white/10 text-white' : 'text-slate-300', 'block w-full px-4 py-2 text-left text-sm']"
                                                >
                                                    Log out
                                                </Link>
                                            </MenuItem>
                                        </MenuItems>
                                    </TransitionRoot>
                                </Menu>
                            </template>
                            <template v-else>
                                <Link
                                    :href="login.url()"
                                    class="nav-chip"
                                >
                                    Log in
                                </Link>
                                <Link
                                    v-if="config.registerEnabled"
                                    :href="register.url()"
                                    class="btn btn-primary"
                                >
                                    Register
                                </Link>
                            </template>
                        </div>

                        <DisclosureButton class="rounded-md p-2 text-slate-400 transition-colors hover:bg-white/5 hover:text-white md:hidden">
                            <span class="sr-only">Toggle menu</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <DisclosurePanel class="space-y-0.5 border-t border-white/10 py-2 md:hidden">
                        <template v-if="auth.user">
                            <Link
                                :href="home.url()"
                                :class="[navLinkClass(isDashboard), 'mx-1 block']"
                                @click="close"
                            >
                                Schedule
                            </Link>
                            <Link
                                v-if="showManageLink"
                                :href="manageUsersIndex.url()"
                                :class="[navLinkClass(isManage), 'mx-1 block']"
                                @click="close"
                            >
                                Manage
                            </Link>
                            <Link
                                v-if="showTimeOffLink"
                                :href="timeOffRequestsIndex.url()"
                                :class="[navLinkClass(isTimeOffManage), 'mx-1 block']"
                                @click="close"
                            >
                                Time off
                            </Link>
                            <a
                                v-for="site in otherSites"
                                :key="site.id"
                                :href="site.url"
                                class="nav-chip mx-1 block"
                                @click="close"
                            >
                                Switch to {{ site.name }}
                            </a>
                            <Link
                                :href="settingsShow.url()"
                                class="nav-chip mx-1 block"
                                @click="close"
                            >
                                Settings
                            </Link>
                            <Link
                                :href="changePasswordShow.url()"
                                class="nav-chip mx-1 block"
                                @click="close"
                            >
                                Change password
                            </Link>
                            <Link
                                :href="logout.url()"
                                method="post"
                                as="button"
                                type="button"
                                class="nav-chip mx-1 block w-full text-left"
                                @click="close"
                            >
                                Log out
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="login.url()"
                                class="nav-chip mx-1 block"
                                @click="close"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="config.registerEnabled"
                                :href="register.url()"
                                class="nav-chip mx-1 block"
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

        <main
            id="main"
            class="flex-1 py-5 sm:py-6"
            tabindex="-1"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <SiteFooter />
    </div>
</template>
