<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DayTimeline from '@/Components/DayTimeline.vue';
import type { HomePageData } from '@/Types/generated';

const props = defineProps<HomePageData>();

const page = usePage();
const auth = computed(() => page.props.auth);

const dateLoading = ref(false);

const isLoggedIn = computed(() => !!auth.value.user);
const currentUserId = computed(() => auth.value.user?.id ?? null);
const isAdmin = computed(() => !!auth.value.user?.is_admin);
const tasks = computed(() => props.tasks ?? []);
const scheduleUsers = computed(() => props.scheduleUsers ?? []);

const isToday = computed(() => {
    if (!props.selectedDate) {
        return false;
    }

    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}` === props.selectedDate;
});

const handleDateChange = (dateString: string): void => {
    if (dateString === props.selectedDate) {
        return;
    }

    dateLoading.value = true;

    router.get('/', { date: dateString }, {
        preserveScroll: true,
        only: [
            'timeBlocks',
            'myLunchBooking',
            'myTaskAssignments',
            'selectedDate',
            'workingHours',
            'tasks',
            'myWorkingHours',
            'scheduleUsers',
        ],
        onFinish: () => {
            dateLoading.value = false;
        },
        onError: () => {
            dateLoading.value = false;
        },
    });
};
</script>

<template>
    <AppLayout>
        <DayTimeline
            :time-blocks="props.timeBlocks"
            :working-hours="props.workingHours"
            :selected-date="props.selectedDate"
            :tasks="tasks"
            :schedule-users="scheduleUsers"
            :current-user-id="currentUserId"
            :logged-in="isLoggedIn"
            :loading="dateLoading"
            :can-edit-lunch="isToday"
            :is-admin="isAdmin"
            @date-change="handleDateChange"
        />
    </AppLayout>
</template>
