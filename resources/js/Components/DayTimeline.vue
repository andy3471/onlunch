<script setup lang="ts">
import { ref, computed, onBeforeUnmount } from 'vue';
import { router } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import LoadingSpinner from './LoadingSpinner.vue';
import Toast from './Toast.vue';
import type { ScheduleUserData, TaskOptionData, TimeBlockData, WorkingHoursData } from '@/Types/generated';
import {
    timeToMinutes,
    minutesToTime,
    snapMinutes,
    minutesFromPointer,
    percentFromMinutes,
    parseLocalDate,
    toLocalDateString,
    expandTimelineBounds,
    DEFAULT_BLOCK_MINUTES,
    MIN_SNAPPED_BLOCK_MINUTES,
    MIN_EXACT_BLOCK_MINUTES,
    SNAP_MINUTES,
} from '@/Composables/useScheduleTime';

interface Props {
    timeBlocks: TimeBlockData[];
    workingHours: WorkingHoursData;
    selectedDate: string;
    tasks: TaskOptionData[];
    scheduleUsers: ScheduleUserData[];
    currentUserId: string | null;
    loggedIn: boolean;
    loading: boolean;
    canEditLunch: boolean;
    isAdmin: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    dateChange: [date: string];
}>();

const toast = ref<{ type: string; message: string } | null>(null);
const reloadKeys = ['timeBlocks', 'myTaskAssignments', 'myLunchBooking'];

type DragMode = 'move' | 'resize-start' | 'resize-end';

