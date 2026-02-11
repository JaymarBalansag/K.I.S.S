<template>
    <div class="main-container min-h-screen flex flex-col ">
        <button @click="triggerDownload"
            class="ml-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Download via Controller
        </button>
    </div>
</template>

<script setup>
const triggerDownload = () => {
    // We use window.open or window.location.href to hit the Laravel API
    window.location.href = '/api/download';
};
</script>