import { ref, onBeforeUnmount, type ComputedRef, type Ref } from 'vue';
import type { TimeBlockData } from '@/Types/generated';
import {
    timeToMinutes,
    snapMinutes,
    minutesFromPointer,
    MIN_SNAPPED_BLOCK_MINUTES,
    SNAP_MINUTES,
} from '@/Composables/useScheduleTime';

export type DragMode = 'move' | 'resize-start' | 'resize-end';

export interface DragState {
    mode: DragMode;
    block: TimeBlockData;
    trackElement: HTMLElement;
    pointerId: number;
    startPointerMinutes: number;
    originalStart: number;
    originalEnd: number;
    previewStart: number;
    previewEnd: number;
}

interface UseTimelineDragOptions {
    dayStart: ComputedRef<number>;
    dayEnd: ComputedRef<number>;
    daySpan: ComputedRef<number>;
    submitting: Ref<boolean>;
    isEditableBlock: (block: TimeBlockData) => boolean;
    onDragComplete: (block: TimeBlockData, previewStart: number, previewEnd: number) => void;
}

export function useTimelineDrag (options: UseTimelineDragOptions): {
    dragState: Ref<DragState | null>;
    onPointerDown: (event: PointerEvent, block: TimeBlockData, mode: DragMode) => void;
    cancelDrag: () => void;
    blockTimes: (block: TimeBlockData) => { start: number; end: number };
} {
    const dragState = ref<DragState | null>(null);

    const blockTimes = (block: TimeBlockData): { start: number; end: number } => {
        if (dragState.value?.block.id === block.id) {
            return {
                start: dragState.value.previewStart,
                end: dragState.value.previewEnd,
            };
        }

        return {
            start: timeToMinutes(block.startTime),
            end: timeToMinutes(block.endTime),
        };
    };

    const cancelDrag = (): void => {
        dragState.value = null;
        window.removeEventListener('pointermove', onPointerMove);
        window.removeEventListener('pointerup', onPointerUp);
    };

    const onPointerMove = (event: PointerEvent): void => {
        if (!dragState.value || event.pointerId !== dragState.value.pointerId) {
            return;
        }

        const current = minutesFromPointer(
            event.clientX,
            dragState.value.trackElement.getBoundingClientRect(),
            options.dayStart.value,
            options.daySpan.value,
        );
        const delta = current - dragState.value.startPointerMinutes;
        const duration = dragState.value.originalEnd - dragState.value.originalStart;

        if (dragState.value.mode === 'move') {
            let start = snapMinutes(dragState.value.originalStart + delta, SNAP_MINUTES);
            let end = start + duration;
            if (end > options.dayEnd.value) {
                end = options.dayEnd.value;
                start = end - duration;
            }
            if (start < options.dayStart.value) {
                start = options.dayStart.value;
                end = start + duration;
            }
            dragState.value.previewStart = start;
            dragState.value.previewEnd = end;
        }

        if (dragState.value.mode === 'resize-start') {
            const end = dragState.value.originalEnd;
            const start = snapMinutes(Math.min(current, end - MIN_SNAPPED_BLOCK_MINUTES), SNAP_MINUTES);
            dragState.value.previewStart = Math.max(options.dayStart.value, start);
            dragState.value.previewEnd = end;
        }

        if (dragState.value.mode === 'resize-end') {
            const start = dragState.value.originalStart;
            const end = snapMinutes(Math.max(current, start + MIN_SNAPPED_BLOCK_MINUTES), SNAP_MINUTES);
            dragState.value.previewEnd = Math.min(options.dayEnd.value, end);
            dragState.value.previewStart = start;
        }
    };

    const onPointerUp = (event: PointerEvent): void => {
        if (!dragState.value || event.pointerId !== dragState.value.pointerId) {
            return;
        }

        const { block, previewStart, previewEnd, originalStart, originalEnd } = dragState.value;

        dragState.value = null;
        window.removeEventListener('pointermove', onPointerMove);
        window.removeEventListener('pointerup', onPointerUp);

        if (previewStart === originalStart && previewEnd === originalEnd) {
            return;
        }

        options.onDragComplete(block, previewStart, previewEnd);
    };

    const onPointerDown = (
        event: PointerEvent,
        block: TimeBlockData,
        mode: DragMode,
    ): void => {
        if (!options.isEditableBlock(block) || options.submitting.value) {
            return;
        }

        const track = (event.currentTarget as HTMLElement).closest('[data-schedule-track]') as HTMLElement | null;
        if (!track) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const start = timeToMinutes(block.startTime);
        const end = timeToMinutes(block.endTime);

        dragState.value = {
            mode,
            block,
            trackElement: track,
            pointerId: event.pointerId,
            startPointerMinutes: minutesFromPointer(
                event.clientX,
                track.getBoundingClientRect(),
                options.dayStart.value,
                options.daySpan.value,
            ),
            originalStart: start,
            originalEnd: end,
            previewStart: start,
            previewEnd: end,
        };

        (event.target as HTMLElement).setPointerCapture?.(event.pointerId);
        window.addEventListener('pointermove', onPointerMove);
        window.addEventListener('pointerup', onPointerUp);
    };

    onBeforeUnmount(() => {
        window.removeEventListener('pointermove', onPointerMove);
        window.removeEventListener('pointerup', onPointerUp);
    });

    return {
        dragState,
        onPointerDown,
        cancelDrag,
        blockTimes,
    };
}
