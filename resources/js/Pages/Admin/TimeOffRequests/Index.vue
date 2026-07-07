<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';

type PendingRequest = {
    id: string;
    userName: string;
    teamName: string;
    date: string | null;
    startTime: string | null;
    endTime: string | null;
    notes: string | null;
};

defineProps<{
    requests: PendingRequest[];
    isSiteAdmin: boolean;
}>();

const approve = (id: string) => {
    router.post(`/manage/time-off-requests/${id}/approve`, {}, { preserveScroll: true });
};

const reject = (id: string) => {
    router.post(`/manage/time-off-requests/${id}/reject`, {}, { preserveScroll: true });
};
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <h2 class="text-lg font-semibold text-slate-100">Pending time off requests</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/50 text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">User</th>
                            <th class="px-6 py-3 font-medium">Team</th>
                            <th class="px-6 py-3 font-medium">Date</th>
                            <th class="px-6 py-3 font-medium">Start</th>
                            <th class="px-6 py-3 font-medium">End</th>
                            <th class="px-6 py-3 font-medium">Notes</th>
                            <th class="px-6 py-3 font-medium" />
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <tr v-for="request in requests" :key="request.id" class="text-slate-200">
                            <td class="px-6 py-3">{{ request.userName }}</td>
                            <td class="px-6 py-3">{{ request.teamName }}</td>
                            <td class="px-6 py-3">{{ request.date }}</td>
                            <td class="px-6 py-3">{{ request.startTime }}</td>
                            <td class="px-6 py-3">{{ request.endTime }}</td>
                            <td class="px-6 py-3">{{ request.notes || '—' }}</td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2 justify-end">
                                    <Button size="sm" @click="approve(request.id)">
                                        Approve
                                    </Button>
                                    <Button size="sm" variant="danger" @click="reject(request.id)">
                                        Reject
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="requests.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                No pending requests.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
