import PasswordController from './PasswordController'
import SettingsController from './SettingsController'

const User = {
    PasswordController: Object.assign(PasswordController, PasswordController),
    SettingsController: Object.assign(SettingsController, SettingsController),
}

export default User