<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import BrandLogo from '@/Components/BrandLogo.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import Toast from '@/Components/Toast.vue';
import { home } from '@/routes/index';

const page = usePage();

const flash = computed(() => page.props.flash);
const currentSite = computed(() => page.props.currentSite);
</script>

<template>
    <div class="relative flex min-h-screen flex-col text-gray-100">
        <a href="#main" class="skip-link">Skip to content</a>

        <header class="sticky top-0 z-40 border-b border-white/10 bg-dark-950/90">
            <div class="mx-auto flex h-12 max-w-7xl items-center justify-center px-4 sm:px-6 lg:px-8">
                <Link
                    :href="home.url()"
                    class="group flex min-w-0 items-center gap-3"
                >
                    <BrandLogo
                        as="span"
                        size="sm"
                    />
                    <p
                        v-if="currentSite"
                        class="truncate text-sm font-medium text-slate-300 group-hover:text-white"
                    >
                        {{ currentSite.name }}
                    </p>
                </Link>
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
            class="flex-1 py-8 sm:py-12"
            tabindex="-1"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <SiteFooter />
    </div>
</template>
