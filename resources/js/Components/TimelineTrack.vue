<script setup lang="ts">
import type { CSSProperties } from 'vue';
import type { TimeBlockData, WorkingHoursData } from '@/Types/generated';
import type { DragMode } from '@/Composables/useTimelineDrag';
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

defineProps<Props>();

const emit = defineEmits<{
    trackClick: [event: MouseEvent];
    openAddDraft: [];
    openLunchDraft: [];
    blockPointerDown: [event: PointerEvent, block: TimeBlockData, mode: DragMode];
    blockDoubleClick: [block: TimeBlockData];
    openEdit: [block: TimeBlockData];
    removeAssignment: [assignmentId: string];
    removeLunch: [];
}>();
</script>

<template>
    <div
        class="flex items-stretch gap-3"
        :class="isMe ? 'rounded-lg bg-primary-500/5 ring-1 ring-primary-500/20 p-2 -mx-2' : ''"
    >
        <div
            class="w-20 shrink-0 text-sm truncate pt-3 flex flex-col gap-1"
            :class="isMe ? 'text-primary-300 font-medium' : 'text-slate-300'"
        >
            <span :title="userName">{{ isMe ? 'You' : userName }}</span>
            <div v-if="canShowRowActions" class="flex flex-col gap-0.5">
                <button
                    type="button"
                    class="text-[10px] text-left text-primary-400 hover:text-primary-300 underline"
                    @click.stop="emit('openAddDraft')"
                >
                    + Add
                </button>
                <button
                    v-if="isMe && canAddLunch"
                    type="button"
                    class="text-[10px] text-left text-emerald-400 hover:text-emerald-300 underline"
                    @click.stop="emit('openLunchDraft')"
                >
                    + Lunch
                </button>
            </div>
        </div>

        <div
            data-schedule-track
            class="flex-1 relative h-14 rounded-lg border transition-colors"
            :class="[
                canEditTrack
                    ? 'bg-slate-900/60 border-slate-600/50 hover:border-primary-500/40 cursor-crosshair'
                    : 'bg-slate-900/40 border-slate-700/30',
            ]"
            @click="emit('trackClick', $event)"
        >
            <div
                class="absolute top-0 bottom-0 bg-slate-700/25 pointer-events-none border-x border-slate-600/25 rounded-sm"
                :style="workingHoursStyle"
                :title="`Working hours ${workingHours.start} – ${workingHours.end}`"
            />

            <div
                v-for="(line, index) in gridLines"
                :key="index"
                class="absolute top-0 bottom-0 w-px bg-slate-700/40 pointer-events-none"
                :style="{ left: `${line}%` }"
            />

            <button
                v-if="isMe && lunchPlaceholderStyle"
                type="button"
                class="absolute top-1 bottom-1 rounded-md border-2 border-dashed border-emerald-500/50 bg-emerald-500/10 text-[10px] text-emerald-300/90 font-medium hover:bg-emerald-500/20 hover:border-emerald-400/70 transition-colors z-0"
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
                :start-minutes="blockTimes(block).start"
                :end-minutes="blockTimes(block).end"
                :label="blockLabel(block)"
                :editable="isEditableBlock(block)"
                :show-edit-hint="block.type === 'task_assignment'"
                @pointer-down="(event, mode) => emit('blockPointerDown', event, block, mode)"
                @double-click="emit('blockDoubleClick', block)"
                @open-edit="emit('openEdit', block)"
                @remove-assignment="block.assignmentId && emit('removeAssignment', block.assignmentId)"
                @remove-lunch="emit('removeLunch')"
            />
        </div>
    </div>
</template>
