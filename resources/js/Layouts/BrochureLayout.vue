<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import BrandLogo from '@/Components/BrandLogo.vue';
import SiteFooter from '@/Components/SiteFooter.vue';
import { home } from '@/routes/brochure';
import { create as register } from '@/routes/sites';
</script>

<template>
    <div class="relative flex min-h-screen flex-col text-gray-100">
        <a href="#main" class="skip-link">Skip to content</a>

        <header class="sticky top-0 z-40 border-b border-white/10 bg-dark-950/90">
            <div class="mx-auto flex h-12 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <BrandLogo
                    :href="home.url()"
                    size="sm"
                />
                <Link
                    :href="register.url()"
                    class="btn btn-primary"
                >
                    Get started
                </Link>
            </div>
        </header>

        <main id="main" class="flex-1" tabindex="-1">
            <slot />
        </main>

        <SiteFooter />
    </div>
</template>
