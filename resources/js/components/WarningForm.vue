<template>

    <div class="alert alert-warning" role="alert">
        {{ message }}
    </div>

</template>

<script>

export default {
    name: 'WarningForm',
    props: {
        message: {
            type: String,
            required: true,
            default: 'This is a warning message.',
        },
    },
};

</script>