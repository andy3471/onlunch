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
        class="mt-3 flex flex-wrap items-end gap-3 rounded-lg bg-slate-900/70 border p-3"
        :class="borderClass"
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
                @click="emit('setMode', 'task')"
            >
                Task
            </button>
            <button
                type="button"
                class="flex-1 text-xs py-1.5 rounded-md font-medium transition-colors"
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
                class="flex-1 text-xs py-1.5 rounded-md font-medium transition-colors"
                :class="addDraft.mode === 'lunch'
                    ? 'bg-emerald-600 text-white'
                    : 'text-slate-400 hover:text-slate-200'"
                @click="emit('setMode', 'lunch')"
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
                    @click="emit('applyTaskSuggestion', name)"
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
                @click="emit('confirm')"
            >
                {{ addDraft.mode === 'time_off' ? 'Request' : addDraft.mode === 'lunch' ? 'Book lunch' : 'Add' }}
            </button>
            <button type="button" class="btn btn-ghost text-sm px-3 py-1.5" @click="emit('cancel')">
                Cancel
            </button>
        </div>
    </div>
</template>
