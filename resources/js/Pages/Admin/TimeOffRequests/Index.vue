<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import type { PendingTimeOffRequestData } from '@/Types/generated';
import { approve as approveRoute, reject as rejectRoute } from '@/routes/manage/time-off-requests';

defineProps<{
    requests: PendingTimeOffRequestData[];
    isSiteAdmin: boolean;
}>();

const approve = (id: string): void => {
    router.post(approveRoute.url({ timeOffRequest: id }), {}, { preserveScroll: true });
};

const reject = (id: string): void => {
    router.post(rejectRoute.url({ timeOffRequest: id }), {}, { preserveScroll: true });
};
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="card overflow-hidden">
            <div class="border-b border-white/10 px-6 py-4">
                <h2 class="text-lg font-semibold tracking-tight text-white">Pending time off requests</h2>
                <p class="mt-0.5 text-sm text-slate-400">Approve or reject requests waiting on this site.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="px-6">User</th>
                            <th class="px-6">Team</th>
                            <th class="px-6">Date</th>
                            <th class="px-6">Start</th>
                            <th class="px-6">End</th>
                            <th class="px-6">Notes</th>
                            <th class="px-6" />
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="request in requests"
                            :key="request.id"
                            class="text-slate-200"
                        >
                            <td class="px-6">{{ request.userName }}</td>
                            <td class="px-6">{{ request.teamName }}</td>
                            <td class="px-6">{{ request.date }}</td>
                            <td class="px-6">{{ request.startTime }}</td>
                            <td class="px-6">{{ request.endTime }}</td>
                            <td class="px-6 text-slate-400">{{ request.notes || '—' }}</td>
                            <td class="px-6">
                                <div class="flex justify-end gap-2">
                                    <Button
                                        size="sm"
                                        @click="approve(request.id)"
                                    >
                                        Approve
                                    </Button>
                                    <Button
                                        size="sm"
                                        variant="danger"
                                        @click="reject(request.id)"
                                    >
                                        Reject
                                    </Button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="requests.length === 0">
                            <td
                                colspan="7"
                                class="px-6 py-8 text-center text-slate-500"
                            >
                                No pending requests.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
