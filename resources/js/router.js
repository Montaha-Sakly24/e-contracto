import { createRouter, createWebHistory } from "vue-router";
import store from "./store/index";

import Page404 from "./Components/Page404.vue";
import Login from "./Authentification/Login.vue";
import Register from "./Authentification/Register.vue";
import ForgotPassword from "./Authentification/ForgotPassword.vue";
import ResetPassword from "./Authentification/ResetPassword.vue";
import Superadmin from "./Superadmin.vue";
import Admin from "./Admin.vue";
import User from "./User.vue";
import sampleUsers from "./Pages/sampleUsers.vue";
import DashboardAdmin from "./Pages/DashboardAdmin.vue";
import Dashboard from "./Pages/Dashboard.vue";
import AdminsList from "./Pages/AdminsList.vue";
import SamplesUsersList from "./Pages/SamplesUsersList.vue";
import Profile from "./Pages/Profile.vue";
import UserAccount from "./Components/UserAccount.vue";
import AccountSettings from "./Components/AccountSettings.vue";
import DeleteAccount from "./Components/DeleteAccount.vue";
import ContractCategoriesList from "./Pages/ContractCategoriesList.vue";
import PlaceholdersList from "./Pages/PlaceholdersList.vue";

import CreateGabarit from "./Components/CreateGabarit.vue";
import UpdateGabarit from "./Components/UpdateGabarit.vue";
import GabaritsList from "./Pages/GabaritsList.vue";
import EditContractModel from "./Components/EditContractModel.vue";
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
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/forgotpassword",
        name: "Forgot Password",
        component: ForgotPassword,
        meta: {
            requiresAuth: false,
        },
    },
    {
        path: "/resetpassword",
        name: "Reset Password",
        component: ResetPassword,
        meta: {
            requiresAuth: false,
        },
    },

    {
        path: "/notfound",
        name: "Page404",
        component: Page404,
        meta: {
            requiresAuth: false,
            parent: "404",
        },
    },

    {
        path: "/superadmin",
        component: Superadmin,
        children: [
            {
                path: "dashboard",
                name: "SuperadminDashboard",
                component: Dashboard,
            },
            { path: "admins", name: "SuperadminAdmins", component: AdminsList },
            {
                path: "users",
                name: "SuperadminSamplesUsers",
                component: SamplesUsersList,
            },
            {
                path: "categories",
                name: "SuperadminContractCategories",
                component: ContractCategoriesList,
            },
            {
                path: "placeholders",
                name: "SuperadminPlaceholders",
                component: PlaceholdersList,
            },
            {
                path: "gabarits/Create",
                name: "SuperadminCreateGabarit",
                component: CreateGabarit,
            },
            {
                path: "gabarits/Update",
                name: "SuperadminUpdateGabarit",
                component: UpdateGabarit,
            },
            {
                path: "gabarits",
                name: "SuperadminGabarits",
                component: GabaritsList,
            },
            {
                path: "models/create",
                name: "SuperadminCreateContractModel",
                component: CreateContractModel,
            },
            {
                path: "models/edit/:modelId",
                name: "SuperadminEditContractModel",
                component: EditContractModel,
            },
            {
                path: "models",
                name: "SuperadminContractModels",
                component: ContractModelsList,
            },
        ],
        meta: {
            requiresAuth: true,
            parent: "Superadmin",
        },
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
            { path: "profile", name: "UserProfile", component: Profile },
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
                name: "GenerateContract",
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
                name: "SignatureContract",
                component: SignatureContract,
            },
            {
                path: "contracts/sendForm",
                name: "ContractSendForm",
                component: ContractSendForm,
            },
        ],
        meta: {
            requiresAuth: true,
            parent: "Simple User",
        },
    },
    {
        path: "/admin",
        component: Admin,
        children: [
            {
                path: "dashboardAdmin",
                name: "AdminDashboard",
                component: DashboardAdmin,
            },
            { path: "profile", name: "AdminProfile", component: Profile },
            {
                path: "users",
                name: "AdminSamplesUsers",
                component: sampleUsers,
            },
            {
                path: "categories",
                name: "AdminContractCategories",
                component: ContractCategoriesList,
            },
            {
                path: "placeholders",
                name: "AdminPlaceholders",
                component: PlaceholdersList,
            },
            {
                path: "gabarits/Create",
                name: "AdminCreateGabarit",
                component: CreateGabarit,
            },
            {
                path: "gabarits/Update",
                name: "AdminUpdateGabarit",
                component: UpdateGabarit,
            },
            {
                path: "gabarits",
                name: "AdminGabarits",
                component: GabaritsList,
            },
            {
                path: "models/create",
                name: "AdminCreateContractModel",
                component: CreateContractModel,
            },
            {
                path: "models/edit/:modelId",
                name: "AdminEditContractModel",
                component: EditContractModel,
            },
            {
                path: "models",
                name: "AdminContractModels",
                component: ContractModelsList,
            },
        ],
        meta: {
            requiresAuth: true,
            parent: "Admin",
        },
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// router.beforeEach(async (to, from, next) => {
//     if (!localStorage.getItem("token") && !to.meta.requiresAuth) {
//         next();
//     } else {
//         if (
//             to.name != "Page404" &&
//             to.meta.parent != localStorage.getItem("role") &&
//             to.name != "Login"
//         ) {
//             console.log("2");

//             next({ name: "Page404" });
//         } else {
//             if (to.name == "Login" || to.name == "Register") {
//                 next({ name: "Page404" });
//             } else {
//                 next();
//             }
//         }
//     }
// });

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem("token");
    const role = localStorage.getItem("role");

    if (!token && !to.meta.requiresAuth) {
        // Proceed if no authentication is required and no token is present
        next();
    } else if (!token && to.meta.requiresAuth) {
        // Redirect to login if authentication is required but no token is present
        next({ name: "Login" });
    } else if (
        to.name != "Page404" &&
        to.meta.parent != role &&
        to.name != "Login"
    ) {
        // Redirect to Page404 if the user's role does not match the required role
        console.log("Role mismatch");
        next({ name: "Page404" });
    } else if (
        role &&
        (to.name == "Login" ||
            to.name == "Register" ||
            to.name == "Forgot Password")
    ) {
        // Redirect to Page404 if trying to access Login or Register while authenticated
        console.log("Access denied");
        next({ name: "Page404" });
    } else {
        // Proceed with the navigation
        next();
    }
});

export default router;
