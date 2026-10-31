import Brochure from './Brochure'
import Auth from './Auth'
import User from './User'
import Site from './Site'
import Admin from './Admin'

const Controllers = {
    Brochure: Object.assign(Brochure, Brochure),
    Auth: Object.assign(Auth, Auth),
    User: Object.assign(User, User),
    Site: Object.assign(Site, Site),
    Admin: Object.assign(Admin, Admin),
}

export default Controllers