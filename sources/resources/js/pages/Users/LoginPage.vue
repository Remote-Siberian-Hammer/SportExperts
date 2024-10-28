<script>
    import AppAlertComponent from "../../components/AppAlertComponent.vue";
    import AppLoginFormComponent from "../../components/forms/AppLoginFormComponent.vue";
    import AppPageTitleComponent from "../../components/AppPageTitleComponent.vue";
    import AppWrapperComponent from "../../components/wrappers/AppWrapperComponent.vue";
    import { API_URL, ENDPOINTS } from "../../common/route/api";
    import { WEB_URL } from "../../common/route/web";
    import AppSocialOAuthComponent from "../../components/AppSocialOAuthComponent.vue";

    export default {
        data() {
            return {
                apiUrl: API_URL,
                webUrl: WEB_URL,
                route: ENDPOINTS,
                messageSuccess: null,
                messageError: null,
                pageTitle: 'Вход',
                pageSubTitle: 'Регистрация',
            };
        },
        components: {
            AppAlertComponent,
            AppLoginFormComponent,
            AppPageTitleComponent,
            AppWrapperComponent,
            AppSocialOAuthComponent
        },
        methods: {
            addMessageError: function (data) {
                this.messageError = data;
            },
        },
    }
</script>

<template>
    <AppWrapperComponent>
        <AppAlertComponent
            :messageSuccess="this.messageSuccess"
            :messageError="this.messageError" />
        <AppPageTitleComponent
            :title="this.pageTitle"
            :subTitle="this.pageSubTitle"
            :baseUrl="this.webUrl"
            :endPoint="this.route.REGISTRATION" />
       <AppLoginFormComponent
            :messageError="this.messageError"
            :baseUrl="this.webUrl"
            :endPoint="this.route.RECOVERY"
            @messageErrorEmit="addMessageError"/>
       <AppSocialOAuthComponent />
    </AppWrapperComponent>
</template>
