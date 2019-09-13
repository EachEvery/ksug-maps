<template>
  <card class="flex flex-col">
    <div class="p-8">
      <h1 class="text-lg text-100">Mapping May 4 Admin Panel</h1>
      <p class="leading-normal mt-4 max-w-sm">
        You can edit and moderate comments and stories by using the sidebar on the left. If you have any questions, please let us know!
        <br />
        <br />
        <em>Each + Every</em>
      </p>
    </div>
  </card>
</template>

<script>
export default {
  props: [
    "card"

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  mounted() {
    //
  }
};
</script>
