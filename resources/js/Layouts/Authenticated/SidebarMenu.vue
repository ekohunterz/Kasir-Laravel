<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {
    ClockIcon,
    Cog6ToothIcon,
    CurrencyDollarIcon,
    KeyIcon,
    ShieldCheckIcon,
    ShoppingCartIcon,
    Square3Stack3DIcon,
    Squares2X2Icon,
    UserGroupIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="text-white">
        <div
            class="flex justify-center items-center border-b border-white/10 h-16 max-w-full px-3"
        >
            <Link
                :href="route('dashboard')"
                class="flex w-full justify-start items-center space-x-4"
            >
                <ApplicationLogo class="block h-8 w-auto" />
                <p class="block text-lg font-semibold truncate">
                    {{ $page.props.app.setting.name }}
                </p>
            </Link>
        </div>
        <ol class="pb-24 pt-6 menu menu-lg">
            <li
                v-bind:class="
                    route().current('dashboard')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('dashboard')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <Squares2X2Icon class="w-5 h-auto" />
                    <span>{{ lang().label.dashboard }}</span>
                </Link>
            </li>
            <li>
                <span class="font-md uppercase font-semibold px-3">{{
                    lang().label.main_menu
                }}</span>
            </li>

            <li
                v-bind:class="
                    route().current('order.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('order.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <ShoppingCartIcon class="w-5 h-auto" />
                    <span>{{ lang().label.order }}</span>
                </Link>
            </li>
            <li
                v-bind:class="
                    route().current('income.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('income.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <CurrencyDollarIcon class="w-5 h-auto" />
                    <span>{{ lang().label.income }}</span>
                </Link>
            </li>
            <li>
                <span class="font-md uppercase font-semibold px-3"
                    >Master Data</span
                >
            </li>

            <li
                v-bind:class="
                    route().current('product.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('product.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="2048"
                        height="2048"
                        viewBox="0 0 2048 2048"
                        class="w-5 h-auto"
                    >
                        <path
                            fill="currentColor"
                            d="m960 120l832 416v1040l-832 415l-832-415V536zm625 456L960 264L719 384l621 314zM960 888l238-118l-622-314l-241 120zM256 680v816l640 320v-816zm768 1136l640-320V680l-640 320z"
                        />
                    </svg>
                    <span>{{ lang().label.product }}</span>
                </Link>
            </li>

            <li
                v-bind:class="
                    route().current('category.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('category.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <Square3Stack3DIcon class="w-5 h-auto" />
                    <span>{{ lang().label.category }}</span>
                </Link>
            </li>
            <li
                v-bind:class="
                    route().current('customer.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('customer.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <UserGroupIcon class="w-5 h-auto" />
                    <span>{{ lang().label.customer }}</span>
                </Link>
            </li>
            <li v-show="can(['user read', 'role read', 'permission read'])">
                <span class="font-md uppercase font-semibold px-3">{{
                    lang().label.access
                }}</span>
            </li>
            <li
                v-show="can(['user read'])"
                v-bind:class="
                    route().current('user.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('user.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <UserIcon class="w-5 h-auto" />
                    <span>{{ lang().label.user }}</span>
                </Link>
            </li>
            <li
                v-show="can(['role read'])"
                v-bind:class="
                    route().current('role.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('role.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <KeyIcon class="w-5 h-auto" />
                    <span>{{ lang().label.role }}</span>
                </Link>
            </li>
            <li
                v-show="can(['permission read'])"
                v-bind:class="
                    route().current('permission.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('permission.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <ShieldCheckIcon class="w-5 h-auto" />
                    <span>{{ lang().label.permission }}</span>
                </Link>
            </li>
            <li v-show="can(['setting read'])">
                <span class="font-md uppercase font-semibold px-3">{{
                    lang().label.setting
                }}</span>
            </li>
            <li
                v-show="can(['setting read'])"
                v-bind:class="
                    route().current('setting.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('setting.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <Cog6ToothIcon class="w-5 h-auto" />
                    <span>{{ lang().label.setting }}</span>
                </Link>
            </li>
            <li
                v-show="can(['activity read'])"
                v-bind:class="
                    route().current('activity.index')
                        ? 'border-l-4 border-white font-semibold bg-white/20 dark:bg-primary/30'
                        : ''
                "
                class="hover:bg-white/20 dark:hover:bg-primary/30"
            >
                <Link
                    :href="route('activity.index')"
                    class="flex items-center py-1.5 px-3 space-x-2"
                >
                    <ClockIcon class="w-5 h-auto" />
                    <span>{{ lang().label.activity }}</span>
                </Link>
            </li>
        </ol>
    </div>
</template>
