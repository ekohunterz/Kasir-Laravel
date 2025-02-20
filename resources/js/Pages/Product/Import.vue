<script setup>
import DialogModal from "@/Components/DialogModal.vue";
import ActionButton from "@/Components/ActionButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const emit = defineEmits(["open"]);
const show = ref(false);
const props = defineProps({
    title: String,
});

const form = useForm({
    file: "",
    errors: {},
});

const submit = () => {
    form.post(route("product.import"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => null,
        onFinish: () => null,
    });
};

const closeModal = () => {
    show.value = false;
};
</script>
<template>
    <div>
        <ActionButton
            v-tooltip="lang().label.import"
            variant="info"
            @click.prevent="(show = true), emit('open')"
        >
            Import
        </ActionButton>
        <DialogModal :show="show" @close="closeModal">
            <template #title>
                {{ lang().label.import }} {{ props.title }}
            </template>

            <template #content>
                <form class="space-y-2" @submit.prevent="submit">
                    <div class="space-y-1">
                        <InputLabel for="file" :value="lang().label.file" />

                        <input
                            id="file"
                            type="file"
                            class="file-input w-full file-input-bordered"
                            @change="form.file = $event.target.files[0]"
                            :error="form.errors.file"
                        />
                        <InputError :message="form.errors.file" />
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
                    {{ lang().button.import }}
                    {{ form.processing ? "..." : "" }}
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>
