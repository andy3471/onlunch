import timeOffRequests from './time-off-requests'
import teams from './teams'
import users from './users'
import tasks from './tasks'
import settings from './settings'

const manage = {
    timeOffRequests: Object.assign(timeOffRequests, timeOffRequests),
    teams: Object.assign(teams, teams),
    users: Object.assign(users, users),
    tasks: Object.assign(tasks, tasks),
    settings: Object.assign(settings, settings),
}

export default manage