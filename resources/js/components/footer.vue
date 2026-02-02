<template>

<footer class="bg-white py-4 mt-auto shadow-lg">
    <div class="container text-center">
    <p class="mb-0 text-muted small">
        © 2026 <strong>{{ config.siteName }}</strong>. All rights reserved.
    </p>
    </div>
</footer>

</template>


<script>

export default {
  name: 'FooterComponent',
  data() {
    return {
      config: {
        siteName: "LCRO Abuyog"
      },
    };
  }
};

</script>