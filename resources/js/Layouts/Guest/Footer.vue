<script setup></script>
<template>
    <footer class="sticky top-[100vh]">
        <div
            class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800"
        >
            <div
                class="max-w-7xl mx-auto flex justify-end items-center px-4 sm:px-6 lg:px-8 py-4"
            >
                <p class="text-slate-600 dark:text-slate-400">
                    {{
                        $page.props.app.setting.name +
                        " v." +
                        $page.props.app.version
                    }}
                    &copy; {{ new Date().getFullYear() }}.
                </p>
            </div>
        </div>
    </footer>
</template>
