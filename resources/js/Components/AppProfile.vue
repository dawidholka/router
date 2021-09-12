<template>
    <div class="layout-profile">
        <div>
            <img :src="$page.props.user.profile_photo_url" alt="" />
        </div>
        <button class="p-link layout-profile-link" @click="onClick">
            <span class="username">
                {{ $page.props.user.first_name }}
                {{ $page.props.user.last_name }}
            </span>
            <i class="pi pi-fw pi-cog"></i>
        </button>
        <transition name="layout-submenu-wrapper">
            <ul v-show="expanded">
                <li>
                    <inertia-link :href="route('profile.show', $page.props.user.id)">
                        <button class="p-link"><i class="pi pi-fw pi-user"></i><span>Twój Profil</span></button>
                    </inertia-link>
                </li>
                <li>
                    <inertia-link :href="route('profile.show')">
                        <button class="p-link"><i class="pi pi-fw pi-cog"></i><span>Ustawienia</span></button>
                    </inertia-link>
                </li>
                <!--                <li>-->
                <!--                    <button class="p-link"><i class="pi pi-fw pi-inbox"></i><span>Notifications</span><span-->
                <!--                        class="menuitem-badge">2</span></button>-->
                <!--                </li>-->
                <li>
                    <button class="p-link" @click="logout">
                        <i class="pi pi-fw pi-power-off"></i><span>Wyloguj</span>
                    </button>
                </li>
            </ul>
        </transition>
    </div>
</template>

<script>


export default {
    name: 'AppProfile',
    data() {
        return {
            expanded: false
        };
    },
    methods: {
        onClick(event) {
            this.expanded = !this.expanded;
            event.preventDefault();
        },
        logout() {
            this.$inertia.post(this.route('logout'));
        },
    }
};
</script>

<style scoped>
</style>
