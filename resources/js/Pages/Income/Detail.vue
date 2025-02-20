<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ActionButton from "@/Components/ActionButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { router, useForm } from "@inertiajs/vue3";
import { ref, onUpdated } from "vue";
import SelectInput from "@/Components/SelectInput.vue";
import { EyeIcon, PencilIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["open"]);
const show = ref(false);
const props = defineProps({
    title: String,
    roles: Object,
    transaction: Object,
});

const print = () => {
    window.open(route("order.print", { id: props.transaction.id }), "_blank");
};

const closeModal = () => {
    show.value = false;
};
</script>
<template>
    <div>
        <ActionButton
            variant="success"
            v-tooltip="lang().label.detail"
            @click.prevent="(show = true), emit('open')"
        >
            <EyeIcon class="w-4 h-auto" />
        </ActionButton>
        <DialogModal :show="show" @close="closeModal">
            <template #title>
                {{ lang().label.detail }} {{ props.transaction.invoice_number }}
            </template>

            <template #content>
                <div>
                    <div class="mt-6">
                        <dl class="divide-y divide-gray-400">
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.name }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.customer_name }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.sub_total }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.formated_sub_total }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.discount }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.formated_discount }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.tax }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.tax }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.grand_total }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.grand_total }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.payment }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.payment.name }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.paid_status }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    <span
                                        v-if="props.transaction.already_paid"
                                        class="badge badge-sm badge-success"
                                        >Completed</span
                                    >
                                    <span
                                        v-else
                                        class="badge badge-sm badge-error"
                                        >Pending</span
                                    >
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.note }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.note }}
                                </dd>
                            </div>
                            <div
                                class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0"
                            >
                                <dt class="text-sm/6 font-medium">
                                    {{ lang().label.cashier }}
                                </dt>
                                <dd
                                    class="mt-1 text-sm/6 sm:col-span-2 sm:mt-0"
                                >
                                    {{ props.transaction.user.name }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    {{ lang().button.cancel }}
                </SecondaryButton>
                <PrimaryButton @click="print" class="ml-3">
                    {{ lang().button.print }}
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>
