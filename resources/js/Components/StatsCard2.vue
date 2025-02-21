<script setup>
import { ArrowDownIcon, ArrowUpIcon } from "@heroicons/vue/24/outline";
import { defineProps } from "vue";

const props = defineProps({
    value: [Number, String],
    title: String,
    color: String,
});
</script>

<template>
    <div
        class="bg-white dark:bg-slate-800 overflow-hidden shadow sm:rounded text-slate-800 dark:text-slate-200 flex gap-4"
    >
        <div
            class="w-24 h-24 flex m-2 rounded justify-center items-center"
            :class="props.color ? props.color : 'bg-sky-500'"
        >
            <slot name="icon"></slot>
        </div>
        <div class="flex flex-col justify-center items-start">
            <p class="truncate font-semibold max-w-full">
                {{ props.title }}
            </p>
            <p class="text-3xl font-semibold text-primary">
                {{ props.value }}
            </p>
        </div>
    </div>
</template>
