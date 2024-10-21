<script>
    import Card from "primevue/card";
    import Button from "primevue/button";
    import { getEventListRequest } from "../api/EventRequest";
    import { createLogOptionRequest} from "../api/CreateLogOptionRequest";
    import { IDENTIFIER, MESSAGES } from "../constant";
    import { getRecordToEventsRequest } from "../api/FilterRequest";
    import InlineMessage from "primevue/inlinemessage";

    export default {
        data() {
            return {
                currentDate: new Date(),
                events: null,
                eventsToRead: null,
                user: null,
                noData: MESSAGES.NO_DATA,
            };
        },
        props: {
            userProps: Object,
            baseUrlProps: String,
            routeProps: Object,
            responseProps: Object,
        },
        components: {
            Card,
            Button,
            InlineMessage
        },
        methods: {
            formatDate: (inputDate) => {
                const [year, month, day] = inputDate.split('-');
                return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`;
            },
            eventList: async function ()
            {
                await getEventListRequest()
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.events = await data.attributes.data;
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getUser',
                            status: error.code,
                            request_data: '',
                            message: error.message
                        })
                    });
            },
            userReadToEvent: async function ()
            {
                let attributes = `user_id:${window.$cookies.get(IDENTIFIER)}`;
                await getRecordToEventsRequest(attributes, 'after')
                    .then(async (response) => {
                        console.log(response);
                        const data = response.data.result.original;
                        this.eventsToRead = data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getRecordToEventsRequest',
                            status: error.code,
                            request_data: attributes.toString() + 'mode:after',
                            message: error.message
                        });
                        this.messageError = MESSAGES.LOADING_ERROR;
                    });
            },
        },
        async beforeMount() {
            await this.eventList();
            if (this.user.role === 'athlete') {
                await this.userReadToEvent();
            }
        },
        userProps: {
            handler(value) {
                this.user = value;
            },
            immediate: true,
            deep: true
        }
    }
</script>

<template>
    <section v-if="this.events.length > 0"
             class="container d-flex d-flex-wrap">
        <Card v-for="event in this.events"
              style="overflow: hidden"
              class="mb-3 m-card-list w-22">
            <template #header>
                <div :style="
                            'background-size: cover;' +
                            'background-position: top;' +
                            'background-image: url(' + this.baseUrlProps + 'storage/uploads/' + event.image.name + ');' +
                            'height: 14em;' +
                            'width: 100%;' +
                            'background-repeat: no-repeat;'
                    "></div>
            </template>
            <template #title>{{event.name }}</template>
            <template #footer>
                <span>Даты проведения:</span>
                <div class="d-flex d-between">
                    <strong style="margin: 0.6em;">
                        <div>
                            <i class="pi pi-calendar-plus" style="
                                    font-weight: bold;
                                    color: #4aa81f;
                                "></i> {{ this.formatDate(event.start_date) }}
                        </div>
                        <div><i class="pi pi-stopwatch" style="
                                    font-weight: bold;
                                    color: #1f41a8;
                                "></i> {{ event.start_time.slice(0, -3) }}</div>
                    </strong>
                </div>
                <div v-for="eventParticipant in this.eventsToRead" class="flex gap-3 mt-2">
                    <section v-if="this.user && Object.keys(this.user).includes('role') && this.user.role === 'athlete'">
                        <a v-if="eventParticipant.key === event.key"
                           :href="this.baseUrlProps + this.routeProps.TOURNAMENT + '?event=' + event.key">
                            <Button label="Вы записаны"
                                    icon="pi pi-check"
                                    severity="link"
                                    class="w-100" />
                        </a>
                        <a v-else
                           :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.DETAIL + '?id=' + event.id">
                            <Button label="Записаться"
                                    severity="secondary"
                                    outlined
                                    class="w-100" />
                        </a>
                    </section>
                    <br>
                </div>
                <section>
                    <a v-if="this.user &&
                                Object.keys(this.user).includes('role') &&
                                this.user.role === 'admin'"
                       :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.DETAIL + '?id=' + event.id">
                        <Button label="Подробнее"
                                severity="secondary"
                                outlined
                                class="w-100" />
                    </a>
                </section>
                <section>
                    <a v-if="!this.user"
                       :href="this.baseUrlProps + this.routeProps.EVENT + '/detail?id=' + event.id">
                        <Button label="Подробнее"
                                severity="secondary"
                                outlined
                                class="w-100" />
                    </a>
                </section>
            </template>
        </Card>
    </section>
    <section v-else class="d-flex d-center">
        <InlineMessage severity="info" class="w-50 mt-5">{{ this.noData }}</InlineMessage>
    </section>
</template>

<style scoped>
.p-button{
    background-color: #222;
    color: #fff;
}
.p-button:hover {
    transition: .6s;
    background-color: #f8fafc;
    color: #222;
}
.p-button-label{
    font-weight: bold!important;
}
</style>
