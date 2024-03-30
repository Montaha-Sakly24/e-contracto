import { createRouter, createWebHistory } from "vue-router";
import store from "./store/index";

import Login from "./Authentification/Login.vue";
import Register from "./Authentification/Register.vue";
import ForgotPassword from "./Authentification/ForgotPassword.vue";
import ResetPassword from "./Authentification/ResetPassword.vue";

import Superadmin from "./Superadmin.vue";
import Admin from "./Admin.vue";
import User from "./User.vue";

import Home from "./Superadmin/Pages/Home.vue";
import Dashboard from "./Superadmin/Pages/Dashboard.vue";
import AdminsList from "./Superadmin/Pages/AdminsList.vue";
import UsersList from "./Superadmin/Pages/UsersList.vue";

import Profile from "./CommunPages/Pages/Profile.vue";
import UserAccount from "./CommunPages/Components/UserAccount.vue";
import AccountSettings from "./CommunPages/Components/AccountSettings.vue";
import DeleteAccount from "./CommunPages/Components/DeleteAccount.vue";

import ContractCategoriesList from "./CommunPages/Pages/ContractCategoriesList.vue";
import ContractModelsList from "./CommunPages/Pages/ContractModelsList.vue";

const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,
        /*meta: {
            requiresAuth: false,
        },*/
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        /*meta: {
            requiresAuth: false,
        },*/
    },
    {
        path: "/forgotpassword",
        name: "Forgot Password",
        component: ForgotPassword,
        /*meta: {
            requiresAuth: false,
        },*/
    },
    {
        path: "/resetpassword",
        name: "Reset Password",
        component: ResetPassword,
        /*meta: {
            requiresAuth: false,
        },*/
    },

    {
        path: "/admin",
        component: Admin,
        children: [
            { path: "", name: "Home", component: Home },
            {
                path: "categories",
                name: "Contract Categories List",
                component: ContractCategoriesList,
            },
            {
                path: "models",
                name: "Contract Models List",
                component: ContractModelsList,
            },
        ],
        /*meta: {
            requiresAuth: true,
        },*/
    },
    {
        path: "/superadmin",
        component: Superadmin,
        children: [
            {
                path: "dashboard",
                name: "Dashboard",
                component: Dashboard,
            },

            { path: "admins", name: "Admins List", component: AdminsList },
            { path: "users", name: "Users List", component: UsersList },
        ],
        /*meta: {
            requiresAuth: true,
        },*/
    },

    {
        path: "/user",
        name: "User",
        component: User,
        children: [
            { path: "profile", name: "Profile", component: Profile },
            {
                path: "profile/account",
                name: "UserAccount",
                component: UserAccount,
            },
            {
                path: "profile/account/settings",
                name: "AccountSettings",
                component: AccountSettings,
            },
            {
                path: "profile/account/delete",
                name: "DeleteAccount",
                component: DeleteAccount,
            },
        ],
        /*meta: {
            requiresAuth: true,
        },*/
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

/*router.beforeEach((to, from) => {
    if (to.meta.requiresAuth && store.getters.getToken == 0) {
        return { name: "Login" };
    }
    if (to.meta.requiresAuth == false && store.getters.getToken != 0) {
        return { name: "Dashboard" };
    }
});*/

export default router;
