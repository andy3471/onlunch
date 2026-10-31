import TimeOffRequestController from './TimeOffRequestController'
import TeamController from './TeamController'
import UserController from './UserController'
import TaskController from './TaskController'
import SiteSettingsController from './SiteSettingsController'

const Admin = {
    TimeOffRequestController: Object.assign(TimeOffRequestController, TimeOffRequestController),
    TeamController: Object.assign(TeamController, TeamController),
    UserController: Object.assign(UserController, UserController),
    TaskController: Object.assign(TaskController, TaskController),
    SiteSettingsController: Object.assign(SiteSettingsController, SiteSettingsController),
}

export default Admin