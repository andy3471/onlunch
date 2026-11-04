import type { TimeBlockData } from '@/Types/generated';

export interface BlockLaneLayout {
    lane: number;
    laneCount: number;
}

/**
 * Assign overlapping blocks to vertical lanes so they remain readable.
 */
export function assignBlockLanes (
    blocks: TimeBlockData[],
    timesFor: (block: TimeBlockData) => { start: number; end: number },
): Map<string, BlockLaneLayout> {
    const sorted = [...blocks].sort((a, b) => {
        const aTimes = timesFor(a);
        const bTimes = timesFor(b);

        if (aTimes.start !== bTimes.start) {
            return aTimes.start - bTimes.start;
        }

        return aTimes.end - bTimes.end;
    });

    const laneEnds: number[] = [];
    const lanes = new Map<string, number>();

    for (const block of sorted) {
        const { start, end } = timesFor(block);
        let lane = laneEnds.findIndex((laneEnd) => laneEnd <= start);

        if (lane === -1) {
            lane = laneEnds.length;
            laneEnds.push(end);
        } else {
            laneEnds[lane] = end;
        }

        lanes.set(block.id, lane);
    }

    const laneCount = Math.max(1, laneEnds.length);
    const layouts = new Map<string, BlockLaneLayout>();

    for (const block of blocks) {
        layouts.set(block.id, {
            lane: lanes.get(block.id) ?? 0,
            laneCount,
        });
    }

    return layouts;
}
