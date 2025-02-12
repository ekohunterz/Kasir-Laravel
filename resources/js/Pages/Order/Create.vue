<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { onUpdated, ref, watch } from "vue";
import { ShoppingCartIcon } from "@heroicons/vue/24/outline";
import CurrencyInput from "../../Components/CurrencyInput.vue";
import currencyFormat from "../../Helpers/CurrencyFormat";

const show = ref(false);
const showCustomerForm = ref(false);
const props = defineProps({
    title: String,
    carts: Array,
    subTotal: Number,
    customers: Object,
    payments: Object,
    discount: Number,
    tax: Number,
});

const form = useForm({
    products: [],
    grand_total: 0,
    customer_id: 1,
    cash: 0,
    payment_id: 1,
    discount: 0,
    already_paid: true,
    note: "",
});

const formCustomer = useForm({
    customer_name: "",
    customer_phone: "",
    customer_address: "",
});

const submit = () => {
    form.post(route("order.store"), {
        preserveScroll: true,
        onSuccess: (response) => {
            closeModal();
            // Assuming the response contains the order ID or invoice number:
            const orderId = response.props.flash.data.order_id; // Or response.props.order.invoice_number

            // Redirect to the invoice print route or open a new window
            window.open(route("order.print", { id: orderId }), "_blank"); // Adjust route name as needed
        },
        onError: () => null,
        onFinish: () => null,
    });
};

const submitCustomer = () => {
    formCustomer.post(route("customer.store"), {
        preserveScroll: true,
        onSuccess: (response) => {
            console.log(response);
            showCustomerForm.value = false;

            // Update customersRef:
            customers.value = response.props.customers.map((customer) => ({
                value: customer.id,
                label: customer.name,
            }));

            // Update form.customer_id:
            form.customer_id = response.props.customer.id;
        },
        onError: () => null,
        onFinish: () => null,
    });
};

onUpdated(() => {
    if (show) {
        form.products = props.carts.map((cart) => {
            return {
                product_id: cart.id,
                quantity: cart.quantity,
            };
        });
        form.grand_total = (
            (props.subTotal - props.discount) *
            (1 + props.tax / 100)
        ).toFixed(0);
        form.discount = props.discount;
    }
});

watch(
    () => props.customers,
    (newCustomers) => {
        customers.value = newCustomers.map((customer) => ({
            value: customer.id,
            label: customer.name,
        }));
    }
);

const closeModal = () => {
    show.value = false;
    form.errors = {};
    form.reset();
};

const customers = ref(
    props.customers.map((customer) => ({
        // Use ref
        value: customer.id,
        label: customer.name,
    }))
);

