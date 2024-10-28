<script>
    import Avatar from 'primevue/avatar';
    import Card from "primevue/card";
    import Image from "primevue/image";
    import Button from "primevue/button";
    import {IDENTIFIER, TOKEN} from "../../constant";

    export default {
        props: {
            userProps: Object,
            routeProps: Array,
            baseUrlProps: String,
        },
        components: {
            Avatar,
            Card,
            Image,
            Button
        },
        methods: {
            logout: function ()
            {
                window.$cookies.remove(TOKEN);
                window.$cookies.remove(IDENTIFIER);
                window.location = this.baseUrl;
            },
        }
    }
</script>

<template>
    <section class="w-100">
        <Card>
            <template #content>
                <div class="d-flex d-center">
                    <section>
                        <Avatar :label="this.userProps.first_name[0] + '' + this.userProps.last_name[0]"
                                class="mr-2"
                                size="xlarge"
                                style="
                                    margin: 0 auto;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    border-radius: .4em;
                                    background-color: #1e293b;
                                    color: #fff;
                                " />
                        <!-- <Image src="images/athlete_default_avatar.png" width="250" /> -->
                        <div class="mt-06 d-flex d-center">
                            <h2 v-if="this.userProps !== null">
                                <strong>{{ this.userProps.first_name }} {{ this.userProps.last_name }}</strong> <br>
                                <small>{{ this.userProps.first_name_eng }} {{ this.userProps.last_name_eng }}</small>
                            </h2>
                        </div>
                        <div v-if="this.userProps !== null && this.userProps.role === 'admin'" class="mt-06">
                            <a :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.CREATE">
                                <Button label="Создать событие" class="w-100" severity="success" />
                            </a>
                        </div>
                        <div class="mt-06">
                            <a :href="this.baseUrlProps + this.routeProps.PROFILE + this.routeProps.BASE + this.routeProps.SETTINGS">
                                <Button type="button" label="Настройки" class="w-100" severity="primary"/>
                            </a>
                        </div>
                        <div class="mt-06">
                            <Button type="button" label="Выход" class="w-100" severity="danger" @click="logout"/>
                        </div>
                    </section>
                </div>
            </template>
        </Card>
    </section>
</template>
