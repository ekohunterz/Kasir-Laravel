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
    category: Object,
});

const form = useForm({
    name: "",
    description: "",
    image: null,
    _method: "PUT",
});

onUpdated(() => {
    if (show) {
        form.name = props.category?.name;
        form.description = props.category?.description;
    }
});

const submit = () => {
    form.post(route("category.update", props.category?.id), {
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
                            v-model="form.name"
                            type="text"
                            class="block w-full"
                            autocomplete="name"
                            :placeholder="lang().placeholder.name"
                            :error="form.errors.name"
                        />
                        <InputError :message="form.errors.name" />
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
                        <InputLabel for="image" :value="lang().label.image" />
                        <ImageInput
                            source="image"
                            v-model="form.image"
                            :image="props.category?.full_image_path"
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
