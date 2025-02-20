<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ImageInput from "@/Components/ImageInput.vue";
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
    name: "",
    image: null,
    description: "",
});

const submit = () => {
    form.post(route("category.store"), {
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
