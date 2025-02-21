<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/Table.vue";
import Breadcrumb from "@/Layouts/Authenticated/Breadcrumb.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TablePagination from "@/Components/TablePagination.vue";
import Detail from "@/Pages/Income/Detail.vue";
import TextInput from "@/Components/TextInput.vue";
import ProductIcon from "@/Components/Icons/ProductIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Delete from "@/Pages/Income/Delete.vue";
import DeleteBulk from "@/Pages/Income/DeleteBulk.vue";
import { reactive, watch } from "vue";
import pkg from "lodash";
import { router } from "@inertiajs/vue3";
import {
    ChartPieIcon,
    ChevronUpDownIcon,
    CurrencyDollarIcon,
    ShoppingBagIcon,
    ArrowDownOnSquareIcon,
} from "@heroicons/vue/24/outline";
import Checkbox from "@/Components/Checkbox.vue";
import currencyFormat from "../../Helpers/CurrencyFormat";
import StatsCard2 from "../../Components/StatsCard2.vue";

const { _, debounce, pickBy } = pkg;
const props = defineProps({
    title: String,
    filters: Object,
    transactions: Object,
    breadcrumbs: Object,
    perPage: Number,
    total_income: Number,
    total_transaction: Number,
    products_sold: Number,
});

const data = reactive({
    params: {
        search: props.filters.search,
        field: props.filters.field,
        order: props.filters.order,
        perPage: props.perPage,
        date_start: props.filters.date_start,
        date_end: props.filters.date_end,
    },
    selectedId: [],
    multipleSelect: false,
    transaction: null,
});

const order = (field) => {
    data.params.field = field;
    data.params.order = data.params.order === "asc" ? "desc" : "asc";
};

watch(
    () => _.cloneDeep(data.params),
    debounce(() => {
        let params = pickBy(data.params);
        router.get(route("income.index"), params, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    }, 150)
);

const selectAll = (event) => {
    if (event.target.checked === false) {
        data.selectedId = [];
    } else {
        props.transactions?.data.forEach((transaction) => {
            data.selectedId.push(transaction.id);
        });
    }
};
const select = () => {
    if (props.transactions?.data.length == data.selectedId.length) {
        data.multipleSelect = true;
        asd;
    } else {
        data.multipleSelect = false;
    }
};

const exportIncome = () => {
    let params = pickBy(data.params);
    window.open(
        route("income.export", params),
        "_blank",
        "noopener,noreferrer"
    );
};
</script>

<template>
    <AppLayout :title="props.title">
        <template #title>
            <span>{{ props.title }}</span>
        </template>
        <template #breadcrumb>
            <Breadcrumb />
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4"
                >
                    <StatsCard2
                        :title="lang().label.income"
                        :value="currencyFormat(props.total_income)"
                        color="bg-green-500"
                    >
                        <template #icon>
                            <CurrencyDollarIcon
                                class="w-12 h-auto text-white"
                            />
                        </template>
                    </StatsCard2>

                    <StatsCard2
                        :title="lang().label.transaction"
                        :value="props.total_transaction"
                    >
                        <template #icon>
                            <ShoppingBagIcon class="w-12 h-auto text-white" />
                        </template>
                    </StatsCard2>

                    <StatsCard2
                        :title="lang().label.product_sold"
                        :value="props.products_sold"
                        color="bg-orange-500"
                    >
                        <template #icon>
                            <ProductIcon class="w-12 h-auto text-white" />
                        </template>
                    </StatsCard2>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 overflow-hidden shadow sm:rounded"
                >
                    <Table>
                        <template #table-action>
                            <div class="flex shrink-0 rounded overflow-hidden">
                                <DeleteBulk
                                    v-show="data.selectedId.length != 0"
                                    :selectedId="data.selectedId"
                                    :title="props.title"
                                    @close="
                                        (data.selectedId = []),
                                            (data.multipleSelect = false)
                                    "
                                />
                            </div>
                            <div class="flex justify-end items-center gap-2">
                                <PrimaryButton @click="exportIncome">
                                    {{ lang().button.export }}
                                </PrimaryButton>
                                <div class="flex items-center space-x-2">
                                    <TextInput
                                        v-model="data.params.date_start"
                                        type="date"
                                        class="block h-9"
                                        :placeholder="lang().placeholder.date"
                                    />
                                    <span>-</span>
                                    <TextInput
                                        v-model="data.params.date_end"
                                        type="date"
                                        class="block h-9"
                                        :placeholder="lang().placeholder.date"
                                    />
                                </div>
                                <TextInput
                                    v-model="data.params.search"
                                    type="search"
                                    class="block h-9"
                                    :placeholder="lang().placeholder.search"
                                />
                                <div class="flex space-x-2">
                                    <SelectInput
                                        class="h-9 text-sm"
                                        v-model="data.params.perPage"
                                        :dataSet="$page.props.app.perpage"
                                    />
                                </div>
                            </div>
                        </template>
                        <template #table-head>
                            <tr>
                                <th class="p-4 text-left">
                                    <Checkbox
                                        v-model:checked="data.multipleSelect"
                                        @change="selectAll"
                                    />
                                </th>
                                <th class="p-4 text-center">#</th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('invoice_number')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>Invoice</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('customer_name')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.name }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('grand_total')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.income }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('created_at')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.date }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th class="p-4 text-center sr-only">Action</th>
                            </tr>
                        </template>
                        <template #table-body>
                            <tr
                                v-for="(
                                    transaction, index
                                ) in transactions.data"
                                :key="index"
                                class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-200/30 hover:dark:bg-slate-900/20"
                            >
                                <td class="whitespace-nowrap px-4 py-2">
                                    <input
                                        class="rounded dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-primary dark:text-primary shadow-sm focus:ring-primary/80 dark:focus:ring-primary dark:focus:ring-offset-slate-800 dark:checked:bg-primary dark:checked:border-primary"
                                        type="checkbox"
                                        @change="select"
                                        :value="transaction.id"
                                        v-model="data.selectedId"
                                    />
                                </td>
                                <td
                                    class="whitespace-nowrap px-4 py-2 text-center"
                                >
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ transaction.invoice_number }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ transaction.customer_name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ transaction.grand_total }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ transaction.created_at }}
                                </td>
                                <td
                                    class="whitespace-nowrap flex justify-end px-4 py-2"
                                >
                                    <div
                                        class="flex w-fit rounded overflow-hidden"
                                    >
                                        <Detail
                                            :title="props.title"
                                            :transaction="data.transaction"
                                            @open="
                                                data.transaction = transaction
                                            "
                                        />
                                        <Delete
                                            :title="props.title"
                                            :transaction="data.transaction"
                                            @open="
                                                data.transaction = transaction
                                            "
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template #table-pagination>
                            <TablePagination
                                :links="props.transactions"
                                :filters="data.params"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
