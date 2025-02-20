<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { PlusIcon } from "@heroicons/vue/24/outline";

const show = ref(false);
const props = defineProps({
    title: String,
});

const form = useForm({
    customer_name: "",
    customer_address: "",
    customer_phone: "",
});

const submit = () => {
    form.post(route("customer.store"), {
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
        <PrimaryButton
            class="flex rounded-none items-center justify-start gap-2"
            @click.prevent="show = true"
        >
            <PlusIcon class="w-4 h-auto" />
            <span class="hidden md:block">{{ lang().button.add }}</span>
        </PrimaryButton>
        <DialogModal :show="show" @close="closeModal">
            <template #title>
                {{ lang().label.add }} {{ props.title }}
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
