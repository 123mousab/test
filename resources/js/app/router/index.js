import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router);


global.router = new Router({
    mode: 'history',
    scrollBehavior() {
        return window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    routes: [
        {
            path: '/',
            component: () => import('../view/layout/seller/index'),
            children: [
                {
                    path: 'noPermission',
                    name: 'noPermission',
                    meta: {
                        role: '',
                    },
                    component: () => import('../view/pages/admin/noPermission/noPermissionPage.vue'),
                },
                {
                    path: 'recipies',
                    name: 'recipies',
                    meta: {
                        role: 'store',
                    },
                    component: () => import('../view/pages/admin/recipies/recipiesPage.vue'),
                },
                {
                    path: 'recipies/addEdit/:id?',
                    name: 'recipiesAddEdit',
                    meta: {
                        role: 'store',
                    },
                    component: () => import('../view/pages/admin/recipies/recipiesAddEditPage.vue'),
                },
                {
                    path: 'ingredients',
                    name: 'ingredients',
                    meta: {
                        role: 'store',
                    },
                    component: () => import('../view/pages/admin/ingredients/ingredientsPage.vue'),
                },
                {
                    path: 'ingredients/addEdit/:id?',
                    name: 'ingredientsAddEdit',
                    meta: {
                        role: 'store',
                    },
                    component: () => import('../view/pages/admin/ingredients/ingredientsAddEditPage.vue'),
                },
                {
                    path: 'kitchens',
                    name: 'kitchens',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/kitchens/kitchensPage.vue'),
                },
                {
                    path: 'kitchens/addEdit/:id?',
                    name: 'kitchensAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/kitchens/kitchensAddEditPage.vue'),
                },
                {
                    path: 'menus',
                    name: 'store',
                    meta: {
                        role: 'store',
                    },
                    component: () => import('../view/pages/admin/new_menus/menusPage.vue'),
                },
                {
                    path: 'menus/addEdit/:id?',
                    name: 'menusAddEdit',
                    meta: {
                        role: 'store',
                    },
                    component: () => import('../view/pages/admin/new_menus/menusAddEditPage.vue'),
                },
                {
                    path: 'groups',
                    name: 'groups',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/groups/groupsPage.vue'),
                },
                {
                    path: 'groups/addEdit/:id?',
                    name: 'groupsAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/groups/addEdit.vue'),
                },
                {
                    path: 'countries',
                    name: 'countries',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/countries/countriesPage.vue'),
                },
                {
                    path: 'countries/addEdit/:id?',
                    name: 'countriesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/countries/addEdit.vue'),
                },
                {
                    path: 'cities',
                    name: 'cities',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/cities/citiesPage.vue'),
                },
                {
                    path: 'cities/addEdit/:id?',
                    name: 'citiesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/cities/addEdit.vue'),
                },
                {
                    path: 'branches',
                    name: 'branches',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/branches/branchesPage.vue'),
                },
                {
                    path: 'branches/addEdit/:id?',
                    name: 'branchesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/branches/addEdit.vue'),
                },
                {
                    path: 'periods',
                    name: 'periods',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/periods/periodsPage.vue'),
                },
                {
                    path: 'periods/addEdit/:id?',
                    name: 'periodsAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/periods/addEdit.vue'),
                },
                {
                    path: 'units',
                    name: 'units',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/units/unitsPage.vue'),
                },
                {
                    path: 'units/addEdit/:id?',
                    name: 'unitsAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/units/addEdit.vue'),
                },
                {
                    path: 'company',
                    name: 'company',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/company/companyNamesPage.vue'),
                },
                {
                    path: 'company/addEdit/:id?',
                    name: 'companyAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/company/addEdit.vue'),
                },
                {
                    path: 'cuisines',
                    name: 'cuisines',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/cuisines/cuisinesPage.vue'),
                },
                {
                    path: 'cuisines/addEdit/:id?',
                    name: 'cuisinesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/cuisines/addEdit.vue'),
                },
                {
                    path: 'divisions',
                    name: 'divisions',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/divisions/divisionsPage.vue'),
                },
                {
                    path: 'divisions/addEdit/:id?',
                    name: 'divisionsAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/divisions/addEdit.vue'),
                },
                {
                    path: 'tools',
                    name: 'tools',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/tools/toolsPage.vue'),
                },
                {
                    path: 'tools/addEdit/:id?',
                    name: 'toolsAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/tools/addEdit.vue'),
                },
                {
                    path: 'bank_names',
                    name: 'bank_names',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/bank_names/bankNamesPage.vue'),
                },
                {
                    path: 'bank_names/addEdit/:id?',
                    name: 'bank_namesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/bank_names/addEdit.vue'),
                },
                {
                    path: 'group_names',
                    name: 'group_names',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/group_names/groupNamesPage.vue'),
                },
                {
                    path: 'group_names/addEdit/:id?',
                    name: 'group_namesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/group_names/addEdit.vue'),
                },
                {
                    path: 'nutrition_facts',
                    name: 'nutrition_facts',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/nutrition_facts/nutrition_factsPage.vue'),
                },
                {
                    path: 'nutrition_facts/addEdit/:id?',
                    name: 'nutrition_factsAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/nutrition_facts/addEdit.vue'),
                },
                {
                    path: 'subscribe',
                    name: 'subscribe',
                    meta: {
                        role: 'orders',
                    },
                    component: () => import('../view/pages/admin/subscribe/subscribePage.vue'),
                },
                {
                    path: 'subscribe/addEdit/:id?',
                    name: 'subscribeAddEdit',
                    meta: {
                        role: 'orders',
                    },
                    component: () => import('../view/pages/admin/subscribe/subscribeAddEditPage.vue'),
                },
                {
                    path: 'packages',
                    name: 'packages',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/packages/packagesPage.vue'),
                },
                {
                    path: 'packages/addEdit/:id?',
                    name: 'packagesAddEdit',
                    meta: {
                        role: 'constant',
                    },
                    component: () => import('../view/pages/admin/packages/packagesAddEditPage.vue'),
                },
                {
                    path: 'users',
                    name: 'users',
                    meta: {
                        role: 'users',
                    },
                    component: () => import('../view/pages/admin/users/usersPage.vue'),
                },
                // {
                //     path: 'roles',
                //     name: 'roles',
                //     meta: {
                //         role: 'users',
                //     },
                //     component: () => import('../view/pages/admin/roles/rolesPage.vue'),
                // },
            ]
        },

        {
            path: '/reports/',
            component: () => import('../view/layout/reports/index'),
            children: [
                {
                    path: 'cookingToday',
                    name: 'cookingTodayReport',
                    meta: {
                        role: 'reports',
                    },
                    component: () => import('../view/pages/admin/subscribe/reports/cookingToday.vue'),
                },
                {
                    path: 'delegateToday',
                    name: 'delegateTodayReport',
                    meta: {
                        role: 'reports',
                    },
                    component: () => import('../view/pages/admin/subscribe/reports/delegateToday.vue'),
                },
                {
                    path: 'quantitiesToday',
                    name: 'quantitiesTodayReport',
                    meta: {
                        role: 'reports',
                    },
                    component: () => import('../view/pages/admin/subscribe/reports/quantitiesToday.vue'),
                },
            ],
        },
    ]


}
)

export default router;


