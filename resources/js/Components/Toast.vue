<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { TransitionRoot } from '@headlessui/vue';
import type { ToastType } from '@/Types/toast';

const props = withDefaults(defineProps<{
    message: string;
    type?: ToastType;
    duration?: number;
}>(), {
    type: 'success',
    duration: 5000,
});

const show = ref(true);

const typeStyles: Record<ToastType, string> = {
    success: 'bg-emerald-600 border-emerald-500',
    error: 'bg-red-600 border-red-500',
    warning: 'bg-amber-600 border-amber-500',
    info: 'bg-blue-600 border-blue-500',
};

const icons: Record<ToastType, string> = {
    success: 'M5 13l4 4L19 7',
    error: 'M6 18L18 6M6 6l12 12',
    warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
};

onMounted(() => {
    if (props.duration > 0) {
        setTimeout(() => {
            show.value = false;
        }, props.duration);
    }
});

const close = (): void => {
    show.value = false;
};
</script>

<template>
    <TransitionRoot
        :show="show"
        enter="transition ease-out duration-300"
        enter-from="translate-y-2 opacity-0"
        enter-to="translate-y-0 opacity-100"
        leave="transition ease-in duration-200"
        leave-from="translate-y-0 opacity-100"
        leave-to="translate-y-2 opacity-0"
        as="div"
        class="fixed top-4 right-4 z-50 max-w-sm"
    >
        <div
            :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg border shadow-lg',
                typeStyles[type]
            ]"
        >
            <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icons[type]" />
            </svg>
            <p class="text-white text-sm font-medium flex-1">{{ message }}</p>
            <button
                @click="close"
                class="text-white/80 hover:text-white transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </TransitionRoot>
</template>
