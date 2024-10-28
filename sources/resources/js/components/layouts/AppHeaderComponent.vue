<script>
import {BASE_URL, TOKEN, IDENTIFIER, ENDPOINTS} from '../../constant';
import Menubar from 'primevue/menubar';
import Image from 'primevue/image';
import Button from 'primevue/button';
import Sidebar from 'primevue/sidebar';
import AppMobileMenuComponent from "./AppMobileMenuComponent.vue";

export default {
    name: 'HeaderComponent',
    data() {
        return {
            baseUrl: BASE_URL,
            token: null,
            visibleRight: false,
            route: ENDPOINTS,
            items: [
                {
                    label: 'Профиль',
                    icon: 'pi pi-user',
                    command: () => {
                        window.location = BASE_URL + ENDPOINTS.PROFILE
                    },
                    href: BASE_URL + ENDPOINTS.PROFILE
                },
                {
                    label: 'События',
                    icon: 'pi pi-calendar',
                    command: () => {
                        window.location = BASE_URL + ENDPOINTS.EVENT
                    },
                    href: BASE_URL + ENDPOINTS.EVENT
                },
                {
                    label: 'Настройки',
                    icon: 'pi pi-cog',
                    command: () => {
                        window.location = BASE_URL + ENDPOINTS.PROFILE + ENDPOINTS.BASE + ENDPOINTS.SETTINGS
                    },
                    href: BASE_URL + ENDPOINTS.PROFILE + ENDPOINTS.BASE + ENDPOINTS.SETTINGS
                },
            ]
        };
    },
    components: {
        Menubar,
        Image,
        Button,
        Sidebar,
        AppMobileMenuComponent
    },
    methods: {
        tokenRead: function ()
        {
          this.token = window.$cookies.get(TOKEN);
        },
        logout: function ()
        {
            window.$cookies.remove(TOKEN);
            window.$cookies.remove(IDENTIFIER);
            window.location = BASE_URL;
        }
    },
    beforeMount() {
        this.tokenRead()
    }
}
</script>

<template>
    <header>
        <Menubar :model="items"
                 style="
                backgroundColor: var(--surface-900);
                borderRadius: 0px;
                border: none;
        ">
            <template #start>
                <a id="logo" :href="baseUrl">
                    <Image :src="baseUrl +'images/logo.png'" alt="Image" width="250" />
                </a>
            </template>
            <template #item="{ item }">
                <a v-if="this.token !== null"
                   v-ripple
                   class="flex align-items-center">
                    <span class="ml-2">
                        <span :class="item.icon" /> {{ item.label }}
                    </span>
                </a>
            </template>
            <template #end>
                <section v-if="this.token" class="mobile-view">
                    <Button icon="pi pi-bars" @click="this.visibleRight = true" />
                    <Sidebar v-model:visible="visibleRight"
                             header="Меню"
                             position="right">
                        <AppMobileMenuComponent :items="this.items"/>
                    </Sidebar>
                </section>

                <div class="flex align-items-center gap-2">
                    <Button v-if="this.token"
                            label="Выход"
                            class="mobile-none"
                            severity="danger"
                            @click="logout" />
                    <a v-else :href="baseUrl + this.route.LOGIN">
                        <Button label="Войти в личный кабинет" severity="success" />
                    </a>
                </div>
            </template>
        </Menubar>
    </header>
</template>
