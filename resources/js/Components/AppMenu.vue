<template>
  <div class="layout-menu-container">
    <AppSubmenu :items="model" class="layout-menu" :root="true" @menuitem-click="onMenuItemClick" />
  </div>
</template>

<script>
import AppSubmenu from './AppSubmenu';

export default {
    components: {
        'AppSubmenu': AppSubmenu
    },
    props: {
        model: {
            type: Array,
            default: () => []
        }
    },
    emits: ['menuitem-click'],
    methods: {
        onMenuItemClick(event) {
            this.$emit('menuitem-click', event);
        }
    }
};
</script>

<style scoped>

</style>
