<template>
    <ul v-if="items">
        <template v-for="(item,i) of items">
            <li v-if="visible(item) && !item.separator" :key="i"
                :class="[{'active-menuitem': activeIndex === i && !item.to && !item.disabled}]" role="none"
            >
                <div v-if="item.items && root===true" class="arrow"></div>
                <a v-if="item.to" :to="item.to"
                   :class="[item.class, 'p-ripple',{'active-route': activeIndex === i, 'p-disabled': item.disabled}]"
                   :style="item.style"
                   :target="item.target" role="menuitem" @click="onMenuItemClick($event,item,i)"
                >
                    <i :class="item.icon"></i>
                    <span>{{ item.label }}</span>
                    <i v-if="item.items" class="pi pi-fw pi-angle-down menuitem-toggle-icon"></i>
                    <span v-if="item.badge" class="menuitem-badge">{{ item.badge }}</span>
                </a>
                <a v-if="!item.to" :href="item.url||'#'" :style="item.style"
                   :class="[item.class, 'p-ripple', {'p-disabled': item.disabled}]"
                   :target="item.target" role="menuitem" @click="onMenuItemClick($event,item,i)"
                >
                    <i :class="item.icon"></i>
                    <span>{{ item.label }}</span>
                    <i v-if="item.items" class="pi pi-fw pi-angle-down menuitem-toggle-icon"></i>
                    <span v-if="item.badge" class="menuitem-badge">{{ item.badge }}</span>
                </a>
                <transition name="layout-submenu-wrapper">
                    <AppSubmenu v-show="activeIndex === i" :items="visible(item) && item.items"
                                @menuitem-click="$emit('menuitem-click', $event)"
                    />
                </transition>
            </li>
            <li v-if="visible(item) && item.separator" :key="'separator' + i" class="p-menu-separator"
                :style="item.style" role="separator"
            ></li>
        </template>
    </ul>
</template>

<script>
export default {
    name: 'AppSubmenu',
    props: {
        items: {
            type: Array,
            default: () => []
        },
        root: {
            type: Boolean,
            default: false
        }
    },
    emits: ['menuitem-click'],
    data() {
        return {
            activeIndex: null
        };
    },
    mounted() {
        for (let itemIndex in this.items) {
            if (this.isUrl(this.items[itemIndex].prefix)) {
                this.activeIndex = parseInt(itemIndex, 10);
            }
        }
    },
    methods: {
        onMenuItemClick(event, item, index) {
            if (item.disabled) {
                event.preventDefault();
                return;
            }
            if (item.route) {
                event.preventDefault();
                this.$inertia.get(item.route);
            }

            if (!item.to && !item.url) {
                event.preventDefault();
            }
            //execute command
            if (item.command) {
                item.command({originalEvent: event, item: item});
            }
            this.activeIndex = index === this.activeIndex ? null : index;
            this.$emit('menuitem-click', {
                originalEvent: event,
                item: item
            });
        },
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1);
            if (urls[0] === '') {
                return currentUrl === '';
            }
            return urls.filter(url => currentUrl.startsWith(url)).length;
        },
        visible(item) {
            return (typeof item.visible === 'function' ? item.visible() : item.visible !== false);
        }
    }
};
</script>

<style scoped>

</style>
