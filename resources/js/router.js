import { createRouter, createWebHistory } from "vue-router";
import store from "./store/index";

import Chat from "./Pages/Chat.vue";
import Login from "./Authentification/Login.vue";
import Register from "./Authentification/Register.vue";
import ForgotPassword from "./Authentification/ForgotPassword.vue";
import ResetPassword from "./Authentification/ResetPassword.vue";

import Superadmin from "./Superadmin.vue";
import Admin from "./Admin.vue";
import User from "./User.vue";

import DashboardAdmin from "./Pages/DashboardAdmin.vue";
import Dashboard from "./Pages/Dashboard.vue";
import AdminsList from "./Pages/AdminsList.vue";
import SamplesUsersList from "./Pages/SamplesUsersList.vue";

import Profile from "./Pages/Profile.vue";
import UserAccount from "./Components/UserAccount.vue";
import AccountSettings from "./Components/AccountSettings.vue";
import DeleteAccount from "./Components/DeleteAccount.vue";

import ContractCategoriesList from "./Pages/ContractCategoriesList.vue";
import CustomizedButton from "./Components/CustomizedButton.vue";
import CreateGabarit from "./Components/CreateGabarit.vue";
import UpdateGabarit from "./Components/UpdateGabarit.vue";
import GabaritsList from "./Pages/GabaritsList.vue";
import CreateContractModel from "./Components/CreateContractModel.vue";
import ContractModelsList from "./Pages/ContractModelsList.vue";

import Home from "./Pages/Home.vue";
import GenerateContract from "./Components/GenerateContract.vue";
import ContractsList from "./Pages/ContractsList.vue";
import SignatureContract from "./Components/SignatureContract.vue";
import ContractSendForm from "./Components/ContractSendForm.vue";

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
        path: "/chat",
        name: "Chat",
        component: Chat,
        /*meta: {
            requiresAuth: false,
        },*/
    },

    {
        path: "/admin",
        component: Admin,
        children: [
            {
                path: "dashboardAdmin",
                name: "Dashboard Admin",
                component: DashboardAdmin,
            },
            {
                path: "categories",
                name: "Contract Categories ",
                component: ContractCategoriesList,
            },
            {
                path: "customizedButton",
                component: CustomizedButton,
            },
            {
                path: "gabarits/Create",
                name: "Create Gabarit",
                component: CreateGabarit,
            },
            {
                path: "gabarits/Update",
                name: "Update Gabarit",
                component: UpdateGabarit,
            },
            {
                path: "gabarits",
                name: "Gabarits",
                component: GabaritsList,
            },

            {
                path: "models/create",
                name: "Create Contract Model",
                component: CreateContractModel,
            },
            {
                path: "models",
                name: "Contract Models",
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

            { path: "admins", name: "Admins ", component: AdminsList },
            {
                path: "users",
                name: "Samples Users",
                component: SamplesUsersList,
            },
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
            {
                path: "home",
                name: "Home",
                component: Home,
            },
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
            {
                path: "generateContract/:modelId",
                name: "generateContract",
                component: GenerateContract,
                props: true,
            },
            {
                path: "contracts",
                name: "Contracts",
                component: ContractsList,
                props: true,
            },
            {
                path: "contracts/sign",
                name: "Signature",
                component: SignatureContract,
            },
            {
                path: "contracts/sendForm",
                name: "ContractSendForm",
                component: ContractSendForm,
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