const payments = props.payments.map((payment) => {
    return {
        value: payment.id,
        label: payment.name,
    };
});
</script>
<template>
    <div>
        <PrimaryButton
            :disabled="carts.length <= 0"
            class="flex items-center justify-start gap-2"
            @click.prevent="show = true"
        >
            <ShoppingCartIcon class="w-4 h-auto" />
            <span class="hidden md:block">{{
                lang().button.checkout ?? "Checkout"
            }}</span>
        </PrimaryButton>
        <DialogModal :show="show" @close="closeModal">
            <template #title>
                {{ lang().label.checkout ?? "Checkout" }}
            </template>

            <template #content>
                <form class="space-y-2" @submit.prevent="submit">
                    <div class="space-y-1">
                        <InputLabel
                            for="customer"
                            :value="lang().label.customer"
                        />
                        <SelectInput
                            id="customer"
                            v-model="form.customer_id"
                            class="block w-full"
                            :dataSet="customers"
                            :error="form.errors.customer"
                        />
                        <button
                            @click.prevent="
                                showCustomerForm = !showCustomerForm
                            "
                            class="text-sm mt-1 text-primary hover:underline"
                        >
                            + {{ lang().button.add }}
                            {{ lang().label.customer }}
                        </button>
                        <InputError :message="form.errors.customer" />
                    </div>
                    <div
                        v-show="showCustomerForm"
                        class="flex flex-wrap gap-2 border-1 border rounded p-2 dark:border-gray-700"
                    >
                        <div class="w-1/2 flex-1 space-y-1">
                            <InputLabel
                                for="customer_name"
                                :value="lang().label.customer_name"
                            />
                            <TextInput
                                id="customer_name"
                                v-model="formCustomer.customer_name"
                                type="text"
                                class="block w-full"
                                autocomplete="name"
                                :placeholder="lang().placeholder.customer_name"
                            />
                            <InputError
                                :message="formCustomer.errors.customer_name"
                            />
                        </div>
                        <div class="w-1/2 space-y-1">
                            <InputLabel
                                for="customer_phone"
                                :value="lang().label.phone"
                            />
                            <TextInput
                                id="customer_phone"
                                v-model="formCustomer.customer_phone"
                                type="text"
                                class="block w-full"
                                autocomplete="phone"
                                :placeholder="lang().placeholder.phone"
                            />
                            <InputError
                                :message="formCustomer.errors.customer_phone"
                            />
                        </div>
                        <div class="w-full space-y-1">
                            <InputLabel
                                for="customer_address"
                                :value="lang().label.address"
                            />
                            <TextInput
                                id="customer_address"
                                v-model="formCustomer.customer_address"
                                type="text"
                                class="block w-full"
                                autocomplete="address"
                                :placeholder="lang().placeholder.address"
                            />
                            <InputError
                                :message="formCustomer.errors.customer_address"
                            />
                        </div>
                        <div class="ml-auto space-x-2">
                            <PrimaryButton
                                :class="{
                                    'opacity-25': formCustomer.processing,
                                }"
                                :disabled="formCustomer.processing"
                                @click="submitCustomer"
                            >
                                {{ lang().button.save }}
                                {{ formCustomer.processing ? "..." : "" }}
                            </PrimaryButton>
                            <SecondaryButton @click="showCustomerForm = false">
                                {{ lang().button.cancel }}
                            </SecondaryButton>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <InputLabel
                            for="grand_total"
                            :value="lang().label.grand_total"
                        />
                        <TextInput
                            id="grand_total"
                            disabled
                            :value="currencyFormat(form.grand_total)"
                            type="text"
                            class="block w-full"
                            autocomplete="grand_total"
                            :placeholder="lang().placeholder.grand_total"
                            :error="form.errors.grand_total"
                        />
                        <InputError :message="form.errors.grand_total" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel
                            for="payment"
                            :value="lang().label.payment"
                        />
                        <SelectInput
                            id="payment"
                            v-model="form.payment_id"
                            class="block w-full"
                            :dataSet="payments"
                            :error="form.errors.payment"
                        />
                        <InputError :message="form.errors.payment" />
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <div class="space-y-1">
                            <InputLabel for="cash" :value="lang().label.cash" />
                            <CurrencyInput
                                id="cash"
                                v-model="form.cash"
                                type="text"
                                class="block w-full"
                                autocomplete="cash"
                                :placeholder="lang().placeholder.cash"
                                :error="form.errors.cash"
                            />
                            <InputError :message="form.errors.cash" />
                        </div>
                        <div class="flex-1 space-y-1">
                            <InputLabel
                                for="change"
                                :value="lang().label.change"
                            />
                            <TextInput
                                id="change"
                                disabled
                                :value="
                                    currencyFormat(
                                        form.cash
                                            ? form.cash - form.grand_total
                                            : 0
                                    )
                                "
                                type="text"
                                class="block w-full"
                                :placeholder="lang().placeholder.change"
                            />
                        </div>
                        <div class="space-x-1">
                            <button
                                @click.prevent="form.cash += 10000"
                                class="btn btn-outline btn-xs"
                            >
                                +10.000
                            </button>
                            <button
                                @click.prevent="form.cash += 20000"
                                class="btn btn-outline btn-xs"
                            >
                                +20.000
                            </button>
                            <button
                                @click.prevent="form.cash += 50000"
                                class="btn btn-outline btn-xs"
                            >
                                +50.000
                            </button>
                            <button
                                @click.prevent="form.cash += 100000"
                                class="btn btn-outline btn-xs"
                            >
                                +100.000
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <InputLabel for="note" :value="lang().label.note" />
                        <TextInput
                            id="note"
                            v-model="form.note"
                            type="text"
                            class="block w-full"
                            autocomplete="note"
                            :placeholder="lang().placeholder.note"
                            :error="form.errors.note"
                        />

                        <InputError :message="form.errors.note" />
                    </div>
                </form>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    {{ lang().button.cancel }}
                </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submit"
                >
                    {{ lang().button.save }} {{ form.processing ? "..." : "" }}
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>