interface DragState {
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

interface AddDraft {
    mode: 'task' | 'time_off' | 'lunch';
    userId: string;
    userName: string;
    startTime: string;
    endTime: string;
    taskName: string;
    notes: string;
}

interface EditDraft {
    block: TimeBlockData;
    startTime: string;
    endTime: string;
    taskName: string;
}

const dragState = ref<DragState | null>(null);
const addDraft = ref<AddDraft | null>(null);
const editDraft = ref<EditDraft | null>(null);
const submitting = ref(false);

const typeStyles: Record<string, { bg: string; border: string }> = {
    task_assignment: { bg: 'bg-indigo-500/80', border: 'border-indigo-300' },
    time_off_request: { bg: 'bg-amber-500/80', border: 'border-amber-300' },
    lunch_booking: { bg: 'bg-emerald-500/80', border: 'border-emerald-300' },
};

const workingDayStart = computed(() => timeToMinutes(props.workingHours.start));
const workingDayEnd = computed(() => timeToMinutes(props.workingHours.end));

const timelineBounds = computed(() => expandTimelineBounds(
    workingDayStart.value,
    workingDayEnd.value,
    props.timeBlocks,
));

const dayStart = computed(() => timelineBounds.value.start);
const dayEnd = computed(() => timelineBounds.value.end);
const daySpan = computed(() => Math.max(dayEnd.value - dayStart.value, 1));

const workingHoursStyle = computed(() => {
    const left = percentFromMinutes(workingDayStart.value, dayStart.value, daySpan.value);
    const right = percentFromMinutes(workingDayEnd.value, dayStart.value, daySpan.value);

    return {
        left: `${Math.max(0, left)}%`,
        width: `${Math.max(0, right - left)}%`,
    };
});

const canEdit = computed(() => props.loggedIn && props.currentUserId !== null);

const isToday = computed(() => toLocalDateString(new Date()) === props.selectedDate);

const hasMyLunchToday = computed(() => props.timeBlocks.some(
    (block) => block.userId === props.currentUserId && block.type === 'lunch_booking',
));

const canAddLunch = computed(() => props.canEditLunch && !hasMyLunchToday.value);

const defaultLunchRange = (): { start: number; end: number } => {
    const start = timeToMinutes('12:00');
    const end = Math.min(dayEnd.value, start + 60);

    return clampSnappedRange(start, end);
};

const lunchPlaceholderStyle = computed(() => {
    if (!canAddLunch.value) {
        return null;
    }

    const { start, end } = defaultLunchRange();
    const left = percentFromMinutes(start, dayStart.value, daySpan.value);
    const width = percentFromMinutes(end, dayStart.value, daySpan.value) - left;

    return {
        left: `${Math.max(0, left)}%`,
        width: `${Math.max(4, width)}%`,
    };
});

const formattedSelectedDate = computed(() => {
    const date = parseLocalDate(props.selectedDate);
    return new Intl.DateTimeFormat(undefined, {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date);
});

const addDraftBorderClass = computed((): string => {
    if (!addDraft.value) {
        return 'border-primary-500/40';
    }

    if (addDraft.value.mode === 'time_off') {
        return 'border-amber-500/40';
    }

    if (addDraft.value.mode === 'lunch') {
        return 'border-emerald-500/40';
    }

    return 'border-primary-500/40';
});

const navigateDay = (delta: number): void => {
    if (props.loading) {
        return;
    }

    const date = parseLocalDate(props.selectedDate);
    date.setDate(date.getDate() + delta);
    emit('dateChange', toLocalDateString(date));
};

const goToToday = (): void => {
    if (props.loading) {
        return;
    }

    emit('dateChange', toLocalDateString(new Date()));
};

const onDatePickerChange = (event: Event): void => {
    const value = (event.target as HTMLInputElement).value;

    if (value && value !== props.selectedDate) {
        emit('dateChange', value);
    }
};

const canEditRow = (userId: string): boolean => {
    return props.isAdmin || userId === props.currentUserId;
};

const scheduleRows = computed(() => {
    const grouped = new Map<string, { userName: string; blocks: TimeBlockData[]; isMe: boolean }>();

    for (const member of props.scheduleUsers) {
        grouped.set(member.userId, {
            userName: member.userId === props.currentUserId ? 'You' : member.userName,
            blocks: [],
            isMe: member.userId === props.currentUserId,
        });
    }

    for (const block of props.timeBlocks) {
        if (!grouped.has(block.userId)) {
            grouped.set(block.userId, {
                userName: block.userName,
                blocks: [],
                isMe: block.userId === props.currentUserId,
            });
        }
        grouped.get(block.userId)!.blocks.push(block);
    }

    if (props.currentUserId && !grouped.has(props.currentUserId)) {
        grouped.set(props.currentUserId, {
            userName: 'You',
            blocks: [],
            isMe: true,
        });
    }

    return Array.from(grouped.entries())
        .map(([userId, data]) => ({ userId, ...data }))
        .sort((a, b) => {
            if (a.isMe) return -1;
            if (b.isMe) return 1;
            return a.userName.localeCompare(b.userName);
        });
});

const hourMarkers = computed(() => {
    const markers: string[] = [];
    const startHour = Math.floor(dayStart.value / 60);
    const endHour = Math.ceil(dayEnd.value / 60);

    for (let hour = startHour; hour <= endHour; hour++) {
        markers.push(`${String(hour).padStart(2, '0')}:00`);
    }

    return markers;
});

const gridLines = computed(() => {
    const lines: number[] = [];
    const start = Math.ceil(dayStart.value / SNAP_MINUTES) * SNAP_MINUTES;

    for (let m = start; m <= dayEnd.value; m += SNAP_MINUTES) {
        lines.push(percentFromMinutes(m, dayStart.value, daySpan.value));
    }

    return lines;
});

const taskSuggestions = computed((): string[] => {
    const names = props.tasks.map((task) => task.name);
    return ['Away', ...names];
});

const defaultTaskName = computed((): string => props.tasks[0]?.name ?? '');

const isEditableBlock = (block: TimeBlockData): boolean => {
    if (block.type === 'task_assignment') {
        return !!block.assignmentId && canEditRow(block.userId);
    }

    if (block.userId !== props.currentUserId) {
        return false;
    }

    if (block.type === 'lunch_booking') {
        return props.canEditLunch && !!block.lunchBookingId;
    }

    return false;
};

const blockLabel = (block: TimeBlockData): string => {
    if (block.type === 'task_assignment') {
        return block.taskName ?? 'Away';
    }
    if (block.type === 'time_off_request') {
        return block.status === 'pending' ? 'Time off (pending)' : 'Time off';
    }
    return 'Lunch';
};

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

const blockStyle = (block: TimeBlockData) => {
    const { start, end } = blockTimes(block);
    const left = percentFromMinutes(start, dayStart.value, daySpan.value);
    const width = percentFromMinutes(end, dayStart.value, daySpan.value) - left;
    const style = typeStyles[block.type] ?? typeStyles.task_assignment;
    const customColor = block.type === 'task_assignment' && block.taskColor;
    const editable = isEditableBlock(block);

    return {
        left: `${Math.max(0, left)}%`,
        width: `${Math.max(1.5, width)}%`,
        backgroundColor: customColor ?? undefined,
        class: [
            customColor ? 'border border-white/30' : `${style.bg} ${style.border} border`,
            editable ? 'cursor-grab active:cursor-grabbing ring-1 ring-white/10 hover:ring-white/30' : 'cursor-default',
            dragState.value?.block.id === block.id ? 'z-20 opacity-90 shadow-lg' : 'z-10',
        ].join(' '),
    };
};

const showToast = (type: string, message: string) => {
    toast.value = { type, message };
    setTimeout(() => { toast.value = null; }, 4000);
};

const clampSnappedRange = (start: number, end: number): { start: number; end: number } => {
    let s = snapMinutes(Math.max(dayStart.value, start), SNAP_MINUTES);
    let e = snapMinutes(Math.min(dayEnd.value, end), SNAP_MINUTES);

    if (e - s < MIN_SNAPPED_BLOCK_MINUTES) {
        e = Math.min(dayEnd.value, s + MIN_SNAPPED_BLOCK_MINUTES);
    }

    if (e <= s) {
        s = Math.max(dayStart.value, e - MIN_SNAPPED_BLOCK_MINUTES);
    }

    return { start: s, end: e };
};

const clampExactRange = (start: number, end: number): { start: number; end: number } => {
    let s = Math.round(Math.max(dayStart.value, Math.min(start, dayEnd.value)));
    let e = Math.round(Math.max(dayStart.value, Math.min(end, dayEnd.value)));

    if (e <= s) {
        e = Math.min(dayEnd.value, s + MIN_EXACT_BLOCK_MINUTES);
    }

    if (e - s < MIN_EXACT_BLOCK_MINUTES) {
        e = Math.min(dayEnd.value, s + MIN_EXACT_BLOCK_MINUTES);
    }

    return { start: s, end: e };
};

const OVERLAP_MESSAGE = 'This time overlaps with something else on the schedule.';

const countsForScheduleOverlap = (block: TimeBlockData): boolean => {
    if (block.type === 'time_off_request' && block.status === 'rejected') {
        return false;
    }

    return true;
};

const hasScheduleOverlap = (
    userId: string,
    start: number,
    end: number,
    ignoreBlockId?: string,
): boolean => props.timeBlocks.some((block) => {
    if (block.userId !== userId || block.id === ignoreBlockId || !countsForScheduleOverlap(block)) {
        return false;
    }

    const blockStart = timeToMinutes(block.startTime);
    const blockEnd = timeToMinutes(block.endTime);

    return start < blockEnd && blockStart < end;
});

const saveLunchBooking = (
    lunchBookingId: string,
    start: number,
    end: number,
    exact = false,
): void => {
    const range = exact ? clampExactRange(start, end) : clampSnappedRange(start, end);

    if (props.currentUserId !== null && hasScheduleOverlap(props.currentUserId, range.start, range.end, props.timeBlocks.find((block) => block.lunchBookingId === lunchBookingId)?.id)) {
        showToast('error', OVERLAP_MESSAGE);
        return;
    }

    submitting.value = true;

    router.put(`/lunch-booking/${lunchBookingId}`, {
        start_time: minutesToTime(range.start),
        end_time: minutesToTime(range.end),
    }, {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Lunch updated.'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            showToast('error', Array.isArray(firstError) ? firstError[0] : 'Could not update lunch.');
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
};

const saveAssignment = (
    assignmentId: string,
    start: number,
    end: number,
    taskName: string,
    exact = false,
    onFinish?: () => void,
): void => {
    const range = exact ? clampExactRange(start, end) : clampSnappedRange(start, end);
    const block = props.timeBlocks.find((entry) => entry.assignmentId === assignmentId);

    if (block !== undefined && hasScheduleOverlap(block.userId, range.start, range.end, block.id)) {
        showToast('error', OVERLAP_MESSAGE);
        onFinish?.();
        return;
    }

    submitting.value = true;

    router.put(`/task-assignments/${assignmentId}`, {
        start_time: minutesToTime(range.start),
        end_time: minutesToTime(range.end),
        task_name: taskName,
    }, {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Schedule updated.'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            showToast('error', Array.isArray(firstError) ? firstError[0] : 'Could not update.');
        },
        onFinish: () => {
            submitting.value = false;
            onFinish?.();
        },
    });
};

const createLunchBooking = (start: number, end: number, exact = false): void => {
    const range = exact ? clampExactRange(start, end) : clampSnappedRange(start, end);

    if (props.currentUserId !== null && hasScheduleOverlap(props.currentUserId, range.start, range.end)) {
        showToast('error', OVERLAP_MESSAGE);
        return;
    }

    submitting.value = true;
    addDraft.value = null;

    router.post('/lunch-booking', {
        start_time: minutesToTime(range.start),
        end_time: minutesToTime(range.end),
    }, {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Lunch booked.'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            showToast('error', Array.isArray(firstError) ? firstError[0] : 'Could not book lunch.');
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
};

const createTimeOffRequest = (start: number, end: number, notes: string, exact = false): void => {
    const range = exact ? clampExactRange(start, end) : clampSnappedRange(start, end);

    if (props.currentUserId !== null && hasScheduleOverlap(props.currentUserId, range.start, range.end)) {
        showToast('error', OVERLAP_MESSAGE);
        return;
    }

    submitting.value = true;
    addDraft.value = null;

    router.post('/time-off-requests', {
        date: props.selectedDate,
        start_time: minutesToTime(range.start),
        end_time: minutesToTime(range.end),
        notes: notes.trim() || null,
    }, {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Time off request submitted.'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            showToast('error', Array.isArray(firstError) ? firstError[0] : 'Could not request time off.');
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
};

const createAssignment = (start: number, end: number, taskName: string, targetUserId: string, exact = false): void => {
    const range = exact ? clampExactRange(start, end) : clampSnappedRange(start, end);

    if (hasScheduleOverlap(targetUserId, range.start, range.end)) {
        showToast('error', OVERLAP_MESSAGE);
        return;
    }

    submitting.value = true;
    addDraft.value = null;

    const payload: Record<string, string> = {
        date: props.selectedDate,
        start_time: minutesToTime(range.start),
        end_time: minutesToTime(range.end),
        task_name: taskName,
    };

    if (props.isAdmin && targetUserId !== props.currentUserId) {
        payload.user_id = targetUserId;
    }

    router.post('/task-assignments', payload, {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Task added.'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            showToast('error', Array.isArray(firstError) ? firstError[0] : 'Could not add task.');
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
};

const removeAssignment = (assignmentId: string) => {
    submitting.value = true;

    router.delete(`/task-assignments/${assignmentId}`, {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Removed.'),
        onError: () => showToast('error', 'Could not remove.'),
        onFinish: () => {
            submitting.value = false;
        },
    });
};

const removeLunchBooking = (): void => {
    submitting.value = true;

    router.delete('/lunch-booking', {
        preserveScroll: true,
        only: reloadKeys,
        onSuccess: () => showToast('success', 'Lunch removed.'),
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            showToast('error', Array.isArray(firstError) ? firstError[0] : 'Could not remove lunch.');
        },
        onFinish: () => {
            submitting.value = false;
        },
    });
};

const onTrackClick = (event: MouseEvent, userId: string, userName: string): void => {
    if (!canEdit.value || !canEditRow(userId) || submitting.value || dragState.value) {
        return;
    }

    if ((event.target as HTMLElement).closest('[data-schedule-block]')) {
        return;
    }

    const track = event.currentTarget as HTMLElement;
    const clicked = snapMinutes(minutesFromPointer(
        event.clientX,
        track.getBoundingClientRect(),
        dayStart.value,
        daySpan.value,
    ));
    const isOwnRow = userId === props.currentUserId;
    const lunchWindowStart = timeToMinutes('10:30');
    const lunchWindowEnd = timeToMinutes('14:30');
    const mode = isOwnRow && canAddLunch.value && clicked >= lunchWindowStart && clicked <= lunchWindowEnd
        ? 'lunch'
        : 'task';

    openAddDraft(clicked, userId, userName, mode);
};

const openAddDraft = (
    startMinutes?: number,
    userId?: string,
    userName?: string,
    mode: 'task' | 'time_off' | 'lunch' = 'task',
): void => {
    if (!props.currentUserId) {
        return;
    }

    editDraft.value = null;

    const targetUserId = userId ?? props.currentUserId;
    const targetUserName = userName ?? 'You';
    let draftMode: AddDraft['mode'] = targetUserId === props.currentUserId ? mode : 'task';

    if (draftMode === 'lunch' && !props.canEditLunch) {
        draftMode = 'task';
    }

    const anchor = startMinutes ?? snapMinutes(dayStart.value + daySpan.value / 2, SNAP_MINUTES);
    const lunchRange = defaultLunchRange();
    const defaultEnd = draftMode === 'lunch' ? 60 : DEFAULT_BLOCK_MINUTES;
    const defaultStart = draftMode === 'lunch' ? lunchRange.start : anchor;
    const end = draftMode === 'lunch'
        ? lunchRange.end
        : Math.min(dayEnd.value, anchor + DEFAULT_BLOCK_MINUTES);
    const start = draftMode === 'lunch'
        ? lunchRange.start
        : Math.max(dayStart.value, end - DEFAULT_BLOCK_MINUTES);
    const range = draftMode === 'lunch' ? lunchRange : clampSnappedRange(start, end);

    addDraft.value = {
        mode: draftMode,
        userId: targetUserId,
        userName: targetUserName,
        startTime: minutesToTime(range.start),
        endTime: minutesToTime(range.end),
        taskName: defaultTaskName.value,
        notes: '',
    };
};

const applyTaskSuggestion = (draft: AddDraft | EditDraft, name: string): void => {
    draft.taskName = name;
};

const setAddDraftMode = (mode: AddDraft['mode']): void => {
    if (!addDraft.value) {
        return;
    }

    addDraft.value.mode = mode;

    if (mode === 'lunch') {
        const range = defaultLunchRange();
        addDraft.value.startTime = minutesToTime(range.start);
        addDraft.value.endTime = minutesToTime(range.end);
    }
};

const openLunchDraft = (startMinutes?: number): void => {
    if (!props.currentUserId || !canAddLunch.value) {
        return;
    }

    openAddDraft(startMinutes, props.currentUserId, 'You', 'lunch');
};

const openEditDraft = (block: TimeBlockData): void => {
    if (!isEditableBlock(block)) {
        return;
    }

    addDraft.value = null;

    editDraft.value = {
        block,
        startTime: block.startTime,
        endTime: block.endTime,
        taskName: block.taskName ?? 'Away',
    };
};

const cancelDrag = (): void => {
    dragState.value = null;
    window.removeEventListener('pointermove', onPointerMove);
    window.removeEventListener('pointerup', onPointerUp);
};

const onBlockDoubleClick = (block: TimeBlockData): void => {
    if (!isEditableBlock(block) || block.type !== 'task_assignment') {
        return;
    }

    cancelDrag();
    openEditDraft(block);
};

const confirmAdd = (): void => {
    if (!addDraft.value) {
        return;
    }

    const range = clampExactRange(
        timeToMinutes(addDraft.value.startTime),
        timeToMinutes(addDraft.value.endTime),
    );

    if (addDraft.value.mode === 'time_off') {
        if (addDraft.value.userId !== props.currentUserId) {
            showToast('error', 'You can only request time off for yourself.');
            return;
        }

        const notes = addDraft.value.notes;
        addDraft.value = null;
        createTimeOffRequest(range.start, range.end, notes, true);
        return;
    }

    if (addDraft.value.mode === 'lunch') {
        if (!canAddLunch.value) {
            showToast('error', 'Lunch can only be booked for today.');
            return;
        }

        addDraft.value = null;
        createLunchBooking(range.start, range.end, true);
        return;
    }

    if (addDraft.value.taskName.trim() === '') {
        showToast('error', 'Enter a task name.');
        return;
    }

    const taskName = addDraft.value.taskName.trim();
    const targetUserId = addDraft.value.userId;

    addDraft.value = null;
    createAssignment(range.start, range.end, taskName, targetUserId, true);
};

const confirmEdit = (): void => {
    if (!editDraft.value || editDraft.value.taskName.trim() === '') {
        showToast('error', 'Enter a task name.');
        return;
    }

    const { block, startTime, endTime, taskName } = editDraft.value;
    const range = clampExactRange(timeToMinutes(startTime), timeToMinutes(endTime));

    editDraft.value = null;

    if (block.type === 'lunch_booking' && block.lunchBookingId) {
        saveLunchBooking(block.lunchBookingId, range.start, range.end, true);
        return;
    }

    if (block.type === 'task_assignment' && block.assignmentId) {
        saveAssignment(block.assignmentId, range.start, range.end, taskName.trim(), true);
    }
};

const onPointerDown = (
    event: PointerEvent,
    block: TimeBlockData,
    mode: DragMode,
) => {
    if (!isEditableBlock(block) || submitting.value) return;

    const track = (event.currentTarget as HTMLElement).closest('[data-schedule-track]') as HTMLElement | null;
    if (!track) return;

    event.preventDefault();
    event.stopPropagation();

    const start = timeToMinutes(block.startTime);
    const end = timeToMinutes(block.endTime);

    dragState.value = {
        mode,
        block,
        trackElement: track,
        pointerId: event.pointerId,
        startPointerMinutes: minutesFromPointer(event.clientX, track.getBoundingClientRect(), dayStart.value, daySpan.value),
        originalStart: start,
        originalEnd: end,
        previewStart: start,
        previewEnd: end,
    };

    (event.target as HTMLElement).setPointerCapture?.(event.pointerId);
    window.addEventListener('pointermove', onPointerMove);
    window.addEventListener('pointerup', onPointerUp);
};

const onPointerMove = (event: PointerEvent) => {
    if (!dragState.value || event.pointerId !== dragState.value.pointerId) return;

    const current = minutesFromPointer(
        event.clientX,
        dragState.value.trackElement.getBoundingClientRect(),
        dayStart.value,
        daySpan.value,
    );
    const delta = current - dragState.value.startPointerMinutes;
    const duration = dragState.value.originalEnd - dragState.value.originalStart;

    if (dragState.value.mode === 'move') {
        let start = snapMinutes(dragState.value.originalStart + delta, SNAP_MINUTES);
        let end = start + duration;
        if (end > dayEnd.value) {
            end = dayEnd.value;
            start = end - duration;
        }
        if (start < dayStart.value) {
            start = dayStart.value;
            end = start + duration;
        }
        dragState.value.previewStart = start;
        dragState.value.previewEnd = end;
    }

    if (dragState.value.mode === 'resize-start') {
        const end = dragState.value.originalEnd;
        const start = snapMinutes(Math.min(current, end - MIN_SNAPPED_BLOCK_MINUTES), SNAP_MINUTES);
        dragState.value.previewStart = Math.max(dayStart.value, start);
        dragState.value.previewEnd = end;
    }

    if (dragState.value.mode === 'resize-end') {
        const start = dragState.value.originalStart;
        const end = snapMinutes(Math.max(current, start + MIN_SNAPPED_BLOCK_MINUTES), SNAP_MINUTES);
        dragState.value.previewEnd = Math.min(dayEnd.value, end);
        dragState.value.previewStart = start;
    }
};

const onPointerUp = (event: PointerEvent) => {
    if (!dragState.value || event.pointerId !== dragState.value.pointerId) return;

    const { block, previewStart, previewEnd, originalStart, originalEnd } = dragState.value;

    dragState.value = null;
    window.removeEventListener('pointermove', onPointerMove);
    window.removeEventListener('pointerup', onPointerUp);

    if (previewStart === originalStart && previewEnd === originalEnd) {
        return;
    }

    if (block.type === 'lunch_booking' && block.lunchBookingId) {
        saveLunchBooking(block.lunchBookingId, previewStart, previewEnd);
        return;
    }

    if (block.type === 'task_assignment' && block.assignmentId) {
        saveAssignment(
            block.assignmentId,
            previewStart,
            previewEnd,
            block.taskName ?? 'Away',
        );
    }
};

onBeforeUnmount(() => {
    window.removeEventListener('pointermove', onPointerMove);
    window.removeEventListener('pointerup', onPointerUp);
});
</script>

<template>
    <div class="card overflow-hidden flex flex-col w-full">
        <div class="px-4 py-3 border-b border-white/5 shrink-0 bg-white/[0.02] space-y-3">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-2 min-w-0">
                    <button
                        type="button"
                        class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800/60 transition-colors disabled:opacity-40"
                        :disabled="loading"
                        aria-label="Previous day"
                        @click="navigateDay(-1)"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <Popover v-slot="{ close }" class="relative">
                        <PopoverButton
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-slate-100 hover:bg-slate-800/60 transition-colors disabled:opacity-40"
                            :disabled="loading"
                        >
                            <span class="truncate">{{ formattedSelectedDate }}</span>
                            <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </PopoverButton>
                        <PopoverPanel class="absolute left-0 z-30 mt-2 rounded-xl bg-slate-800 border border-slate-700 shadow-lg p-3">
                            <label class="block text-xs text-slate-400 mb-2">Jump to date</label>
                            <input
                                type="date"
                                :value="selectedDate"
                                class="input text-sm"
                                @change="(e) => { onDatePickerChange(e); close(); }"
                            >
                        </PopoverPanel>
                    </Popover>

                    <button
                        type="button"
                        class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800/60 transition-colors disabled:opacity-40"
                        :disabled="loading"
                        aria-label="Next day"
                        @click="navigateDay(1)"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <button
                        v-if="!isToday"
                        type="button"
                        class="text-xs px-2 py-1 rounded-lg text-primary-400 hover:text-primary-300 hover:bg-slate-800/60 transition-colors disabled:opacity-40"
                        :disabled="loading"
                        @click="goToToday"
                    >
                        Today
                    </button>
                </div>

                <div v-if="canEdit && !addDraft && !editDraft" class="flex gap-2 shrink-0 flex-wrap justify-end">
                    <button
                        v-if="canAddLunch"
                        type="button"
                        class="btn text-sm px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white border-0"
                        :disabled="submitting"
                        @click="openLunchDraft()"
                    >
                        Book lunch
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary text-sm px-3 py-1.5"
                        :disabled="submitting"
                        @click="openAddDraft(undefined, undefined, undefined, 'task')"
                    >
                        Add task
                    </button>
                    <button
                        type="button"
                        class="btn text-sm px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white border-0"
                        :disabled="submitting"
                        @click="openAddDraft(undefined, undefined, undefined, 'time_off')"
                    >
                        Request time off
                    </button>
                </div>
                <div v-if="!loggedIn" class="text-xs text-slate-500">
                    <a href="/login" class="text-primary-400 hover:text-primary-300">Log in</a> to edit your row
                </div>
            </div>

            <p v-if="canEdit || isAdmin" class="text-xs text-slate-500">
                <span v-if="canEdit">
                    Click your row to add a task or time off
                    <span v-if="canAddLunch"> — or book lunch from the slot around midday</span>
                </span>
                <span v-if="canEdit && isAdmin" class="text-amber-400/80"> · admin: edit anyone's tasks</span>
            </p>

            <div
                v-if="addDraft"
                class="mt-3 flex flex-wrap items-end gap-3 rounded-lg bg-slate-900/70 border p-3"
                :class="addDraftBorderClass"
            >
                <p v-if="addDraft.userId !== currentUserId" class="w-full text-xs text-amber-300/90">
                    Adding task for {{ addDraft.userName }}
                </p>
                <div
                    v-if="addDraft.userId === currentUserId"
                    class="w-full flex gap-1 p-0.5 rounded-lg bg-slate-800/80 border border-slate-700/50"
                >
                    <button
                        type="button"
                        class="flex-1 text-xs py-1.5 rounded-md font-medium transition-colors"
                        :class="addDraft.mode === 'task'
                            ? 'bg-indigo-600 text-white'
                            : 'text-slate-400 hover:text-slate-200'"
                        @click="addDraft.mode = 'task'"
                    >
                        Task
                    </button>
                    <button
                        type="button"
                        class="flex-1 text-xs py-1.5 rounded-md font-medium transition-colors"
                        :class="addDraft.mode === 'time_off'
                            ? 'bg-amber-600 text-white'
                            : 'text-slate-400 hover:text-slate-200'"
                        @click="setAddDraftMode('time_off')"
                    >
                        Time off
                    </button>
                    <button
                        v-if="canAddLunch"
                        type="button"
                        class="flex-1 text-xs py-1.5 rounded-md font-medium transition-colors"
                        :class="addDraft.mode === 'lunch'
                            ? 'bg-emerald-600 text-white'
                            : 'text-slate-400 hover:text-slate-200'"
                        @click="setAddDraftMode('lunch')"
                    >
                        Lunch
                    </button>
                </div>
                <p v-if="addDraft.mode === 'lunch'" class="w-full text-xs text-emerald-300/90">
                    Book lunch for today
                </p>
                <div v-if="addDraft.mode === 'task'" class="flex-1 min-w-[180px]">
                    <label class="block text-xs text-slate-400 mb-1">Task</label>
                    <input
                        v-model="addDraft.taskName"
                        type="text"
                        list="task-name-suggestions"
                        placeholder="e.g. Deep work, Client call…"
                        class="input text-sm py-1.5 w-full"
                    >
                    <datalist id="task-name-suggestions">
                        <option v-for="name in taskSuggestions" :key="name" :value="name" />
                    </datalist>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <button
                            v-for="name in taskSuggestions"
                            :key="`add-${name}`"
                            type="button"
                            class="text-[10px] px-2 py-0.5 rounded-full border border-slate-600 text-slate-400 hover:border-primary-500/50 hover:text-primary-300"
                            @click="applyTaskSuggestion(addDraft, name)"
                        >
                            {{ name }}
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Start</label>
                    <input v-model="addDraft.startTime" type="time" step="60" class="input text-sm py-1.5">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">End</label>
                    <input v-model="addDraft.endTime" type="time" step="60" class="input text-sm py-1.5">
                </div>
                <div v-if="addDraft.mode === 'time_off'" class="w-full">
                    <label class="block text-xs text-slate-400 mb-1">Notes (optional)</label>
                    <textarea
                        v-model="addDraft.notes"
                        rows="2"
                        class="input text-sm py-1.5 w-full resize-none"
                        placeholder="Reason or details…"
                    />
                </div>
                <div class="flex gap-2">
                    <button
                        type="button"
                        class="text-sm px-3 py-1.5 btn"
                        :class="{
                            'bg-amber-600 hover:bg-amber-500 text-white border-0': addDraft.mode === 'time_off',
                            'bg-emerald-600 hover:bg-emerald-500 text-white border-0': addDraft.mode === 'lunch',
                            'btn-primary': addDraft.mode === 'task',
                        }"
                        :disabled="submitting"
                        @click="confirmAdd"
                    >
                        {{ addDraft.mode === 'time_off' ? 'Request' : addDraft.mode === 'lunch' ? 'Book lunch' : 'Add' }}
                    </button>
                    <button type="button" class="btn btn-ghost text-sm px-3 py-1.5" @click="addDraft = null">
                        Cancel
                    </button>
                </div>
            </div>

            <div
                v-if="editDraft"
                class="mt-3 flex flex-wrap items-end gap-3 rounded-lg bg-slate-900/70 border border-emerald-500/40 p-3"
            >
                <p class="w-full text-xs font-medium text-slate-300">
                    Edit {{ blockLabel(editDraft.block) }} — exact times
                </p>
                <div v-if="editDraft.block.type === 'task_assignment'" class="flex-1 min-w-[180px]">
                    <label class="block text-xs text-slate-400 mb-1">Task</label>
                    <input
                        v-model="editDraft.taskName"
                        type="text"
                        list="task-name-suggestions-edit"
                        placeholder="e.g. Deep work, Client call…"
                        class="input text-sm py-1.5 w-full"
                    >
                    <datalist id="task-name-suggestions-edit">
                        <option v-for="name in taskSuggestions" :key="`edit-${name}`" :value="name" />
                    </datalist>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <button
                            v-for="name in taskSuggestions"
                            :key="`edit-suggest-${name}`"
                            type="button"
                            class="text-[10px] px-2 py-0.5 rounded-full border border-slate-600 text-slate-400 hover:border-primary-500/50 hover:text-primary-300"
                            @click="applyTaskSuggestion(editDraft, name)"
                        >
                            {{ name }}
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Start</label>
                    <input v-model="editDraft.startTime" type="time" step="60" class="input text-sm py-1.5">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">End</label>
                    <input v-model="editDraft.endTime" type="time" step="60" class="input text-sm py-1.5">
                </div>
                <div class="flex gap-2">
                    <button type="button" class="btn btn-primary text-sm px-3 py-1.5" :disabled="submitting" @click="confirmEdit">
                        Save
                    </button>
                    <button type="button" class="btn btn-ghost text-sm px-3 py-1.5" @click="editDraft = null">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 flex-1 relative min-h-[200px]">
            <LoadingSpinner v-if="loading" />

            <template v-else>
                <div class="flex text-xs text-slate-500 mb-2 pl-24 pr-1">
                    <div class="flex-1 flex justify-between">
                        <span v-for="marker in hourMarkers" :key="marker">{{ marker }}</span>
                    </div>
                </div>

                <div v-if="scheduleRows.length === 0" class="text-center text-slate-400 py-12 text-sm">
                    <p>No one scheduled for this day.</p>
                    <p v-if="canEdit" class="text-xs text-slate-500 mt-2">Click a time slot on your row to add a task or request time off.</p>
                </div>

                <div v-else class="space-y-2">
                    <div
                        v-for="user in scheduleRows"
                        :key="user.userId"
                        class="flex items-stretch gap-3"
                        :class="user.isMe ? 'rounded-lg bg-primary-500/5 ring-1 ring-primary-500/20 p-2 -mx-2' : ''"
                    >
                        <div
                            class="w-20 shrink-0 text-sm truncate pt-3 flex flex-col gap-1"
                            :class="user.isMe ? 'text-primary-300 font-medium' : 'text-slate-300'"
                        >
                            <span :title="user.userName">{{ user.isMe ? 'You' : user.userName }}</span>
                            <div v-if="canEditRow(user.userId) && !addDraft && !editDraft" class="flex flex-col gap-0.5">
                                <button
                                    type="button"
                                    class="text-[10px] text-left text-primary-400 hover:text-primary-300 underline"
                                    @click.stop="openAddDraft(undefined, user.userId, user.isMe ? 'You' : user.userName, user.isMe ? 'task' : 'task')"
                                >
                                    + Add
                                </button>
                                <button
                                    v-if="user.isMe && canAddLunch"
                                    type="button"
                                    class="text-[10px] text-left text-emerald-400 hover:text-emerald-300 underline"
                                    @click.stop="openLunchDraft()"
                                >
                                    + Lunch
                                </button>
                            </div>
                        </div>

                        <div
                            data-schedule-track
                            class="flex-1 relative h-14 rounded-lg border transition-colors"
                            :class="[
                                canEditRow(user.userId)
                                    ? 'bg-slate-900/60 border-slate-600/50 hover:border-primary-500/40 cursor-crosshair'
                                    : 'bg-slate-900/40 border-slate-700/30',
                            ]"
                            @click="onTrackClick($event, user.userId, user.isMe ? 'You' : user.userName)"
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
                                v-if="user.isMe && lunchPlaceholderStyle"
                                type="button"
                                class="absolute top-1 bottom-1 rounded-md border-2 border-dashed border-emerald-500/50 bg-emerald-500/10 text-[10px] text-emerald-300/90 font-medium hover:bg-emerald-500/20 hover:border-emerald-400/70 transition-colors z-0"
                                :style="lunchPlaceholderStyle"
                                title="Book lunch"
                                @click.stop="openLunchDraft()"
                            >
                                <span class="truncate px-1">+ Lunch</span>
                            </button>

                            <div
                                v-for="block in user.blocks"
                                :key="block.id"
                                data-schedule-block
                                class="absolute top-1 bottom-1 rounded-md flex items-center overflow-hidden group select-none touch-none"
                                :class="blockStyle(block).class"
                                :style="{ left: blockStyle(block).left, width: blockStyle(block).width, backgroundColor: blockStyle(block).backgroundColor }"
                                :title="`${blockLabel(block)}: ${minutesToTime(blockTimes(block).start)} – ${minutesToTime(blockTimes(block).end)}${isEditableBlock(block) && block.type === 'task_assignment' ? ' · double-click to edit' : ''}`"
                                @pointerdown="onPointerDown($event, block, 'move')"
                                @dblclick.stop="onBlockDoubleClick(block)"
                            >
                                <div
                                    v-if="isEditableBlock(block)"
                                    class="absolute left-0 top-0 bottom-0 w-1.5 cursor-ew-resize opacity-0 group-hover:opacity-100 bg-white/30"
                                    @pointerdown.stop="onPointerDown($event, block, 'resize-start')"
                                />
                                <span class="text-[10px] text-white font-medium truncate px-2 flex-1 text-center pointer-events-none">
                                    {{ blockLabel(block) }}
                                </span>
                                <span class="text-[9px] text-white/70 px-1 hidden sm:inline pointer-events-none">
                                    {{ minutesToTime(blockTimes(block).start) }}
                                </span>
                                <button
                                    v-if="isEditableBlock(block)"
                                    type="button"
                                    class="absolute top-0.5 right-5 hidden group-hover:flex w-4 h-4 items-center justify-center rounded bg-black/40 text-white/80 hover:text-white text-[10px]"
                                    title="Set exact times"
                                    @click.stop="openEditDraft(block)"
                                >
                                    ✎
                                </button>
                                <button
                                    v-if="isEditableBlock(block) && block.type === 'task_assignment'"
                                    type="button"
                                    class="absolute top-0.5 right-0.5 hidden group-hover:flex w-4 h-4 items-center justify-center rounded bg-black/40 text-white/80 hover:text-white text-xs"
                                    @click.stop="removeAssignment(block.assignmentId!)"
                                >
                                    ×
                                </button>
                                <button
                                    v-if="isEditableBlock(block) && block.type === 'lunch_booking'"
                                    type="button"
                                    class="absolute top-0.5 right-0.5 hidden group-hover:flex w-4 h-4 items-center justify-center rounded bg-black/40 text-white/80 hover:text-white text-xs"
                                    title="Remove lunch"
                                    @click.stop="removeLunchBooking()"
                                >
                                    ×
                                </button>
                                <div
                                    v-if="isEditableBlock(block)"
                                    class="absolute right-0 top-0 bottom-0 w-1.5 cursor-ew-resize opacity-0 group-hover:opacity-100 bg-white/30"
                                    @pointerdown.stop="onPointerDown($event, block, 'resize-end')"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-4 justify-center text-xs text-slate-400">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-indigo-500/70"></span> Task</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-amber-500/70"></span> Time off</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-emerald-500/70"></span> Lunch</span>
                </div>
            </template>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </div>
</template>
