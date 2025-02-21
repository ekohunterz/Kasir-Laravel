<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Layouts/Authenticated/Breadcrumb.vue";
import { CurrencyDollarIcon, ShoppingBagIcon } from "@heroicons/vue/24/outline";
import ProductIcon from "@/Components/Icons/ProductIcon.vue";
import currencyFormat from "@/Helpers/CurrencyFormat.js";
import StatsCard from "../Components/StatsCard.vue";
import Table from "@/Components/Table.vue";

const props = defineProps({
    userCount: Number,
    roleCount: Number,
    permissionCount: Number,
    productsSold: Object,
    income: Object,
    topSellingProducts: Array,
    transactions: Object,
    productsStock: Object,
});

console.log(props.productsSold);
</script>

<template>
    <AppLayout :title="lang().label.dashboard">
        <template #title>
            <span>{{ lang().label.dashboard }}</span>
        </template>
        <template #breadcrumb>
            <Breadcrumb />
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6 w-full">
                    <StatsCard
                        :variant="props.productsSold.status"
                        :value="props.productsSold.today"
                        :percentage="props.productsSold.percentage_change"
                    >
                        <template #icon
                            ><ProductIcon class="h-12 w-12" />
                        </template>
                        <template #desc>Products sold today</template>
                    </StatsCard>
                    <StatsCard
                        color="bg-sky-500"
                        :variant="props.income.status"
                        :value="currencyFormat(props.income.today)"
                        :percentage="props.income.percentage_change"
                    >
                        <template #icon
                            ><CurrencyDollarIcon class="h-12 w-12" />
                        </template>
                        <template #desc>Incomes today</template>
                    </StatsCard>
                    <StatsCard
                        color="bg-orange-500"
                        :variant="props.transactions.status"
                        :value="props.transactions.today"
                        :percentage="props.transactions.percentage_change"
                    >
                        <template #icon
                            ><ShoppingBagIcon class="h-12 w-12" />
                        </template>
                        <template #desc>Transactions today</template>
                    </StatsCard>
                </div>

                <div class="mt-6 flex flex-wrap gap-6">
                    <div
                        class="bg-white card dark:bg-slate-800 overflow-hidden shadow sm:rounded"
                    >
                        <Table>
                            <template #table-action>
                                <h2 class="card-title">Top Selling Products</h2>
                            </template>
                            <template #table-head>
                                <tr>
                                    <th class="p-4">Image</th>
                                    <th class="p-4">Product Name</th>
                                    <th class="p-4">Quantity</th>
                                </tr>
                            </template>
                            <template #table-body>
                                <tr
                                    v-for="product in props.topSellingProducts"
                                    class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-200/30 hover:dark:bg-slate-900/20"
                                >
                                    <td
                                        class="whitespace-nowrap mx-auto px-4 py-2 avatar"
                                    >
                                        <div class="w-12 rounded">
                                            <img
                                                :src="product.image"
                                                :alt="product.name"
                                            />
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        {{ product.name }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-center"
                                    >
                                        {{ product.quantity }}
                                    </td>
                                </tr>
                            </template>
                        </Table>
                    </div>
                    <div
                        class="bg-white card dark:bg-slate-800 overflow-hidden shadow sm:rounded"
                    >
                        <Table>
                            <template #table-action>
                                <h2 class="card-title">Stock Status</h2>
                            </template>
                            <template #table-head>
                                <tr>
                                    <th class="p-4">Image</th>
                                    <th class="p-4">Product Name</th>
                                    <th class="p-4">Stock</th>
                                    <th class="p-4">Status</th>
                                </tr>
                            </template>
                            <template #table-body>
                                <tr
                                    v-for="product in props.productsStock"
                                    class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-200/30 hover:dark:bg-slate-900/20"
                                >
                                    <td
                                        class="whitespace-nowrap mx-auto px-4 py-2 avatar"
                                    >
                                        <div class="w-12 rounded">
                                            <img
                                                :src="product.image"
                                                :alt="product.name"
                                            />
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        {{ product.name }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-center"
                                    >
                                        {{ product.quantity }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-4 py-2 text-center"
                                    >
                                        <span
                                            class="badge badge-success text-white"
                                            v-if="product.quantity > 10"
                                        >
                                            In Stock
                                        </span>
                                        <span
                                            class="badge badge-warning text-white"
                                            v-else-if="
                                                product.quantity > 0 &&
                                                product.quantity < 10
                                            "
                                        >
                                            Low Stock</span
                                        >
                                        <span
                                            class="badge badge-error text-white"
                                            v-else
                                        >
                                            Out of Stock</span
                                        >
                                    </td>
                                </tr>
                            </template>
                        </Table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
