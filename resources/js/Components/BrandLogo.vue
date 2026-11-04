<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

withDefaults(defineProps<{
    href?: string;
    size?: 'sm' | 'md' | 'lg' | 'hero';
    as?: 'link' | 'span';
    label?: string;
}>(), {
    href: '/',
    size: 'md',
    as: 'link',
    label: 'FlexRota',
});

const sizeClass = {
    sm: 'text-base',
    md: 'text-lg',
    lg: 'text-2xl',
    hero: 'text-5xl sm:text-6xl lg:text-7xl',
} as const;
</script>

<template>
    <Link
        v-if="as === 'link'"
        :href="href"
        class="brand-logo marker-wiggle"
        :class="sizeClass[size]"
    >
        {{ label }}
    </Link>
    <span
        v-else
        class="brand-logo marker-wiggle"
        :class="sizeClass[size]"
    >
        {{ label }}
    </span>
</template>
