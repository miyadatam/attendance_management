require('./bootstrap');

window.Vue = require('vue');

import Header from './components/layouts/Header.vue'
import Sidebar from './components/layouts/Sidebar.vue'
import Footer from './components/layouts/Footer.vue'
import UserShow from './components/users/Show.vue'
import UserEdit from './components/users/Edit.vue'
import AttendanceShow from './components/attendances/Show.vue'
import ShiftShow from './components/shifts/Show.vue'
import ShiftCreate from './components/shifts/Create.vue'
import ShiftEdit from './components/shifts/Edit.vue'
import WorkStampStamping from './components/workstamps/Stamping.vue'
import WorkStampShow from './components/workstamps/Show.vue'
import WorkStampCreate from './components/workstamps/Create.vue'
import WorkStampEdit from './components/workstamps/Edit.vue'
import EarlyTimeShow from './components/earlytimes/Show.vue'
import EarlyTimeCreate from './components/earlytimes/Create.vue'
import EarlyTimeEdit from './components/earlytimes/Edit.vue'
import OverTimeShow from './components/overtimes/Show.vue'
import OverTimeCreate from './components/overtimes/Create.vue'
import OverTimeEdit from './components/overtimes/Edit.vue'

/**********
layouts
**********/
Vue.component('header-component', Header)
Vue.component('sidebar-component', Sidebar)
Vue.component('footer-component', Footer)

/**********
User
**********/
Vue.component('user-show', UserShow)
Vue.component('user-edit', UserEdit)

/**********
Attendance
**********/
Vue.component('attendance-show', AttendanceShow)

/**********
Shist
**********/
Vue.component('shift-show', ShiftShow)
Vue.component('shift-create', ShiftCreate)
Vue.component('shift-edit', ShiftEdit)

/**********
WorkStamp
**********/
Vue.component('work-stamp-stamping', WorkStampStamping)
Vue.component('work-stamp-show', WorkStampShow)
Vue.component('work-stamp-create', WorkStampCreate)
Vue.component('work-stamp-edit', WorkStampEdit)

/**********
EarlyTime
**********/
Vue.component('early-time-show', EarlyTimeShow);
Vue.component('early-time-create', EarlyTimeCreate);
Vue.component('early-time-edit', EarlyTimeEdit);

/**********
OverTime
**********/
Vue.component('over-time-show', OverTimeShow);
Vue.component('over-time-create', OverTimeCreate);
Vue.component('over-time-edit', OverTimeEdit);


const app = new Vue({}).$mount('#app')
