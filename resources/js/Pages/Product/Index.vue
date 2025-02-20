<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/Table.vue";
import Breadcrumb from "@/Layouts/Authenticated/Breadcrumb.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TablePagination from "@/Components/TablePagination.vue";
import TextInput from "@/Components/TextInput.vue";
import Create from "@/Pages/Product/Create.vue";
import Edit from "@/Pages/Product/Edit.vue";
import Delete from "@/Pages/Product/Delete.vue";
import Import from "@/Pages/Product/Import.vue";
import DeleteBulk from "@/Pages/Product/DeleteBulk.vue";
import { reactive, watch } from "vue";
import pkg from "lodash";
import { router } from "@inertiajs/vue3";
import {
    ChevronUpDownIcon,
    CheckBadgeIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";
import Checkbox from "@/Components/Checkbox.vue";

const { _, debounce, pickBy } = pkg;
const props = defineProps({
    title: String,
    filters: Object,
    products: Object,
    breadcrumbs: Object,
    perPage: Number,
    categories: Object,
});

const data = reactive({
    params: {
        search: props.filters.search,
        field: props.filters.field,
        order: props.filters.order,
        perPage: props.perPage,
    },
    selectedId: [],
    multipleSelect: false,
    product: null,
});

const order = (field) => {
    data.params.field = field;
    data.params.order = data.params.order === "asc" ? "desc" : "asc";
};

watch(
    () => _.cloneDeep(data.params),
    debounce(() => {
        let params = pickBy(data.params);
        router.get(route("product.index"), params, {
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
        props.products?.data.forEach((product) => {
            data.selectedId.push(product.id);
        });
    }
};
const select = () => {
    if (props.products?.data.length == data.selectedId.length) {
        data.multipleSelect = true;
    } else {
        data.multipleSelect = false;
    }
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
                    class="bg-white dark:bg-slate-800 overflow-hidden shadow sm:rounded"
                >
                    <Table>
                        <template #table-action>
                            <div class="flex shrink-0 rounded overflow-hidden">
                                <Create
                                    v-show="can(['product create'])"
                                    :title="props.title"
                                    :categories="props.categories"
                                />
                                <DeleteBulk
                                    v-show="
                                        data.selectedId.length != 0 &&
                                        can(['product delete'])
                                    "
                                    :selectedId="data.selectedId"
                                    :title="props.title"
                                    @close="
                                        (data.selectedId = []),
                                            (data.multipleSelect = false)
                                    "
                                />
                            </div>
                            <div class="flex justify-end items-center gap-2">
                                <div
                                    class="flex shrink-0 rounded overflow-hidden"
                                >
                                    <Import
                                        v-show="can(['product create'])"
                                        :title="props.title"
                                    />
                                </div>

                                <div class="flex space-x-2">
                                    <SelectInput
                                        class="h-9 text-sm"
                                        v-model="data.params.perPage"
                                        :dataSet="$page.props.app.perpage"
                                    />
                                </div>
                                <TextInput
                                    v-model="data.params.search"
                                    type="search"
                                    class="block h-9"
                                    :placeholder="lang().placeholder.search"
                                />
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
                                    v-on:click="order('name')"
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
                                    v-on:click="order('price')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.price }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('stock')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.stock }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th class="p-4 text-left">
                                    {{ lang().label.description }}
                                </th>
                                <th class="p-4 text-left">
                                    {{ lang().label.image }}
                                </th>
                                <th class="p-4 text-left">
                                    {{ lang().label.category }}
                                </th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('is_active')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.active }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th class="p-4 text-center sr-only">Action</th>
                            </tr>
                        </template>
                        <template #table-body>
                            <tr
                                v-for="(product, index) in products.data"
                                :key="index"
                                class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-200/30 hover:dark:bg-slate-900/20"
                            >
                                <td class="whitespace-nowrap px-4 py-2">
                                    <input
                                        class="rounded dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-primary dark:text-primary shadow-sm focus:ring-primary/80 dark:focus:ring-primary dark:focus:ring-offset-slate-800 dark:checked:bg-primary dark:checked:border-primary"
                                        type="checkbox"
                                        @change="select"
                                        :value="product.id"
                                        v-model="data.selectedId"
                                    />
                                </td>
                                <td
                                    class="whitespace-nowrap px-4 py-2 text-center"
                                >
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ product.name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ product.formated_price }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ product.stock }}
                                </td>
                                <td class="whitespace-pre-wrap px-4 py-2">
                                    {{ product.description ?? "-" }}
                                </td>
                                <td class="whitespace-nowrap mx-auto px-4 py-2">
                                    <div
                                        v-show="product.full_image_path"
                                        class="mt-2 shrink-0"
                                    >
                                        <span
                                            class="block rounded w-16 h-16 bg-cover bg-no-repeat bg-center"
                                            :style="
                                                'background-image: url(\'' +
                                                product.full_image_path +
                                                '\');'
                                            "
                                        />
                                    </div>
                                </td>
                                <td class="whitespace-pre-wrap px-4 py-2">
                                    {{ product.category?.name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <CheckBadgeIcon
                                        v-tooltip="'Active'"
                                        v-if="product.is_active"
                                        class="w-6 h-auto text-blue-500 ml-1 shrink-0"
                                    />
                                    <XCircleIcon
                                        v-tooltip="'Inactive'"
                                        v-else
                                        class="w-6 h-auto text-red-500 ml-1 shrink-0"
                                    />
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <div
                                        class="flex w-fit rounded overflow-hidden"
                                    >
                                        <Edit
                                            v-show="can(['product update'])"
                                            :title="props.title"
                                            :product="data.product"
                                            :categories="props.categories"
                                            @open="data.product = product"
                                        />
                                        <Delete
                                            v-show="can(['product delete'])"
                                            :title="props.title"
                                            :product="data.product"
                                            @open="data.product = product"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template #table-pagination>
                            <TablePagination
                                :links="props.products"
                                :filters="data.params"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
