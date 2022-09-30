import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import firstPage from './components/pages/myfirstpage'
import secondPage from './components/pages/mysecondpage'
import hooks from './components/pages/Basic/hooks'
import methods from './components/pages/Basic/methods'
//admin project Pages
import home from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import usecom from './vuex/usecom'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'
import createBlog from './admin/pages/createBlog'
import blogs from './admin/pages/blogs'
import editblog from './admin/pages/editBlog'
import notfound from './admin/pages/notfound'
const routes = [{
        // projects routes....
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/tags',
        component: tags,
        name: 'tags'
    },
    {
        path: '/category',
        component: category,
        name: 'category'
    },
    {
        path: '/adminusers',
        component: adminusers,
        name: 'adminusers'
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },
    {
        path: '/role',
        component: role,
        name: 'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name: 'assignRole'
    },
    {
        path: '/createBlog',
        component: createBlog,
        name: 'createBlog'
    },
    {
        path: '/blogs',
        component: blogs,
        name: 'blogs'
    },
    {
        path: '/editblog/:id',
        component: editblog,
        name: 'editblog'
    },
    {
        path: '*',
        component: notfound,
        name: 'notfound'
    },

    {
        path: '/usevuex',
        component: usecom
    },
    {
        path: '/my-new-vue-route',
        component: firstPage
    },
    // basic tutorial routes....
    {
        path: '/second-route',
        component: secondPage
    },
    // hooks routes... 
    {
        path: '/hooks',
        component: hooks
    },
    // methods routes...
    {
        path: '/methods',
        component: methods
    }
]

export default new Router({
    mode: 'history', //For remove hashtag from URL
    routes
})