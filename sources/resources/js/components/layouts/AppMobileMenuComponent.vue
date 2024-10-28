<script>
    import Button from 'primevue/button';
    import {BASE_URL, IDENTIFIER, TOKEN} from "../../constant";

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
                window.location = BASE_URL;
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
