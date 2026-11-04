<script setup lang="ts">
/**
 * @deprecated Prefer BrandLogo. Kept for any lingering imports.
 */
import BrandLogo from '@/Components/BrandLogo.vue';

withDefaults(defineProps<{
    name?: string;
    compact?: boolean;
}>(), {
    name: 'FlexRota',
    compact: false,
});
</script>

<template>
    <BrandLogo
        as="span"
        :size="compact ? 'sm' : 'md'"
        :label="name"
    />
</template>
