<script setup lang="ts">
export interface AddDraft {
    mode: 'task' | 'time_off' | 'lunch';
    userId: string;
    userName: string;
    startTime: string;
    endTime: string;
    taskName: string;
    notes: string;
}

interface Props {
    addDraft: AddDraft;
    currentUserId: string | null;
    canAddLunch: boolean;
    taskSuggestions: string[];
    submitting: boolean;
    borderClass: string;
}

defineProps<Props>();

const emit = defineEmits<{
    confirm: [];
    cancel: [];
    setMode: [mode: AddDraft['mode']];
    applyTaskSuggestion: [name: string];
}>();
</script>

<template>
    <div
        class="mt-3 flex flex-wrap items-end gap-3 border bg-dark-950/60 p-3"
        :class="borderClass"
    >
        <p
            v-if="addDraft.userId !== currentUserId"
            class="w-full text-xs text-amber-300/90"
        >
            Adding task for {{ addDraft.userName }}
        </p>
        <div
            v-if="addDraft.userId === currentUserId"
            class="flex w-full gap-1 border border-white/10 bg-white/[0.03] p-0.5"
        >
            <button
                type="button"
                class="flex-1 rounded-md py-1.5 text-xs font-medium transition-colors"
                :class="addDraft.mode === 'task'
                    ? 'bg-primary-600 text-white'
                    : 'text-slate-400 hover:text-slate-200'"
                @click="emit('setMode', 'task')"
            >
                Task
            </button>
            <button
                type="button"
                class="flex-1 rounded-md py-1.5 text-xs font-medium transition-colors"
                :class="addDraft.mode === 'time_off'
                    ? 'bg-amber-600 text-white'
                    : 'text-slate-400 hover:text-slate-200'"
                @click="emit('setMode', 'time_off')"
            >
                Time off
            </button>
            <button
                v-if="canAddLunch"
                type="button"
                class="flex-1 rounded-md py-1.5 text-xs font-medium transition-colors"
                :class="addDraft.mode === 'lunch'
                    ? 'bg-sky-600 text-white'
                    : 'text-slate-400 hover:text-slate-200'"
                @click="emit('setMode', 'lunch')"
            >
                Lunch
            </button>
        </div>
        <p
            v-if="addDraft.mode === 'lunch'"
            class="w-full text-xs text-sky-300/90"
        >
            Book lunch for today
        </p>
        <div
            v-if="addDraft.mode === 'task'"
            class="min-w-[180px] flex-1"
        >
            <label class="mb-1 block text-xs text-slate-400">Task</label>
            <input
                v-model="addDraft.taskName"
                type="text"
                list="task-name-suggestions"
                placeholder="e.g. Deep work, Client call…"
                class="input w-full py-1.5 text-sm"
            >
            <datalist id="task-name-suggestions">
                <option
                    v-for="name in taskSuggestions"
                    :key="name"
                    :value="name"
                />
            </datalist>
            <div class="mt-2 flex flex-wrap gap-1.5">
                <button
                    v-for="name in taskSuggestions"
                    :key="`add-${name}`"
                    type="button"
                    class="rounded-md border border-white/15 px-2 py-0.5 text-[10px] text-slate-400 transition-colors hover:border-primary-500/50 hover:text-primary-300"
                    @click="emit('applyTaskSuggestion', name)"
                >
                    {{ name }}
                </button>
            </div>
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-400">Start</label>
            <input
                v-model="addDraft.startTime"
                type="time"
                step="60"
                class="input py-1.5 text-sm"
            >
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-400">End</label>
            <input
                v-model="addDraft.endTime"
                type="time"
                step="60"
                class="input py-1.5 text-sm"
            >
        </div>
        <div
            v-if="addDraft.mode === 'time_off'"
            class="w-full"
        >
            <label class="mb-1 block text-xs text-slate-400">Notes (optional)</label>
            <textarea
                v-model="addDraft.notes"
                rows="2"
                class="input w-full resize-none py-1.5 text-sm"
                placeholder="Reason or details…"
            />
        </div>
        <div class="flex gap-2">
            <button
                type="button"
                class="btn px-3 py-1.5 text-sm"
                :class="{
                    'border-0 bg-amber-600 text-white hover:bg-amber-500': addDraft.mode === 'time_off',
                    'border-0 bg-sky-600 text-white hover:bg-sky-500': addDraft.mode === 'lunch',
                    'btn-primary': addDraft.mode === 'task',
                }"
                :disabled="submitting"
                @click="emit('confirm')"
            >
                {{ addDraft.mode === 'time_off' ? 'Request' : addDraft.mode === 'lunch' ? 'Book lunch' : 'Add' }}
            </button>
            <button
                type="button"
                class="btn btn-ghost px-3 py-1.5 text-sm"
                @click="emit('cancel')"
            >
                Cancel
            </button>
        </div>
    </div>
</template>
