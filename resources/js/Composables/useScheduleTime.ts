export const SNAP_MINUTES = 15;
export const DEFAULT_BLOCK_MINUTES = 60;
export const MIN_SNAPPED_BLOCK_MINUTES = 15;
export const MIN_EXACT_BLOCK_MINUTES = 1;

export const toLocalDateString = (date: Date): string => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

export const parseLocalDate = (dateString: string): Date => {
    const [year, month, day] = dateString.split('-').map(Number);
    return new Date(year, month - 1, day);
};

export const timeToMinutes = (time: string): number => {
    const [hours, minutes] = time.split(':').map(Number);
    return hours * 60 + minutes;
};

export const minutesToTime = (minutes: number): string => {
    const rounded = Math.round(minutes);
    const clamped = Math.max(0, Math.min(rounded, 24 * 60 - 1));
    const hours = Math.floor(clamped / 60);
    const mins = clamped % 60;
    return `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
};

export const snapMinutes = (minutes: number, interval = SNAP_MINUTES): number => {
    return Math.round(minutes / interval) * interval;
};

export const minutesFromPointer = (
    clientX: number,
    trackRect: DOMRect,
    dayStart: number,
    daySpan: number,
): number => {
    const ratio = Math.max(0, Math.min(1, (clientX - trackRect.left) / trackRect.width));
    return dayStart + ratio * daySpan;
};

export const percentFromMinutes = (minutes: number, dayStart: number, daySpan: number): number => {
    return ((minutes - dayStart) / daySpan) * 100;
};

const floorToHour = (minutes: number): number => Math.floor(minutes / 60) * 60;

const ceilToHour = (minutes: number): number => Math.ceil(minutes / 60) * 60;

/** Widen the timeline beyond core working hours so blocks can be placed in the evening, etc. */
export const expandTimelineBounds = (
    workingStart: number,
    workingEnd: number,
    blocks: Array<{ startTime: string; endTime: string }>,
    paddingBefore = 120,
    paddingAfter = 240,
): { start: number; end: number } => {
    const dayMin = 0;
    const dayMax = 24 * 60;

    let start = workingStart - paddingBefore;
    let end = workingEnd + paddingAfter;

    for (const block of blocks) {
        const blockStart = timeToMinutes(block.startTime);
        const blockEnd = timeToMinutes(block.endTime);
        start = Math.min(start, blockStart - 60);
        end = Math.max(end, blockEnd + 60);
    }

    start = floorToHour(Math.max(dayMin, start));
    end = ceilToHour(Math.min(dayMax, end));

    if (end <= start) {
        end = Math.min(dayMax, start + 60);
    }

    return { start, end };
};
