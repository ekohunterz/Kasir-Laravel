<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Table from "@/Components/Table.vue";
import Breadcrumb from "@/Layouts/Authenticated/Breadcrumb.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TablePagination from "@/Components/TablePagination.vue";
import TextInput from "@/Components/TextInput.vue";
import Create from "@/Pages/Category/Create.vue";
import Edit from "@/Pages/Category/Edit.vue";
import Delete from "@/Pages/Category/Delete.vue";
import DeleteBulk from "@/Pages/Category/DeleteBulk.vue";
import { reactive, watch } from "vue";
import pkg from "lodash";
import { router } from "@inertiajs/vue3";
import { ChevronUpDownIcon } from "@heroicons/vue/24/outline";
import Checkbox from "@/Components/Checkbox.vue";

const { _, debounce, pickBy } = pkg;
const props = defineProps({
    title: String,
    filters: Object,
    categories: Object,
    breadcrumbs: Object,
    perPage: Number,
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
    category: null,
});

const order = (field) => {
    data.params.field = field;
    data.params.order = data.params.order === "asc" ? "desc" : "asc";
};

watch(
    () => _.cloneDeep(data.params),
    debounce(() => {
        let params = pickBy(data.params);
        router.get(route("category.index"), params, {
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
        props.categories?.data.forEach((category) => {
            data.selectedId.push(category.id);
        });
    }
};
const select = () => {
    if (props.categories?.data.length == data.selectedId.length) {
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
                                    v-show="can(['category create'])"
                                    :title="props.title"
                                />
                                <DeleteBulk
                                    v-show="
                                        data.selectedId.length != 0 &&
                                        can(['category delete'])
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
                                <th class="p-4 text-left">
                                    {{ lang().label.description }}
                                </th>
                                <th class="p-4 text-left">
                                    {{ lang().label.image }}
                                </th>
                                <th
                                    class="p-4 cursor-pointer"
                                    v-on:click="order('created_at')"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span>{{ lang().label.created }}</span>
                                        <ChevronUpDownIcon class="w-4 h-4" />
                                    </div>
                                </th>
                                <th class="p-4 text-center sr-only">Action</th>
                            </tr>
                        </template>
                        <template #table-body>
                            <tr
                                v-for="(category, index) in categories.data"
                                :key="index"
                                class="border-t border-slate-200 dark:border-slate-700 hover:bg-slate-200/30 hover:dark:bg-slate-900/20"
                            >
                                <td class="whitespace-nowrap px-4 py-2">
                                    <input
                                        class="rounded dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-primary dark:text-primary shadow-sm focus:ring-primary/80 dark:focus:ring-primary dark:focus:ring-offset-slate-800 dark:checked:bg-primary dark:checked:border-primary"
                                        type="checkbox"
                                        @change="select"
                                        :value="category.id"
                                        v-model="data.selectedId"
                                    />
                                </td>
                                <td
                                    class="whitespace-nowrap px-4 py-2 text-center"
                                >
                                    {{ ++index }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ category.name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ category.description ?? "-" }}
                                </td>
                                <td class="whitespace-nowrap mx-auto px-4 py-2">
                                    <div
                                        v-show="category.full_image_path"
                                        class="mt-2 shrink-0"
                                    >
                                        <span
                                            class="block rounded w-16 h-16 bg-cover bg-no-repeat bg-center"
                                            :style="
                                                'background-image: url(\'' +
                                                category.full_image_path +
                                                '\');'
                                            "
                                        />
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    {{ category.created_at }}
                                </td>
                                <td
                                    class="whitespace-nowrap flex justify-end px-4 py-2"
                                >
                                    <div
                                        class="flex w-fit rounded overflow-hidden"
                                    >
                                        <Edit
                                            v-show="can(['category update'])"
                                            :title="props.title"
                                            :category="data.category"
                                            @open="data.category = category"
                                        />
                                        <Delete
                                            v-show="can(['category delete'])"
                                            :title="props.title"
                                            :category="data.category"
                                            @open="data.category = category"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template #table-pagination>
                            <TablePagination
                                :links="props.categories"
                                :filters="data.params"
                            />
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
