<script>
    import AppFormWrapperComponent from "../wrappers/AppFormWrapperComponent.vue";
    import InputText from "primevue/inputtext";
    import Button from "primevue/button";

    export default {
        data() {
            return {
                currentDate: new Date(),
                email: null,
            }
        },
        props: {
            userEmailProps: Object
        },
        components: {
            AppFormWrapperComponent,
            InputText,
            Button,
        },
        methods: {
            userPasswordNew: async function ()
            {
                // TODO: реализовать
            },
        },
        watch: {
            userEmailProps: {
                handler(newVal) {
                    this.email = newVal;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <AppFormWrapperComponent>
        <section class="d-flex d-center">
            <div class="form-block w-100">
                <label for="name">Укажите адрес электронной почты</label>
                <InputText type="email"
                           v-model="this.email"
                           class="w-100"
                           :value="this.email"
                           required />
                <small>На этот адрес вам будет выслана ссылка для смены пароля.</small>
            </div>
        </section>
        <Button type="button"
                label="Отправить пароль"
                class="w-100 mt-3"
                severity="primary"
                @click="this.userPasswordNew"/>
    </AppFormWrapperComponent>
</template>
