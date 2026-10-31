import OnboardingController from './OnboardingController'
import HomeController from './HomeController'
import LunchBookingController from './LunchBookingController'
import TimeOffRequestController from './TimeOffRequestController'
import TaskAssignmentController from './TaskAssignmentController'

const Site = {
    OnboardingController: Object.assign(OnboardingController, OnboardingController),
    HomeController: Object.assign(HomeController, HomeController),
    LunchBookingController: Object.assign(LunchBookingController, LunchBookingController),
    TimeOffRequestController: Object.assign(TimeOffRequestController, TimeOffRequestController),
    TaskAssignmentController: Object.assign(TaskAssignmentController, TaskAssignmentController),
}

export default Site