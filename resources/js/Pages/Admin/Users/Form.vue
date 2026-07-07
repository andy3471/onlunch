<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AdminNav from '@/Components/AdminNav.vue';
import Button from '@/Components/Button.vue';
import Toast from '@/Components/Toast.vue';

type PresetOption = {
    id: string;
    name: string;
    startTime: string;
    endTime: string;
    label: string;
};

type TeamOption = {
    id: string;
    name: string;
};

type TeamMembershipForm = {
    teamId: string;
    isMember: boolean;
    isScheduled: boolean;
    scheduleVisibility: 'everyone' | 'team' | 'approvers' | 'private';
    isTimeOffApprover: boolean;
};

type UserFormData = {
    id: string;
    name: string;
    email: string;
    isAdmin: boolean;
};

const props = defineProps<{
    presets: PresetOption[];
    teams: TeamOption[];
    defaultTeamId: string | null;
    workingHourPresetId: string | null;
    timeOffApproval: 'site_default' | 'required' | 'auto';
    teamMemberships: TeamMembershipForm[];
    user: UserFormData | null;
}>();

const isEditing = computed(() => props.user !== null);

const name = ref(props.user?.name ?? '');
const email = ref(props.user?.email ?? '');
const isAdmin = ref(props.user?.isAdmin ?? false);
const workingHourPresetId = ref(props.workingHourPresetId ?? '');
const timeOffApproval = ref<'site_default' | 'required' | 'auto'>(props.timeOffApproval);
const memberships = ref<TeamMembershipForm[]>(
    props.teamMemberships.map((membership) => ({ ...membership })),
);
const password = ref('');
const passwordConfirmation = ref('');
const submitting = ref(false);
const toast = ref<{ type: string; message: string } | null>(null);

const membershipForTeam = (teamId: string): TeamMembershipForm | undefined => {
    return memberships.value.find((membership) => membership.teamId === teamId);
};

const toggleMembership = (teamId: string, isMember: boolean): void => {
    const membership = membershipForTeam(teamId);

    if (!membership) {
        return;
    }

    membership.isMember = isMember;
};

watch(
    () => props.teamMemberships,
    (value) => {
        memberships.value = value.map((membership) => ({ ...membership }));
    },
);

const payload = () => ({
    name: name.value,
    email: email.value,
    is_admin: isAdmin.value,
    working_hour_preset_id: workingHourPresetId.value || null,
    time_off_approval: timeOffApproval.value,
    team_memberships: memberships.value
        .filter((membership) => membership.isMember)
        .map((membership) => ({
            team_id: membership.teamId,
            is_scheduled: membership.isScheduled,
            schedule_visibility: membership.scheduleVisibility,
            is_time_off_approver: membership.isTimeOffApprover,
        })),
    ...(password.value ? { password: password.value, password_confirmation: passwordConfirmation.value } : {}),
});

const submit = () => {
    submitting.value = true;

    const options = {
        preserveScroll: true,
        onSuccess: () => {
            toast.value = { type: 'success', message: isEditing.value ? 'User updated.' : 'User created.' };
        },
        onError: (errors: Record<string, string | string[]>) => {
            const firstError = Object.values(errors)[0];
            toast.value = {
                type: 'error',
                message: Array.isArray(firstError) ? firstError[0] : 'Could not save user.',
            };
        },
        onFinish: () => {
            submitting.value = false;
        },
    };

    if (isEditing.value && props.user) {
        router.put(`/manage/users/${props.user.id}`, payload(), options);
    } else {
        router.post('/manage/users', payload(), options);
    }
};

const destroy = () => {
    if (!props.user || !confirm('Remove this user?')) {
        return;
    }

    router.delete(`/manage/users/${props.user.id}`);
};
</script>

