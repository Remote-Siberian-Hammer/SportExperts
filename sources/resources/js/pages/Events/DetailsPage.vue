<script>
    import { getUser, getInvitedOwnerRequest } from "../../api/UserRequest";
    import { getEventRequest } from '../../api/EventRequest';
    import Image from 'primevue/image';
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import AppAlertComponent from "../../components/AppAlertComponent.vue";
    import AppEventCardComponent from "../../components/cards/AppEventCardComponent.vue";
    import AppModalInvitesComponent from "../../components/modals/AppModalInvitesComponent.vue";
    import {WEB_URL} from "../../common/route/web";
    import {ENDPOINTS} from "../../common/route/api";
    import {IDENTIFIER, TOKEN} from "../../common/fields";
    import {MESSAGES} from "../../common/messages";

    export default {
        data(){
            return {
                baseUrl: WEB_URL,
                currentDate: new Date(),
                route: ENDPOINTS,
                messageError: null,
                messageSuccess: null,
                user: null,
                eventId: null,
                event: null,
            };
        },
        components: {
            AppAlertComponent,
            AppModalInvitesComponent,
            AppEventCardComponent,
            Image,
        },
        methods: {
            tokenRead: async function () {
                this.token = await window.$cookies.get(TOKEN);
            },
            userIdentifier: async function () {
                if (window.$cookies.get(IDENTIFIER))
                {
                    let attributes = {id: window.$cookies.get(IDENTIFIER)};
                    await getUser(attributes)
                        .then(async (response) => {
                            const data = await response.data.result.original;
                            this.user = await data.attributes;
                        })
                        .catch(async (error) => {
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'getUserRequest',
                                status: error.code,
                                request_data: attributes.toString(),
                                message: error.message
                            });
                            this.messageError = MESSAGES.LOADING_ERROR;
                        });
                }
            },
            getUrl: function () {
                const urlParams = new URLSearchParams(window.location.search);
                this.eventId = urlParams.get('id');
                // this.invited.event_id = urlParams.get('id');
            },
            getEvent: async function () {
                let attributes = { id: this.eventId };
                await getEventRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.event = await data.attributes;
                    })
                    .catch(async (error) => {
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.messageError = MESSAGES.LOADING_ERROR + ' 2';
                    });
            },
        },
        async beforeMount() {
            await this.tokenRead();
            await this.userIdentifier();
            this.getUrl();
            await this.getEvent();
        }
    }
</script>

<template>
    <section v-if="this.event != null" class="mt-5 mb-5">
        <AppAlertComponent
            :messageSuccess="this.messageSuccess"
            :messageError="this.messageError" />
        <section class="container d-flex d-between d-flex-wrap">
            <div class="w-50">
                <Image :src="this.baseUrl + 'storage/uploads/' + this.event.image.name"
                       imageStyle="max-width: 95%;"
                       :alt="this.event.name" />
                <div class="mt-3">
                    <h3>ИНФОРМАЦИЯ</h3>
                    <br>
                    <p v-html="this.event.description"
                        style="
                        white-space: break-spaces;
                        line-height: 120%;
                        color: #323232;
                        width: 90%;
                    "></p>
                </div>
            </div>
            <AppEventCardComponent
              :eventProps="this.event"
              :userProps="this.user"
              :eventIdProps="this.eventId"/>
        </section>
    </section>
</template>

<style scoped>
i.pi{
    color: #3470bb!important;
    font-size: 1.2em;
    font-weight: bold;
}
.option-list a{
    color: #3470bb !important;
}

</style>
