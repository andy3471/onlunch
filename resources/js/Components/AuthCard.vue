<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <AuthLayout>
        <div class="max-w-md mx-auto">
            <div class="card">
                <div class="px-6 py-4 border-b border-slate-700/50">
                    <h2 class="text-xl font-semibold text-white text-center">
                        {{ title }}
                    </h2>
                </div>
                <div class="p-6">
                    <slot />
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
