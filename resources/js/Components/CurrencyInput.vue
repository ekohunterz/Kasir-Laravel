<script setup>
import { useCurrencyInput } from "vue-currency-input";
import { onMounted } from "vue";

defineProps({
    modelValue: [String, Number],
    error: {
        type: String,
        default: null,
    },
});

// defineEmits(["update:modelValue"]);

onMounted(() => {
    if (inputRef.value.hasAttribute("autofocus")) {
        inputRef.value.focus();
    }
});

defineExpose({ focus: () => inputRef.value.focus() });

const { inputRef } = useCurrencyInput({
    locale: "id-ID",
    currency: "IDR",
});
</script>

<template>
    <input
        ref="inputRef"
        type="text"
        v-bind:class="
            error
                ? 'border-rose-500 dark:border-rose-400 dark:bg-slate-900 dark:text-slate-300 focus:border-rose-500 dark:focus:border-rose-400 focus:ring-rose-500 dark:focus:ring-rose-400 '
                : 'border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary '
        "
        class="rounded shadow-sm placeholder:text-slate-300 dark:placeholder:text-slate-700"
        :value="modelValue"
    />
</template>
