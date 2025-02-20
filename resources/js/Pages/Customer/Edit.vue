<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ActionButton from "@/Components/ActionButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import ImageInput from "@/Components/ImageInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, onUpdated } from "vue";
import SelectInput from "@/Components/SelectInput.vue";
import { PencilIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["open"]);
const show = ref(false);
const props = defineProps({
    title: String,
    roles: Object,
    customer: Object,
});

const form = useForm({
    customer_name: "",
    customer_phone: "",
    customer_address: "",
});

onUpdated(() => {
    if (show) {
        form.customer_name = props.customer?.name;
        form.customer_phone = props.customer?.phone;
        form.customer_address = props.customer?.address;
    }
});

const submit = () => {
    form.put(route("customer.update", props.customer?.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => null,
        onFinish: () => null,
    });
};

const closeModal = () => {
    show.value = false;
    form.errors = {};
    form.reset();
};
</script>
<template>
    <div>
        <ActionButton
            v-tooltip="lang().label.edit"
            @click.prevent="(show = true), emit('open')"
        >
            <PencilIcon class="w-4 h-auto" />
        </ActionButton>
        <DialogModal :show="show" @close="closeModal">
            <template #title>
                {{ lang().label.edit }} {{ props.title }}
            </template>

            <template #content>
                <form class="space-y-2" @submit.prevent="submit">
                    <div class="space-y-1">
                        <InputLabel for="name" :value="lang().label.name" />
                        <TextInput
                            id="name"
                            v-model="form.customer_name"
                            type="text"
                            class="block w-full"
                            autocomplete="name"
                            :placeholder="lang().placeholder.name"
                            :error="form.errors.customer_name"
                        />
                        <InputError :message="form.errors.customer_name" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel for="phone" :value="lang().label.phone" />
                        <TextInput
                            id="phone"
                            v-model="form.customer_phone"
                            type="text"
                            class="block w-full"
                            autocomplete="phone"
                            :placeholder="lang().placeholder.phone"
                            :error="form.errors.customer_phone"
                        />
                        <InputError :message="form.errors.customer_phone" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel
                            for="address"
                            :value="lang().label.address"
                        />
                        <TextAreaInput
                            id="address"
                            v-model="form.customer_address"
                            class="block w-full"
                            :placeholder="lang().placeholder.address"
                            :error="form.errors.customer_address"
                        />
                        <InputError :message="form.errors.customer_address" />
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
