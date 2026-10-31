import HomeController from './HomeController'
import SiteRegistrationController from './SiteRegistrationController'

const Brochure = {
    HomeController: Object.assign(HomeController, HomeController),
    SiteRegistrationController: Object.assign(SiteRegistrationController, SiteRegistrationController),
}

export default Brochure