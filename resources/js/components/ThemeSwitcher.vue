<template>
    <div class="flex items-center mr-8">
        <button
            class="rounded-full w-4 h-4 bg-default border mr-2 focus:outline-none"
            :class="{ 'border-accent' : selectedTheme == 'theme-light' }"
            style="background: #f5f6f9"
            @click="selectedTheme = 'theme-light'">
        </button>
        <button
            class="rounded-full w-4 h-4 bg-default border mr-2 focus:outline-none"
            :class="{ 'border-accent' : selectedTheme == 'theme-dark' }"
            style="background: #222"
            @click="selectedTheme = 'theme-dark'">
        </button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                selectedTheme: 'theme-light'
            };
        },

        watch: {
            selectedTheme() {
                document.body.className = document.body.className.replace(/theme-\w+/, this.selectedTheme);
            }
        }
    }
</script>
