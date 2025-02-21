<script setup>
import { ArrowDownIcon, ArrowUpIcon } from "@heroicons/vue/24/outline";
import { defineProps } from "vue";

const props = defineProps({
    value: [Number, String],
    percentage: Number,
    variant: String,
    color: String,
});
</script>

<template>
    <div
        class="flex items-center p-4 bg-white dark:bg-slate-800 overflow-hidden shadow sm:rounded text-slate-800 dark:text-slate-200"
    >
        <div
            class="flex flex-shrink-0 items-center justify-center text-slate-200 bg-green-500 h-16 w-16 rounded"
            :class="props.color"
        >
            <slot name="icon" />
        </div>
        <div class="flex-grow flex flex-col ml-4">
            <span class="text-xl font-bold">{{ props.value }}</span>
            <div class="flex items-center justify-between">
                <span class="text-gray-500"><slot name="desc" /></span>
                <span
                    class="text-green-500 text-sm font-semibold ml-2 flex items-center gap-1"
                    v-if="props.variant === 'success'"
                    >{{ props.percentage + "%" }}
                    <ArrowUpIcon class="h-4 w-4" />
                </span>
                <span
                    class="text-red-500 text-sm font-semibold ml-2 flex items-center gap-1"
                    v-else-if="props.variant === 'danger'"
                    >{{ props.percentage + "%"
                    }}<ArrowDownIcon class="h-4 w-4" />
                </span>
                <span class="text-gray-500 ml-2" v-else>{{
                    props.percentage + "%"
                }}</span>
            </div>
        </div>
    </div>
</template>
