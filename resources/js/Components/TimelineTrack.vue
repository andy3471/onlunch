<script setup lang="ts">
import { computed, type CSSProperties } from 'vue';
import type { TimeBlockData, WorkingHoursData } from '@/Types/generated';
import type { DragMode } from '@/Composables/useTimelineDrag';
import { assignBlockLanes } from '@/Composables/useBlockLanes';
import TimelineBlock from '@/Components/TimelineBlock.vue';

interface ScheduleRowBlockStyle {
    left: string;
    width: string;
    backgroundColor?: string;
    class: string;
}

interface Props {
    userName: string;
    isMe: boolean;
    blocks: TimeBlockData[];
    workingHours: WorkingHoursData;
    workingHoursStyle: CSSProperties;
    gridLines: number[];
    lunchPlaceholderStyle: { left: string; width: string } | null;
    canEditTrack: boolean;
    canShowRowActions: boolean;
    canAddLunch: boolean;
    blockLabel: (block: TimeBlockData) => string;
    blockStyle: (block: TimeBlockData) => ScheduleRowBlockStyle;
    blockTimes: (block: TimeBlockData) => { start: number; end: number };
    isEditableBlock: (block: TimeBlockData) => boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    trackClick: [event: MouseEvent];
    openAddDraft: [];
    openLunchDraft: [];
    blockPointerDown: [event: PointerEvent, block: TimeBlockData, mode: DragMode];
    blockDoubleClick: [block: TimeBlockData];
    openEdit: [block: TimeBlockData];
    removeAssignment: [assignmentId: string];
    removeLunch: [lunchBookingId: string];
}>();

const laneLayouts = computed(() => assignBlockLanes(props.blocks, props.blockTimes));

const laneCount = computed(() => {
    let max = 1;

    for (const layout of laneLayouts.value.values()) {
        max = Math.max(max, layout.laneCount);
    }

    return max;
});

const trackHeightClass = computed(() => {
    if (laneCount.value >= 3) {
        return 'h-28';
    }

    if (laneCount.value === 2) {
        return 'h-20';
    }

    return 'h-16';
});

const laneStyle = (blockId: string): CSSProperties => {
    const layout = laneLayouts.value.get(blockId);

    if (!layout || layout.laneCount <= 1) {
        return {
            top: '0.25rem',
            bottom: '0.25rem',
        };
    }

    const gapPx = 2;
    const paddingPx = 4;
    const available = `calc(100% - ${paddingPx * 2}px - ${(layout.laneCount - 1) * gapPx}px)`;
    const height = `calc(${available} / ${layout.laneCount})`;
    const top = `calc(${paddingPx}px + (${height} + ${gapPx}px) * ${layout.lane})`;

    return {
        top,
        height,
        bottom: 'auto',
    };
};
</script>

<template>
    <div
        class="flex items-stretch gap-3"
        :class="isMe ? '-mx-1 bg-primary-500/[0.04] p-1.5 ring-1 ring-primary-500/20 sm:-mx-2 sm:p-2' : ''"
    >
        <div
            class="flex w-20 shrink-0 flex-col gap-1 truncate pt-3 text-sm"
            :class="isMe ? 'font-semibold text-primary-300' : 'text-slate-400'"
        >
            <span :title="userName">{{ isMe ? 'You' : userName }}</span>
            <div
                v-if="canShowRowActions"
                class="flex flex-col gap-0.5"
            >
                <button
                    type="button"
                    class="text-left text-[10px] text-primary-400 hover:text-primary-300"
                    @click.stop="emit('openAddDraft')"
                >
                    + Add
                </button>
                <button
                    v-if="isMe && canAddLunch"
                    type="button"
                    class="text-left text-[10px] text-sky-400 hover:text-sky-300"
                    @click.stop="emit('openLunchDraft')"
                >
                    + Lunch
                </button>
            </div>
        </div>

        <div
            data-schedule-track
            class="relative flex-1 border transition-colors"
            :class="[
                trackHeightClass,
                canEditTrack
                    ? 'cursor-crosshair border-white/10 bg-dark-950/60 hover:border-primary-500/35'
                    : 'border-white/5 bg-dark-950/40',
            ]"
            @click="emit('trackClick', $event)"
        >
            <div
                class="pointer-events-none absolute inset-y-0 border-x border-white/[0.06] bg-white/[0.03]"
                :style="workingHoursStyle"
                :title="`Working hours ${workingHours.start} – ${workingHours.end}`"
            />

            <div
                v-for="(line, index) in gridLines"
                :key="index"
                class="pointer-events-none absolute inset-y-0 w-px bg-white/[0.06]"
                :style="{ left: `${line}%` }"
            />

            <button
                v-if="isMe && lunchPlaceholderStyle"
                type="button"
                class="absolute top-1.5 bottom-1.5 z-0 border border-dashed border-sky-500/45 bg-sky-500/10 text-[10px] font-medium text-sky-300/90 transition-colors hover:border-sky-400/70 hover:bg-sky-500/20"
                :style="lunchPlaceholderStyle"
                title="Book lunch"
                @click.stop="emit('openLunchDraft')"
            >
                <span class="truncate px-1">+ Lunch</span>
            </button>

            <TimelineBlock
                v-for="block in blocks"
                :key="block.id"
                :block="block"
                :block-style="blockStyle(block)"
                :lane-style="laneStyle(block.id)"
                :start-minutes="blockTimes(block).start"
                :end-minutes="blockTimes(block).end"
                :label="blockLabel(block)"
                :editable="isEditableBlock(block)"
                :show-edit-hint="block.type === 'task_assignment'"
                @pointer-down="(event, mode) => emit('blockPointerDown', event, block, mode)"
                @double-click="emit('blockDoubleClick', block)"
                @open-edit="emit('openEdit', block)"
                @remove-assignment="block.assignmentId && emit('removeAssignment', block.assignmentId)"
                @remove-lunch="block.lunchBookingId && emit('removeLunch', block.lunchBookingId)"
            />
        </div>
    </div>
</template>
