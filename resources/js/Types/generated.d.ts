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
export type ScheduleUserData = {
userId: string;
userName: string;
};
export type TaskOptionData = {
id: string;
name: string;
color: string | null;
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
export type TimeOffRequestStatus = 'pending' | 'approved' | 'rejected';
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
