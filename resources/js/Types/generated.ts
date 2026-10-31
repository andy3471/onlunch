export type HomePageData = {
timeBlocks: Array<TimeBlockData>;
workingHours: WorkingHoursData;
myLunchBooking: TimeBlockData | null;
myTaskAssignments: Array<TimeBlockData>;
selectedDate: string;
tasks: Array<TaskOptionData>;
myWorkingHours: WorkingHourPresetData | null;
scheduleUsers: Array<ScheduleUserData>;
};
export type PendingTimeOffRequestData = {
id: string;
userName: string;
teamName: string;
date: string | null;
startTime: string | null;
endTime: string | null;
notes: string | null;
};
export type ScheduleUserData = {
userId: string;
userName: string;
};
export type ScheduleVisibility = 'everyone' | 'team' | 'approvers' | 'private';
export type TaskOptionData = {
id: string;
name: string;
color: string | null;
};
export type TeamFilterOptionData = {
id: string;
name: string;
};
export type TeamRowData = {
id: string;
name: string;
minimumAvailableStaff: number | null;
tasksEnabled: boolean;
};
export type TimeBlockData = {
id: string;
type: string;
userId: string;
userName: string;
date: string;
startTime: string;
endTime: string;
taskName: string | null;
taskColor: string | null;
status: string | null;
notes: string | null;
assignmentId: string | null;
taskId: string | null;
lunchBookingId: string | null;
};
export type TimeOffApprovalMode = 'site_default' | 'required' | 'auto';
export type TimeOffRequestStatus = 'pending' | 'approved' | 'rejected';
export type UserRowData = {
id: string;
name: string;
email: string;
isAdmin: boolean;
teamNames: Array<any>;
isScheduled: boolean;
};
export type WorkingHourPresetData = {
id: string;
name: string;
startTime: string;
endTime: string;
label: string;
};
export type WorkingHoursData = {
start: string;
end: string;
};
