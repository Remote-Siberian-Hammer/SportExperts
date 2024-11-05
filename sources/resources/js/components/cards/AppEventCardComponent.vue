<script>
    import Button from 'primevue/button';
    import Card from "primevue/card";
    import { recordUserToEventRequest } from "../../api/ParticipantRequest";
    import { createLogOptionRequest } from "../../api/CreateLogOptionRequest";
    import AppModalInvitesComponent from "../modals/AppModalInvitesComponent.vue";
    import AppEventOptionsCardComponent from "./AppEventOptionsCardComponent.vue";
    import AppEventParticipantsCardComponent from "./AppEventParticipantsCardComponent.vue";
    import InlineMessage from "primevue/inlinemessage";
    import {IDENTIFIER} from "../../common/fields";
    import {MESSAGES} from "../../common/messages";
    import {WEB_URL} from "../../common/route/web";

    export default {
        data() {
          return {
              currentDate: new Date(),
              baseUrl: WEB_URL,
              dialog: false,
              eventId: null,
              event: null,
              user: null
          };
        },
        props: {
            eventProps: Object,
            userProps: Object,
            eventIdProps: String,
        },
        components: {
            AppEventParticipantsCardComponent,
            AppEventOptionsCardComponent,
            AppModalInvitesComponent,
            InlineMessage,
            Card,
            Button
        },
        methods: {
            formatDate: (inputDate) => {
                const [year, month, day] = inputDate.split('-');
                return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`;
            },
            invited: async function ()
            {
                let attributes = {
                    event_id: this.eventIdProps,
                    user_id: this.user.id,
                    invited_user_id: window.$cookies.get(IDENTIFIER),
                    // team_key: null,
                };

                recordUserToEventRequest(attributes)
                    .then(async(response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        await this.$emit('messageSuccessEmit', data.attributes ? MESSAGES.FORM_SUCCESS : MESSAGES.ERROR_ERROR);
                    })
                    .catch(async(error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'recordUserToEventRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        await this.$emit('messageErrorEmit', MESSAGES.ERROR_ERROR);
                    });
            },
        },
        watch: {
            eventIdProps: {
                handler(value) {
                    this.eventId = value;
                },
                immediate: true,
                deep: true
            },
            eventProps: {
                handler(value) {
                    this.event = value;
                },
                immediate: true,
                deep: true
            },
            userProps: {
                handler(value) {
                    this.user = value;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <div class="w-50">
        <Card role="region">
            <template #header>
                <h2 class="text-center" style="
                    @media(max-width: 480px) {
                        width: 90%;
                        margin: 0 auto;
                        margin-top: 1.2em;
                    }
                ">{{ this.event.name }}</h2>
            </template>
            <template #content>
                <div class="mb-1">
                    <h3>
                        <i class="pi pi-map-marker" style="color: #222"></i> <span>{{ this.event.location }}</span>
                    </h3>
                </div>
                <div class="mb-1">
                    <h3>
                        <i class="pi pi-calendar-clock" style="color: #222"></i> <span>{{ this.formatDate(this.event.start_date) }}</span>
                    </h3>
                </div>
                <div v-if="this.event.participants.length > 0" class="mb-1">
                    <a :href="this.baseUrl + 'tournament?event=' + this.event.key">
                        <Card class="w-50" style="color: #40a053; font-size: 1.1em;">
                            <template #content>
                                <i class="pi pi-sitemap"></i> Турнирная сетка
                            </template>
                        </Card>
                    </a>
                </div>
                <div class="mb-1">
                    <h3>
                        <i class="pi pi-users" style="color: #222"></i> <span>{{ this.event.participants.length }} участников</span>
                    </h3>
                </div>
                <div v-if="this.eventProps.status === 'Active'" class="mb-1">
                    <br>
                    <AppModalInvitesComponent  v-if="this.user && this.user.role === 'admin'"
                                               :userProps="this.user"
                                               :eventIdProps="this.eventIdProps" />
                    <Button v-if="this.user && this.user.role === 'athlete'"
                            label="Записаться"
                            severity="primary"
                            class="w-100"
                            @click="this.invited" />
                </div>
                <div v-else class="mb-1">
                    <InlineMessage class="w-100" icon="pi pi-ban" severity="secondary">Событие завершено</InlineMessage>
                </div>
            </template>
        </Card>
        <AppEventOptionsCardComponent :optionsProps="this.event.options" />
        <AppEventParticipantsCardComponent
            :participantsProps="this.event.participants"
            :eventKeyProps="this.event.key" />
    </div>
</template>
