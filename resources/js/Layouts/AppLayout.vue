<template>
    <div :class="containerClass" @click="onWrapperClick">
        <AppTopBar @menu-toggle="onMenuToggle"/>

        <transition name="layout-sidebar">
            <div v-show="isSidebarVisible()" :class="sidebarClass" @click="onSidebarClick">
                <div class="layout-logo">
                    <h3><i class="pi pi-fw pi-compass" style="font-size: 1.4rem"/> Router</h3>
                </div>

                <AppMenu :model="menu" @menuitem-click="onMenuItemClick"/>
            </div>
        </transition>

        <div class="layout-main">
            <Toast/>
            <slot></slot>
        </div>
    </div>
</template>


<script>
import AppTopBar from '../Components/AppTopBar';
import AppMenu from '../Components/AppMenu';
import Toast from 'primevue/toast';
import FlashMessage from "../Services/FlashMessage";

export default {
    name: 'AppLayout',
    components: {
        AppTopBar,
        AppMenu,
        Toast
    },
    mixins: [FlashMessage],
    data() {
        return {
            layoutMode: 'static',
            layoutColorMode: 'dark',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menu: [
                {label: 'Start', icon: 'pi pi-fw pi-home', route: this.route('dashboard'), prefix: 'dashboard'},
                {
                    label: this.$t('common.routes'),
                    icon: 'pi pi-fw pi-directions',
                    route: this.route('routes.index'),
                    prefix: 'routes',
                    visible: true,
                },
                {
                    label: this.$t('common.calendar'),
                    icon: 'pi pi-fw pi-calendar',
                    route: this.route('calendar.index'),
                    prefix: 'calendar'
                },
                {
                    label: this.$t('common.drivers'),
                    icon: 'pi pi-fw pi-users',
                    route: this.route('drivers.index'),
                    prefix: 'drivers',
                    visible: this.$page.props.admin
                },
                {
                    label: this.$t('common.points'),
                    icon: 'pi pi-fw pi-tag',
                    route: this.route('points.index'),
                    prefix: 'points',
                },
                {
                    label: this.$t('common.zones'),
                    icon: 'pi pi-fw pi-table',
                    route: this.route('zones.index'),
                    prefix: 'zones',
                    visible: this.$page.props.admin
                },
                {
                    label: this.$t('common.users'),
                    icon: 'pi pi-fw pi-user',
                    route: this.route('users.index'),
                    prefix: 'users',
                    visible: this.$page.props.admin
                },
                {
                    label: this.$t('common.creator'),
                    icon: 'pi pi-fw pi-star',
                    route: this.route('creator'),
                    prefix: 'creator',
                    visible: this.$page.props.admin
                },
                {
                    label: this.$t('common.settings'),
                    icon: 'pi pi-fw pi-cog',
                    route: this.route('settings.general'),
                    prefix: 'settings',
                    visible: this.$page.props.admin
                },
                {
                    label: this.$t('auth.signOut'),
                    icon: 'pi pi-fw pi-sign-out',
                    command: () => this.logout(),
                }
            ]
        };
    },
    computed: {
        containerClass() {
            return ['layout-wrapper', {
                'layout-overlay': this.layoutMode === 'overlay',
                'layout-static': this.layoutMode === 'static',
                'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                'layout-mobile-sidebar-active': this.mobileMenuActive
            }];
        },
        sidebarClass() {
            return ['layout-sidebar', {
                'layout-sidebar-dark': this.layoutColorMode === 'dark',
                'layout-sidebar-light': this.layoutColorMode === 'light'
            }];
        },
        logo() {
            return (this.layoutColorMode === 'dark') ? 'https://chmurowisko.pl/wp-content/themes/chmurowisko-wp/img/logo.png' : 'https://chmurowisko.pl/wp-content/themes/chmurowisko-wp/img/logo.png';
        }
    },
    watch: {
        $route() {
            this.menuActive = false;
            // this.$toast.removeAllGroups();
        },
        '$page.props.flash': {
            handler(flashData) {
                if (flashData?.error) {
                    this.flashError(flashData.error);
                    this.$page.props.flash.error = null;
                }
                if (this.$page.props.flash.success) {
                    this.flashSuccess(this.$page.props.flash.success);
                    this.$page.props.flash.success = null;
                }
            },
            deep: true,
        }
        // '$page.props.flash': {
        //     handler() {
        //         if (this.$page.props.flash.success) {
        //             this.$toast.add({
        //                 severity: 'success',
        //                 summary: 'Sukces',
        //                 detail: this.$page.props.flash.success,
        //                 life: 3000
        //             });
        //             this.$page.props.flash.success = null;
        //         }
        //     },
        //     deep: true,
        // }
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, 'body-overflow-hidden');
        else
            this.removeClass(document.body, 'body-overflow-hidden');
    },
    methods: {
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
            this.menuClick = false;
        },
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1);
            if (urls[0] === '') {
                return currentUrl === '';
            }
            return urls.filter(url => currentUrl.startsWith(url)).length;
        },
        onMenuToggle() {
            this.menuClick = true;
            if (this.isDesktop()) {
                if (this.layoutMode === 'overlay') {
                    if (this.mobileMenuActive === true) {
                        this.overlayMenuActive = true;
                    }
                    this.overlayMenuActive = !this.overlayMenuActive;
                    this.mobileMenuActive = false;
                } else if (this.layoutMode === 'static') {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            } else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }
            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
        onLayoutChange(layoutMode) {
            this.layoutMode = layoutMode;
        },
        onLayoutColorChange(layoutColorMode) {
            this.layoutColorMode = layoutColorMode;
        },
        addClass(element, className) {
            if (element.classList)
                element.classList.add(className);
            else
                element.className += ' ' + className;
        },
        removeClass(element, className) {
            if (element.classList)
                element.classList.remove(className);
            else
                element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        },
        isDesktop() {
            return window.innerWidth > 1024;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === 'static')
                    return !this.staticMenuInactive;
                else if (this.layoutMode === 'overlay')
                    return this.overlayMenuActive;
                else
                    return true;
            } else {
                return true;
            }
        },
        can(permission) {
            return true;
        },
        logout() {
            this.$inertia.post(this.route('logout'));
        },
    },
};
</script>

<style scoped>
</style>
