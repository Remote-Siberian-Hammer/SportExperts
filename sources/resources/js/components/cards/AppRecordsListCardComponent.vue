<script>
    import {getRecordToEventsRequest} from "../../api/FilterRequest";
    import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
    import Card from "primevue/card";
    import InlineMessage from "primevue/inlinemessage";
    import Button from "primevue/button";
    import {IDENTIFIER} from "../../common/fields";
    import {MESSAGES} from "../../common/messages";

    export default {
        data() {
            return {
                currentDate: new Date(),
                noData: MESSAGES.NO_DATA,
                events: null,
            };
        },
        props: {
            baseUrlProps: String,
            routeProps: String
        },
        components: {
            Card,
            InlineMessage,
            Button
        },
        methods: {
            userReadToEvent: async function ()
            {
                let attributes = `user_id:${window.$cookies.get(IDENTIFIER)}`;
                await getRecordToEventsRequest(attributes, 'after')
                    .then(async (response) => {
                        console.log(response);
                        const data = await response.data.result.original;
                        this.events = await data.attributes;
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
                        this.$emit('messageErrorEmit', MESSAGES.LOADING_ERROR);
                    });
            },
        },
        async beforeMount() {
            await this.userReadToEvent();
        }
    }
</script>

<template>
    <section class="mb-5 d-flex d-between d-flex-wrap">
        <Card v-for="event in this.events"
              :key="event.id"
              style="overflow: hidden"
              class="mb-3 w-30">
            <template #header>
                <div :style="
                        `background-size: cover;
                        background-position: top;
                        background-image: url(/storage/uploads/${event.image.name});
                        height: 14em;
                        width: 100%;
                        background-repeat: no-repeat;`
                ">
                    <InlineMessage v-if="event.status === 'Active'" severity="success">Активно</InlineMessage>
                    <InlineMessage v-if="event.status === 'No Active'" severity="info">Закончился</InlineMessage>
                    <InlineMessage v-if="event.status === 'Archive'" severity="warn">В архиве</InlineMessage>
                </div>
            </template>
            <template #title>{{ event.name }}</template>
            <template #content>
                <!--                        <p class="m-0"> {{ this.short(event.description, 130) }}</p>-->
            </template>
            <template #footer>
                <span>Даты проведения:</span>
                <div class="d-flex d-between">
                    <strong><i class="pi pi-calendar-plus"></i> {{ event.start_date }} <i class="pi pi-stopwatch"></i> {{ event.start_time }}</strong>
                </div>
                <div class="flex gap-3 mt-2">
                    <br>
                    <a :href="this.baseUrlProps + this.routeProps.TOURNAMENT + '?event=' + event.key">
                        <Button label="Подробнее" severity="secondary" outlined class="w-full" />
                    </a>
                </div>
            </template>
        </Card>
        <section v-if="this.events !== null && this.events.length === 0" class="w-100">
            <InlineMessage severity="info" class="w-100 mt-5">{{ this.noData }}</InlineMessage>
        </section>
        <!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
        <!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
        <!--    </section>-->
    </section>
</template>

