<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Breadcrumb from "@/Layouts/Authenticated/Breadcrumb.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TablePagination from "@/Components/TablePagination.vue";
import TextInput from "@/Components/TextInput.vue";
import Create from "@/Pages/Order/Create.vue";
import { onMounted, reactive, watch } from "vue";
import pkg from "lodash";
import { router, useForm } from "@inertiajs/vue3";
import { TrashIcon } from "@heroicons/vue/24/outline";
import currencyFormat from "../../Helpers/CurrencyFormat";
import CurrencyInput from "../../Components/CurrencyInput.vue";

const { _, debounce, pickBy } = pkg;
const props = defineProps({
    title: String,
    filters: Object,
    products: Object,
    breadcrumbs: Object,
    perPage: Number,
    carts: Object,
    subTotal: Number,
    customers: Object,
    payments: Object,
    order: Object,
});

const data = reactive({
    params: {
        search: props.filters.search,
        field: props.filters.field,
        order: props.filters.order,
        perPage: props.perPage,
    },
    carts: {},
    discount: 0,
});

const order = (field) => {
    data.params.field = field;
    data.params.order = data.params.order === "asc" ? "desc" : "asc";
};

watch(
    () => _.cloneDeep([data.params]),
    debounce(() => {
        let params = pickBy(data.params);
        router.get(route("order.index"), params, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    }, 150)
);

onMounted(() => {
    data.carts = props.carts;
});

const form = useForm({
    product_id: null,
});

const addToCart = (id) => {
    let product = props.products.data.find((product) => product.id === id);

    form.product_id = product.id;

    form.post(route("order.addToCart"), {
        preserveScroll: true,
        onSuccess: (resp) => {
            console.log(resp);
        },
    });
};

const editQty = (id, type) => {
    router.post(route("order.edit-quantity", { id }), {
        type,
        preserveScroll: true,
    });
};

const clearCart = () => {
    router.post(route("order.clear-cart"), {
        preserveScroll: true,
    });
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
            <div class="max-w-7xl flex flex-wrap gap-4 mx-auto sm:px-6 lg:px-8">
                <div class="w-full md:w-2/3">
                    <div class="flex justify-between">
                        <h1 class="text-lg">Choose Order</h1>
                        <TextInput
                            v-model="data.params.search"
                            type="search"
                            :placeholder="lang().placeholder.search"
                            class="w-48"
                        />
                    </div>
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-4 place-items-stretch mt-3"
                    >
                        <div
                            v-for="product in props.products.data"
                            :key="product.id"
                            @click="addToCart(product.id)"
                            class="card card-compact bg-slate-200 dark:bg-base-200 w-48 shadow-md flex-auto hover:shadow-lg cursor-pointer transition-transform transform hover:scale-105"
                        >
                            <figure>
                                <img
                                    :src="product.full_image_path"
                                    :alt="product.name"
                                />
                            </figure>
                            <div class="card-body">
                                <h2 class="card-title capitalize">
                                    {{ product.name }}
                                </h2>
                                <div class="flex justify-between items-center">
                                    <span>{{ product.formated_price }}</span>
                                    <span
                                        v-if="product.stock < 1"
                                        class="text-xs text-red-500"
                                        >Out of stock!</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <TablePagination
                            :links="props.products"
                            :filters="data.params"
                        />
                    </div>
                </div>

                <div
                    class="flex-1 p-4 bg-white dark:bg-slate-800 overflow-hidden shadow sm:rounded"
                >
                    <h2 class="text-lg">Order Lists</h2>
                    <div class="mt-4" v-if="props.carts.length > 0">
                        <ul
                            class="grid grid-cols-1 gap-2 max-h-96 overflow-y-auto"
                        >
                            <li
                                v-for="cart in carts"
                                :key="cart.id"
                                class="shadow-sm overflow-hidden"
                            >
                                <div
                                    class="card card-compact px-2 card-side bg-slate-200 dark:bg-base-100"
                                    :class="{
                                        'blur-sm transition ease-in-out duration-500':
                                            cart.showAction,
                                    }"
                                >
                                    <figure>
                                        <img
                                            class="w-12 h-12 rounded-md"
                                            :src="cart.product.full_image_path"
                                            :alt="cart.product.name"
                                        />
                                    </figure>
                                    <div
                                        class="p-2 w-full items-center flex justify-between"
                                    >
                                        <div class="w-1/3">
                                            <h2
                                                class="card-title text-base capitalize truncate"
                                            >
                                                {{ cart.product.name }}
                                            </h2>
                                            <span class="text-sm">{{
                                                cart.product.formated_price
                                            }}</span>
                                        </div>
                                        <div class="w-1/3 mx-auto text-center">
                                            <div
                                                class="flex justify-center items-center gap-2"
                                            >
                                                <button
                                                    class="btn btn-xs btn-circle btn-outline"
                                                    @click="
                                                        editQty(
                                                            cart.id,
                                                            'increment'
                                                        )
                                                    "
                                                >
                                                    +</button
                                                ><span class="text-sm"
                                                    >{{ cart.quantity }}x</span
                                                ><button
                                                    class="btn btn-xs btn-circle btn-outline"
                                                    @click="
                                                        editQty(
                                                            cart.id,
                                                            'decrement'
                                                        )
                                                    "
                                                >
                                                    -
                                                </button>
                                            </div>
                                        </div>
                                        <div class="w-1/3 text-right">
                                            <span class="font-semibold">{{
                                                cart.formated_price
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="flex justify-between mt-4">
                            <h3 class="font-semibold text-sm">Sub Total</h3>
                            <span class="font-bold">{{
                                currencyFormat(props.subTotal)
                            }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <h3 class="font-light text-sm">
                                PB ({{ $page.props.app.setting.tax }}%)
                            </h3>
                            <span class="font-bold">{{
                                currencyFormat(
                                    (props.subTotal *
                                        $page.props.app.setting.tax) /
                                        100
                                )
                            }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <h3 class="font-light text-sm">Discount</h3>

                            <CurrencyInput
                                v-model="data.discount"
                                class="w-28 text-right"
                                type="text"
                                :placeholder="lang().placeholder.discount"
                            />
                        </div>
                        <div class="flex justify-end mt-4 gap-2">
                            <DangerButton @click="clearCart()"
                                ><TrashIcon class="w-4 h-auto"
                            /></DangerButton>
                            <Create
                                :carts="carts"
                                :subTotal="props.subTotal"
                                :customers="props.customers"
                                :payments="props.payments"
                                :discount="Number(data.discount)"
                                :tax="$page.props.app.setting.tax"
                            />
                        </div>
                    </div>
                    <div class="mt-4" v-else>
                        <div class="flex justify-center items-center h-48">
                            <span class="text-lg">No item in cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
