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
import CurrencyInput from "../../Components/CurrencyInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, onUpdated } from "vue";
import { PencilIcon } from "@heroicons/vue/24/outline";

const emit = defineEmits(["open"]);
const show = ref(false);
const props = defineProps({
    title: String,
    roles: Object,
    categories: Object,
    product: Object,
});

const form = useForm({
    name: "",
    image: null,
    description: "",
    is_active: false,
    category_id: 1,
    stock: 0,
    price: 0,
    _method: "PUT",
});

onUpdated(() => {
    if (show) {
        form.name = props.product?.name;
        form.description = props.product?.description;
        form.is_active = props.product?.is_active;
        form.category_id = props.product?.category_id;
        form.stock = props.product?.stock;
        form.price = props.product?.price;
    }
});

const submit = () => {
    form.post(route("product.update", props.product?.id), {
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
                            :image="props.product?.full_image_path"
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
