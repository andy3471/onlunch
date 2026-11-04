<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import BrandLogo from '@/Components/BrandLogo.vue';
import SchedulePreview from '@/Components/SchedulePreview.vue';
import BrochureLayout from '@/Layouts/BrochureLayout.vue';
import { create as register } from '@/routes/sites';
</script>

<template>
    <BrochureLayout>
        <section class="relative flex min-h-[calc(100vh-3rem)] flex-col">
            <div class="landing-intro mx-auto w-full max-w-7xl px-4 pt-10 pb-8 sm:px-6 sm:pt-14 lg:px-8 lg:pt-16">
                <p class="select-none">
                    <BrandLogo
                        as="span"
                        size="hero"
                    />
                </p>
                <h1 class="landing-line mt-5 max-w-2xl text-xl font-semibold tracking-tight text-white sm:text-2xl lg:text-[1.75rem] lg:leading-snug">
                    One day view for who’s working, at lunch, or out.
                </h1>
                <p class="landing-line mt-3 max-w-xl text-sm leading-relaxed text-slate-400 sm:text-base" style="animation-delay: 80ms">
                    Drop free-form blocks on a shared timeline — overlaps included, no rigid rota grid.
                </p>
                <div class="landing-line mt-7 flex flex-wrap items-center gap-3" style="animation-delay: 140ms">
                    <Link
                        :href="register.url()"
                        class="btn btn-primary px-4 py-2.5"
                    >
                        Create your team
                    </Link>
                    <a
                        href="#day-view"
                        class="btn btn-secondary px-4 py-2.5"
                    >
                        See the day view
                    </a>
                </div>
            </div>

            <div
                id="day-view"
                class="landing-plane mt-auto w-full border-t border-white/10"
            >
                <SchedulePreview variant="hero" />
            </div>
        </section>

        <section
            class="border-t border-white/10"
            aria-labelledby="truth-heading"
        >
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <h2
                    id="truth-heading"
                    class="max-w-2xl text-lg font-semibold tracking-tight text-white sm:text-xl"
                >
                    Reality isn’t neat. The rota shouldn’t pretend it is.
                </h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-400 sm:text-base">
                    Lunch stacks, half-days land mid-shift, and two people cover the same window. FlexRota keeps that readable on one track instead of forcing everything into tidy non-overlapping cells.
                </p>
            </div>
        </section>
    </BrochureLayout>
</template>
