<script setup lang="ts">
defineProps<{
    name?: string;
    compact?: boolean;
}>();
</script>

<template>
    <div
        class="shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 shadow-sm shadow-primary-900/40"
        :class="compact ? 'size-8' : 'size-9'"
        aria-hidden="true"
    >
        <svg
            class="text-white"
            :class="compact ? 'size-4' : 'size-5'"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h6" />
        </svg>
    </div>
</template>
