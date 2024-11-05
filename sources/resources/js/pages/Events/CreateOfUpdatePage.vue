<script>
    import { getEventRequest } from '../../api/EventRequest';
    import '@vueup/vue-quill/dist/vue-quill.snow.css';
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import AppAlertComponent from "../../components/AppAlertComponent.vue";
    import AppEventFormComponent from "../../components/forms/AppEventFormComponent.vue";
    import AppUserCreateOrUpdateOptionFormComponent from "../../components/forms/AppUserCreateOrUpdateOptionFormComponent.vue";
    import {WEB_URL} from "../../common/route/web";
    import {ENDPOINTS} from "../../common/route/api";
    import {IDENTIFIER} from "../../common/fields";
    import {MESSAGES} from "../../common/messages";

    export default {
        data() {
            return {
                banner: null,
                baseUrl: WEB_URL,
                route: ENDPOINTS,
                messageError: null,
                messageSuccess: null,
                eventId: null,
                event: null,
                options: []
            };
        },
        components: {
            AppUserCreateOrUpdateOptionFormComponent,
            AppEventFormComponent,
            AppAlertComponent,
        },
        methods: {
            addMessageSuccess: function (data) { this.messageSuccess = data; },
            addMessageError: function (data) { this.messageError = data; },
            getUserId: function ()
            {
                this.token = window.$cookies.get(IDENTIFIER);
            },
            getUrl: function ()
            {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams)
                {
                    this.eventId = urlParams.get('id');
                }
            },
            getEvent: async function ()
            {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('id')) {
                    let attributes = {id: this.eventId};
                    await getEventRequest(attributes)
                        .then(async (response) => {
                            console.log(response);
                            const data = response.data.result.original;
                            this.event = await data.attributes;
                            this.options = await data.attributes.options;
                        })
                        .catch(async (error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'getEventRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.messageError = MESSAGES.LOADING_ERROR;
                        });
                }
            },
        },
        async beforeMount() {
            this.getUserId();
            this.getUrl();
            await this.getEvent();
        }
    }
</script>

<template>
    <section class="mt-5 mb-5">
        <AppAlertComponent
            :messageSuccess="this.messageSuccess"
            :messageError="this.messageError" />
        <div class="mt-3">
            <h2 v-if="this.eventId" class="text-center">Создать новое спортивное событие</h2>
            <h2 v-else class="text-center">Создать новое спортивное событие</h2>
        </div>
        <AppEventFormComponent :eventIdProps="this.eventId"
                               :eventProps="this.event"
                               :optionProps="this.options"
                               :baseUrlProps="this.baseUrl"
                               @messageSuccessEmit="addMessageSuccess"
                               @messageErrorEmit="addMessageError" />
    </section>
</template>

<style scoped>
    i.pi-times{
        color: red!important;
    }
    img{
        width: 100%;
    }
</style>
