<script setup lang="ts">
import type { TimeBlockData } from '@/Types/generated';
import type { DragMode } from '@/Composables/useTimelineDrag';
import { minutesToTime } from '@/Composables/useScheduleTime';

interface BlockStyle {
    left: string;
    width: string;
    backgroundColor?: string;
    class: string;
}

interface Props {
    block: TimeBlockData;
    blockStyle: BlockStyle;
    startMinutes: number;
    endMinutes: number;
    label: string;
    editable: boolean;
    showEditHint: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
    pointerDown: [event: PointerEvent, mode: DragMode];
    doubleClick: [];
    openEdit: [];
    removeAssignment: [];
    removeLunch: [];
}>();
</script>

<template>
    <div
        data-schedule-block
        class="absolute top-1 bottom-1 rounded-md flex items-center overflow-hidden group select-none touch-none"
        :class="blockStyle.class"
        :style="{ left: blockStyle.left, width: blockStyle.width, backgroundColor: blockStyle.backgroundColor ?? undefined }"
        :title="`${label}: ${minutesToTime(startMinutes)} – ${minutesToTime(endMinutes)}${editable && showEditHint ? ' · double-click to edit' : ''}`"
        @pointerdown="emit('pointerDown', $event, 'move')"
        @dblclick.stop="emit('doubleClick')"
    >
        <div
            v-if="editable"
            class="absolute left-0 top-0 bottom-0 w-1.5 cursor-ew-resize opacity-0 group-hover:opacity-100 bg-white/30"
            @pointerdown.stop="emit('pointerDown', $event, 'resize-start')"
        />
        <span class="text-[10px] text-white font-medium truncate px-2 flex-1 text-center pointer-events-none">
            {{ label }}
        </span>
        <span class="text-[9px] text-white/70 px-1 hidden sm:inline pointer-events-none">
            {{ minutesToTime(startMinutes) }}
        </span>
        <button
            v-if="editable"
            type="button"
            class="absolute top-0.5 right-5 hidden group-hover:flex w-4 h-4 items-center justify-center rounded bg-black/40 text-white/80 hover:text-white text-[10px]"
            title="Set exact times"
            @click.stop="emit('openEdit')"
        >
            ✎
        </button>
        <button
            v-if="editable && block.type === 'task_assignment' && block.assignmentId"
            type="button"
            class="absolute top-0.5 right-0.5 hidden group-hover:flex w-4 h-4 items-center justify-center rounded bg-black/40 text-white/80 hover:text-white text-xs"
            @click.stop="emit('removeAssignment')"
        >
            ×
        </button>
        <button
            v-if="editable && block.type === 'lunch_booking'"
            type="button"
            class="absolute top-0.5 right-0.5 hidden group-hover:flex w-4 h-4 items-center justify-center rounded bg-black/40 text-white/80 hover:text-white text-xs"
            title="Remove lunch"
            @click.stop="emit('removeLunch')"
        >
            ×
        </button>
        <div
            v-if="editable"
            class="absolute right-0 top-0 bottom-0 w-1.5 cursor-ew-resize opacity-0 group-hover:opacity-100 bg-white/30"
            @pointerdown.stop="emit('pointerDown', $event, 'resize-end')"
        />
    </div>
</template>
