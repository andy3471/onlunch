<script setup>
import { Link } from '@inertiajs/vue3';
import BrochureLayout from '@/Layouts/BrochureLayout.vue';
</script>

<template>
    <BrochureLayout>
        <div class="py-16 sm:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight">
                        Flexible working,
                        <span class="text-primary-400">sorted.</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-400 max-w-2xl mx-auto">
                        FlexRota helps teams manage roles, time off, and lunch breaks across the day —
                        free-form scheduling without the hassle.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-4">
                        <Link
                            href="/register"
                            class="px-6 py-3 rounded-xl text-base font-semibold bg-primary-600 text-white hover:bg-primary-500 transition-colors"
                        >
                            Get Started
                        </Link>
                        <a
                            href="#features"
                            class="px-6 py-3 rounded-xl text-base font-semibold text-slate-300 border border-slate-700 hover:border-slate-500 hover:text-white transition-colors"
                        >
                            Learn More
                        </a>
                    </div>
                </div>

                <div id="features" class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-slate-800/60 rounded-xl border border-slate-700/50 p-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Day Timeline</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            See who's working, on leave, or at lunch across the day. Overlapping entries are fine — your schedule, your way.
                        </p>
                    </div>

                    <div class="bg-slate-800/60 rounded-xl border border-slate-700/50 p-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Role Assignments</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            Assign roles for any time chunk — in office, WFH, or away. Admins set working hours and defaults for the team.
                        </p>
                    </div>

                    <div class="bg-slate-800/60 rounded-xl border border-slate-700/50 p-6">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Time Off Requests</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            Request any chunk of the day off. Auto-approve or route through admin — configurable per team.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </BrochureLayout>
</template>
