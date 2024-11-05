<script>
    import Button from 'primevue/button';
    import {IDENTIFIER, TOKEN} from "../../common/fields";
    import {WEB_URL} from "../../common/route/web";

    export default {
        props: {
            items: Array
        },
        components: {
            Button
        },
        methods: {
            logout: function ()
            {
                window.$cookies.remove(TOKEN);
                window.$cookies.remove(IDENTIFIER);
                window.location = WEB_URL;
            }
        }
    }
</script>

<template>

        <section v-for="item in this.items" class="mb-1">
            <a :href="item.href">
                <Button :label="item.label" class="w-100"/>
            </a>
        </section>
        <section>
            <hr>
            <Button label="Выход"
                    class="w-100"
                    severity="danger"
                    @click="logout" />
        </section>
</template>
