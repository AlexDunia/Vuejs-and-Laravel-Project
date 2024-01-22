import Dashboard from '../components/Dashboard.vue';
import Appointments from '../components/Appointments.vue';
import Users from '../components/Users.vue';
import Settings from '../components/Settings.vue';
import Profiles from '../components/Profiles.vue';

export default [
    {
        path:'/admin/dashboard',
        name:'admin.dashboard',
        component: Dashboard,
    },
    {
        path:'/admin/appointments',
        name:'admin.appointments',
        component: Appointments,
    },
    {
        path:'/admin/users',
        name:'admin.users',
        component: Users,
    },
    {
        path:'/admin/settings',
        name:'admin.settings',
        component: Settings,
    },
    {
        path:'/admin/profiles',
        name:'admin.profiles',
        component: Profiles,
    }
]

