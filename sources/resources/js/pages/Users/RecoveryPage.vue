<script>
    import Message from 'primevue/message';
    import AppResetFormComponent from "../../components/forms/AppResetFormComponent.vue";
    import AppAlertComponent from "../../components/AppAlertComponent.vue";
    import AppRegistrationFormComponent from "../../components/forms/AppRegistrationFormComponent.vue";
    import AppWrapperComponent from "../../components/wrappers/AppWrapperComponent.vue";
    import {WEB_URL} from "../../common/route/web";

    export default {
        data() {
            return {
                baseUrl: WEB_URL,
                messageError: null,
                messageSuccess: null,
            };
        },
        components: {
            AppWrapperComponent,
            AppRegistrationFormComponent,
            Message,
            AppAlertComponent,
            AppResetFormComponent
        },
        methods: {
            addMessageSuccess: function (data) { this.messageSuccess = data; },
            addMessageError: function (data) { this.messageError = data; }
        }
    }
</script>

<template>
    <AppWrapperComponent>
        <AppAlertComponent
            :messageSuccess="this.messageSuccess"
            :messageError="this.messageError" />
        <div class="text-center">
            <h2>Восстановление пароля</h2>
            <p>Укажите адрес электронной почты, мы на него отправим временный пароль.</p>
        </div>
        <AppResetFormComponent
            @messageSuccessEmit="addMessageSuccess"
            @messageErrorEmit="addMessageError" />
    </AppWrapperComponent>
</template>
