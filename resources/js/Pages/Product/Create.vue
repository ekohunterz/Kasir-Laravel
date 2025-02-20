<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ImageInput from "@/Components/ImageInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import CurrencyInput from "../../Components/CurrencyInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { PlusIcon } from "@heroicons/vue/24/outline";

const show = ref(false);
const props = defineProps({
    title: String,
    categories: Object,
});

const form = useForm({
    name: "",
    image: null,
    description: "",
    is_active: false,
    category_id: 1,
    stock: 0,
    price: 0,
});

const submit = () => {
    form.post(route("product.store"), {
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

const fileChange = (value) => {
    if (value.source === "image") {
        form.image = value.file;
    }
};

const categories = props.categories.map((category) => {
    return {
        value: category.id,
        label: category.name,
    };
});
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
                        <InputLabel
                            for="name"
                            :value="lang().label.product_name"
                        />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="block w-full"
                            autocomplete="name"
                            :placeholder="lang().label.product_name"
                            :error="form.errors.name"
                        />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel for="price" :value="lang().label.price" />
                        <CurrencyInput
                            id="price"
                            v-model="form.price"
                            type="text"
                            class="block w-full"
                            autocomplete="price"
                            :placeholder="lang().placeholder.price"
                            :error="form.errors.price"
                        />
                        <InputError :message="form.errors.price" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel for="stock" :value="lang().label.stock" />
                        <TextInput
                            id="stock"
                            v-model="form.stock"
                            type="number"
                            class="block w-full"
                            autocomplete="stock"
                            :placeholder="lang().placeholder.stock"
                            :error="form.errors.stock"
                        />
                        <InputError :message="form.errors.stock" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel
                            for="description"
                            :value="lang().label.description"
                        />
                        <TextAreaInput
                            id="description"
                            v-model="form.description"
                            class="block w-full"
                            :placeholder="lang().placeholder.description"
                            :error="form.errors.description"
                        />
                        <InputError :message="form.errors.description" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel
                            for="category_id"
                            :value="lang().label.category"
                        />
                        <SelectInput
                            id="category_id"
                            v-model="form.category_id"
                            :dataSet="categories"
                            :error="form.errors.category_id"
                            class="block w-full"
                        />
                        <InputError :message="form.errors.category_id" />
                    </div>
                    <div class="space-y-1">
                        <InputLabel
                            for="is_active"
                            :value="lang().label.active"
                        />
                        <div class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                class="toggle toggle-success"
                                v-model="form.is_active"
                                id="is_active"
                            />
                            <span v-if="form.is_active"> Yes </span>
                            <span v-else> No </span>
                        </div>

                        <InputError :message="form.errors.is_active" />
                    </div>

                    <div class="space-y-1">
                        <InputLabel for="image" :value="lang().label.image" />
                        <ImageInput
                            source="image"
                            v-model="form.image"
                            tooltip="Click to select/change image"
                            class="mt-1 block"
                            @fileChange="fileChange"
                        />
                        <InputError :message="form.errors.image" />
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