<template>
    <AppLayout>
        <AdminNav />

        <div class="max-w-2xl mx-auto card p-6 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-100">
                    {{ isEditing ? 'Edit user' : 'Add user' }}
                </h2>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Name</label>
                    <input v-model="name" type="text" class="input" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <input v-model="email" type="email" class="input" required>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input v-model="isAdmin" type="checkbox" class="rounded border-slate-600 bg-slate-800 text-indigo-500">
                    Site admin
                </label>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Working hours</label>
                    <select
                        :value="workingHourPresetId ?? ''"
                        class="input"
                        @change="workingHourPresetId = ($event.target as HTMLSelectElement).value || ''"
                    >
                        <option value="">None — user completes onboarding</option>
                        <option v-for="preset in presets" :key="preset.id" :value="preset.id">
                            {{ preset.label }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Time off approval</label>
                    <select v-model="timeOffApproval" class="input">
                        <option value="site_default">Use team default</option>
                        <option value="required">Always require approval</option>
                        <option value="auto">Always auto-approve</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Applies across the site. Teams still control minimum staffing rules.</p>
                </div>

                <div class="space-y-3">
                    <div>
                        <h3 class="text-sm font-medium text-slate-300">Teams</h3>
                        <p class="text-xs text-slate-500 mt-1">Team membership controls rosters, minimum staffing, who can see this person’s time, and who can approve their time off.</p>
                    </div>

                    <div
                        v-for="team in teams"
                        :key="team.id"
                        class="rounded-lg border border-slate-800 bg-slate-900/50 p-4 space-y-3"
                    >
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-200">
                            <input
                                type="checkbox"
                                class="rounded border-slate-600 bg-slate-800 text-indigo-500"
                                :checked="membershipForTeam(team.id)?.isMember ?? false"
                                @change="toggleMembership(team.id, ($event.target as HTMLInputElement).checked)"
                            >
                            {{ team.name }}
                        </label>

                        <label
                            v-if="membershipForTeam(team.id)?.isMember"
                            class="flex items-center gap-2 text-sm text-slate-300"
                        >
                            <input
                                v-model="membershipForTeam(team.id)!.isScheduled"
                                type="checkbox"
                                class="rounded border-slate-600 bg-slate-800 text-indigo-500"
                            >
                            On schedule
                        </label>

                        <div
                            v-if="membershipForTeam(team.id)?.isMember"
                            class="grid gap-3 sm:grid-cols-2"
                        >
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1.5">Who can see their time</label>
                                <select
                                    v-model="membershipForTeam(team.id)!.scheduleVisibility"
                                    class="input text-sm"
                                >
                                    <option value="everyone">Everyone on site</option>
                                    <option value="team">Team members only</option>
                                    <option value="approvers">Team approvers only</option>
                                    <option value="private">Only site admins</option>
                                </select>
                            </div>

                            <label class="flex items-end gap-2 text-sm text-slate-300 pb-2">
                                <input
                                    v-model="membershipForTeam(team.id)!.isTimeOffApprover"
                                    type="checkbox"
                                    class="rounded border-slate-600 bg-slate-800 text-indigo-500"
                                >
                                Can approve time off for this team
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        {{ isEditing ? 'New password (optional)' : 'Password' }}
                    </label>
                    <input v-model="password" type="password" class="input" :required="!isEditing">
                </div>

                <div v-if="password || !isEditing">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Confirm password</label>
                    <input v-model="passwordConfirmation" type="password" class="input" :required="!isEditing || !!password">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <Button class="flex-1" :disabled="submitting" @click="submit">
                    {{ isEditing ? 'Save changes' : 'Create user' }}
                </Button>
                <Link href="/manage/users" class="text-sm text-slate-400 hover:text-slate-300">
                    Cancel
                </Link>
            </div>

            <div v-if="isEditing" class="pt-4 border-t border-slate-800">
                <Button variant="danger" size="sm" @click="destroy">
                    Remove user
                </Button>
            </div>
        </div>

        <Toast v-if="toast" :type="toast.type" :message="toast.message" />
    </AppLayout>
</template>
