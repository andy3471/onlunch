<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps<{
    title: string;
}>();
</script>

<template>
    <AuthLayout>
        <div class="mx-auto max-w-md">
            <div class="card">
                <div class="border-b border-white/10 px-6 py-4">
                    <h1 class="text-center text-lg font-semibold tracking-tight text-white">
                        {{ title }}
                    </h1>
                </div>
                <div class="p-6">
                    <slot />
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
