<script setup lang="ts">
withDefaults(defineProps<{
    variant?: 'card' | 'hero';
}>(), {
    variant: 'hero',
});

const hours = ['09', '10', '11', '12', '13', '14', '15', '16', '17'] as const;

const rows = [
    {
        name: 'You',
        me: true,
        blocks: [
            { left: 8, width: 26, tone: 'task', label: 'Standup', delay: '80ms' },
            { left: 40, width: 12, tone: 'lunch', label: 'Lunch', delay: '160ms' },
            { left: 56, width: 28, tone: 'task', label: 'Deep work', delay: '240ms' },
        ],
    },
    {
        name: 'Sam',
        me: false,
        blocks: [
            { left: 10, width: 36, tone: 'task', label: 'Support desk', delay: '120ms' },
            { left: 52, width: 18, tone: 'off', label: 'PTO', delay: '200ms' },
        ],
    },
    {
        name: 'Alex',
        me: false,
        blocks: [
            { left: 8, width: 20, tone: 'task', label: 'Design', delay: '100ms' },
            { left: 36, width: 12, tone: 'lunch', label: 'Lunch', delay: '180ms' },
            { left: 54, width: 32, tone: 'task', label: 'Review', delay: '260ms' },
        ],
    },
    {
        name: 'Jordan',
        me: false,
        blocks: [
            { left: 14, width: 24, tone: 'task', label: 'On call', delay: '140ms' },
            { left: 44, width: 14, tone: 'lunch', label: 'Lunch', delay: '220ms' },
            { left: 64, width: 20, tone: 'task', label: 'Handover', delay: '300ms' },
        ],
    },
] as const;

const toneClass = {
    task: 'bg-primary-600/85 border-primary-400/35',
    lunch: 'bg-sky-600/80 border-sky-300/30',
    off: 'bg-amber-600/75 border-amber-300/30',
} as const;
</script>

<template>
    <div
        class="schedule-preview"
        :class="variant === 'hero' ? 'schedule-preview-hero' : 'schedule-preview-card'"
        aria-hidden="true"
    >
        <div class="flex items-end justify-between gap-4 border-b border-white/10 px-4 py-3 sm:px-6 lg:px-8">
            <p class="text-xs font-medium tracking-wide text-slate-400 uppercase">
                Tuesday · team day
            </p>
            <p class="hidden text-xs text-slate-500 sm:block">
                Tasks · lunch · time off on one track
            </p>
        </div>

        <div class="relative px-4 pt-3 pb-5 sm:px-6 lg:px-8">
            <div class="mb-2 flex justify-between pl-14 font-mono text-[10px] tracking-wider text-slate-500 sm:pl-16">
                <span
                    v-for="hour in hours"
                    :key="hour"
                >
                    {{ hour }}:00
                </span>
            </div>

            <div class="space-y-2.5">
                <div
                    v-for="row in rows"
                    :key="row.name"
                    class="flex items-center gap-3 sm:gap-4"
                >
                    <div
                        class="w-14 shrink-0 truncate text-xs sm:w-16"
                        :class="row.me ? 'font-semibold text-primary-300' : 'text-slate-400'"
                    >
                        {{ row.name }}
                    </div>
                    <div class="relative h-10 flex-1 overflow-hidden bg-dark-950/50 sm:h-11">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-[8%] w-[72%] border-x border-white/[0.04] bg-white/[0.015]"
                        />
                        <div
                            v-for="(block, index) in row.blocks"
                            :key="`${row.name}-${index}`"
                            class="schedule-block absolute top-1.5 bottom-1.5 flex items-center overflow-hidden border px-2 text-[10px] font-medium text-white/95 sm:text-[11px]"
                            :class="toneClass[block.tone]"
                            :style="{
                                left: `${block.left}%`,
                                width: `${block.width}%`,
                                animationDelay: block.delay,
                            }"
                        >
                            <span class="truncate">{{ block.label }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
