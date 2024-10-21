<script>
import {IDENTIFIER, MESSAGES, RESPONSE} from "../../constant";
import {getEventOwnerRequest} from "../../api/FilterRequest";
import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
import {createArchiveRequest, removeArchiveRequest} from "../../api/ArchiveRequest";
import Card from "primevue/card";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import InlineMessage from "primevue/inlinemessage";
import Button from "primevue/button";

    export default {
        data() {
            return {
                currentDate: new Date(),
                events: null,
                eventsNoActive: null,
                eventsArchive: null,
            };
        },
        props: {
            userProps: Object,
            baseUrlProps: String,
            routeProps: String
        },
        components: {
            Card,
            TabView,
            TabPanel,
            InlineMessage,
            Button
        },
        methods: {
            formatDate: (inputDate) => {
                const [year, month, day] = inputDate.split('-');
                return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`;
            },
            getEventOwner: async function ()
            {
                if  (this.userProps && this.userProps.role === 'admin') {
                    let attributes = {
                        filter: `user_id:${window.$cookies.get(IDENTIFIER)}`,
                        mode: 'all',
                        limit: 10,
                        startDate: true,
                        status: 'Active'
                    };
                    await getEventOwnerRequest(attributes)
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
                                method: 'getEventOwnerRequest',
                                status: error.code,
                                request_data: attributes.toString() + ', mode:after, limit:9, startDate:false',
                                message: error.message
                            });
                            // this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                        });
                    let attributes2 = {
                        filter: `user_id:${window.$cookies.get(IDENTIFIER)}`,
                        mode: 'after',
                        limit: 9,
                        startDate: false,
                        status: 'No active'
                    };
                    await getEventOwnerRequest(attributes2)
                        .then(async (response) => {
                            console.log(response);
                            const data = await response.data.result.original;
                            this.eventsNoActive = await data.attributes.data;
                        })
                        .catch(async (error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'getEventOwnerRequest',
                                status: error.code,
                                request_data: attributes2.toString(),
                                message: error.message
                            });
                            // this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                        });

                    let attributes3 = {
                        filter: `user_id:${window.$cookies.get(IDENTIFIER)}`,
                        mode: 'all',
                        limit: 9,
                        startDate: false,
                        status: 'Archive'
                    };
                    await getEventOwnerRequest(attributes3)
                        .then(async (response) => {
                            console.log(response);
                            const data = await response.data.result.original;
                            this.eventsArchive = await data.attributes.data;
                        })
                        .catch(async (error) => {
                            console.log(error);
                            await createLogOptionRequest({
                                current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                                current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                                method: 'getEventOwnerRequest',
                                status: error.code,
                                request_data: attributes3.toString(),
                                message: error.message
                            });
                            // this.$emit('messageErrorEmit', MESSAGES.NO_DATA);
                        });
                }
            },
            removeEventToArchive: async function (eventKey)
            {
                let attributes = { key: eventKey };
                await removeArchiveRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        await this.getEventOwner();
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getEventParticipantRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.$emit('messageErrorEmit', MESSAGES.ERROR_DEFAULT);
                    });
            },
            addEventToArchive: async function (eventKey)
            {
                let attributes = {
                    key: eventKey
                };
                await createArchiveRequest(attributes)
                    .then(async (response) => {
                        console.log(response);
                        await this.getEventOwner();
                    })
                    .catch(async (error) => {
                        console.log(error);
                        await createLogOptionRequest({
                            current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                            current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                            method: 'getEventParticipantRequest',
                            status: error.code,
                            request_data: attributes.toString(),
                            message: error.message
                        });
                        this.$emit('messageErrorEmit', MESSAGES.ERROR_DEFAULT);
                    });
            },
        },
        async beforeMount() {
            await this.getEventOwner();
        }
    }
</script>

<template>
    <section class="mb-3">
        <Card>
            <template #content>
                <section>
                    <h2 class="mb-3">Спортивные события которые вы создали</h2>
                </section>
                <TabView>
                    <TabPanel header="Активные">
                        <section v-if="this.events &&
                                 Object.keys(this.events).includes('data') &&
                                 this.events.data.length > 0">
                            <section v-for="event in this.events.data" class="mt-1 mb-1">
                                <Card class="w-100">
                                    <template #content>
                                        <div class="d-flex d-between d-align-center">
                                            <div class="w-30">
                                                <div :style="
                                                        'background-size: cover;' +
                                                        'background-position: center top;' +
                                                        'background-image: url(' + this.baseUrlProps + 'storage/uploads/' + event.image.name + ');' +
                                                        'height: 10em;' +
                                                        'width: 80%;' +
                                                        'background-repeat: round;' +
                                                        'border-radius: 0.5em'
                                                "></div>
                                            </div>
                                            <div class="w-70">
                                                <div class="d-flex d-between">
                                                    <section class="w-40">
                                                        <p>
                                                            <strong>{{ event.name }}</strong>
                                                        </p>
                                                        <small>{{ this.formatDate(event.start_date) }} {{ event.start_time.slice(0, -3) }}</small><br />
                                                        <small>{{ this.formatDate(event.expiration_date) }} {{ event.expiration_time.slice(0, -3) }}</small>
                                                    </section>
                                                    <section>
                                                        <section>
                                                            <p>
                                                                <strong>Статус:</strong>
                                                            </p>
                                                            <InlineMessage severity="success">Активное событие</InlineMessage>
                                                        </section>
                                                        <section v-if="event.admins && event.admins.length > 0">
                                                            <p>
                                                                <strong>Администраторы:</strong>
                                                                <ul>
                                                                    <li v-for="item in event.admins"
                                                                        :key="item.id"
                                                                        class="item-admin">
                                                                        <i class="pi pi-check-circle" style="font-size: 1rem"></i>
                                                                        <a href="#">{{ item.first_name }} {{ item.last_name }}</a>
                                                                    </li>
                                                                </ul>
                                                            </p>
                                                        </section>
                                                    </section>
                                                    <section>
                                                        <p>Участников: 11</p>
                                                        <div class="mb-1">
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + '/detail?id=' + event.id">
                                                                <Button label="Подробнее" severity="secondary" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                        <div class="mb-1">
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.UPDATE + '?id=' + event.id">
                                                                <Button label="Редактировать" severity="primary" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <Button label="В архив"
                                                                    severity="warning"
                                                                    outlined
                                                                    class="w-100"
                                                                    @click="this.addEventToArchive(event.key)"/>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </Card>
                            </section>
                            <!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
                            <!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
                            <!--    </section>-->
                        </section>
                        <section v-else class="d-flex d-center">
                            <InlineMessage severity="warn" class="w-70">Данных нет</InlineMessage>
                        </section>
                    </TabPanel>
                    <TabPanel header="Прошедшие">
                        <section v-if="this.eventsNoActive &&
                                    this.eventsNoActive.length > 0">
                            <section v-for="event in this.eventsNoActive" class="mt-1 mb-1">
                                <Card class="w-100">
                                    <template #content>
                                        <div class="d-flex d-between d-align-center">
                                            <div class="w-30">
                                                <div :style="
                                                        'background-size: cover;' +
                                                        'background-position: center top;' +
                                                        'background-image: url(' + this.baseUrlProps + 'storage/uploads/' + event.image.name + ');' +
                                                        'height: 10em;' +
                                                        'width: 80%;' +
                                                        'background-repeat: round;' +
                                                        'border-radius: 0.5em'
                                                "></div>
                                            </div>
                                            <div class="w-70">
                                                <div class="d-flex d-between">
                                                    <section class="w-40">
                                                        <p>
                                                            <strong>{{ event.name }}</strong>
                                                        </p>
                                                        <small>{{ event.start_date }} {{ event.start_time }}</small><br />
                                                        <small>{{ event.expiration_date }} {{ event.expiration_time }}</small>
                                                    </section>
                                                    <section>
                                                        <section>
                                                            <p>
                                                                <strong>Статус:</strong>
                                                            </p>
                                                            <InlineMessage severity="secondary">Событие завершено</InlineMessage>
                                                        </section>
                                                        <section v-if="event.admins && event.admins.length > 0">
                                                            <p>
                                                                <strong>Администраторы:</strong>
                                                                <ul>
                                                                    <li v-for="item in event.admins"
                                                                        :key="item.id"
                                                                        class="item-admin">
                                                                        <i class="pi pi-check-circle" style="font-size: 1rem"></i>
                                                                        <a href="#">{{ item.first_name }} {{ item.last_name }}</a>
                                                                    </li>
                                                                </ul>
                                                            </p>
                                                        </section>
                                                    </section>
                                                    <section>
                                                        <p>Участников: 11</p>
                                                        <div class="mb-1">
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + '?id=' + event.id">
                                                                <Button label="Подробнее" severity="secondary" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                        <div class="mb-1">
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.UPDATE + '?id=' + event.id">
                                                                <Button label="Редактировать" severity="primary" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.UPDATE + '?id=' + event.id">
                                                                <Button label="В архив" severity="warning" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </Card>
                            </section>
                            <!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
                            <!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
                            <!--    </section>-->
                        </section>
                        <section v-else class="d-flex d-center">
                            <InlineMessage severity="warn" class="w-70">Данных нет</InlineMessage>
                        </section>
                    </TabPanel>
                    <TabPanel header="В архиве">
                        <section v-if="this.eventsArchive &&
                                this.eventsArchive.length > 0">
                            <section v-for="event in this.eventsArchive" class="mt-1 mb-1">
                                <Card class="w-100">
                                    <template #content>
                                        <div class="d-flex d-between d-align-center">
                                            <div class="w-30">
                                                <div :style="
                                                        'background-size: cover;' +
                                                        'background-position: center top;' +
                                                        'background-image: url(' + this.baseUrlProps + 'storage/uploads/' + event.image.name + ');' +
                                                        'height: 10em;' +
                                                        'width: 80%;' +
                                                        'background-repeat: round;' +
                                                        'border-radius: 0.5em'
                                                "></div>
                                            </div>
                                            <div class="w-70">
                                                <div class="d-flex d-between">
                                                    <section class="w-40">
                                                        <p>
                                                            <strong>{{ event.name }}</strong>
                                                        </p>
                                                        <small>{{ event.start_date }} {{ event.start_time }}</small><br />
                                                        <small>{{ event.expiration_date }} {{ event.expiration_time }}</small>
                                                    </section>
                                                    <section>
                                                        <section>
                                                            <p>
                                                                <strong>Статус:</strong>
                                                            </p>
                                                            <InlineMessage severity="warn">В архиве</InlineMessage>
                                                        </section>
                                                        <section v-if="event.admins && event.admins.length > 0">
                                                            <p>
                                                                <strong>Администраторы:</strong>
                                                                <ul>
                                                                    <li v-for="item in event.admins"
                                                                        :key="item.id"
                                                                        class="item-admin">
                                                                        <i class="pi pi-check-circle" style="font-size: 1rem"></i>
                                                                        <a href="#">{{ item.first_name }} {{ item.last_name }}</a>
                                                                    </li>
                                                                </ul>
                                                            </p>
                                                        </section>
                                                    </section>
                                                    <section>
                                                        <p>Участников: 11</p>
                                                        <div class="mb-1">
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + '?id=' + event.id">
                                                                <Button label="Подробнее" severity="secondary" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                        <div class="mb-1">
                                                            <a :href="this.baseUrlProps + this.routeProps.EVENT + this.routeProps.BASE + this.routeProps.UPDATE + '?id=' + event.id">
                                                                <Button label="Редактировать" severity="primary" outlined class="w-100" />
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <Button label="Возобновить"
                                                                    severity="primary"
                                                                    outlined
                                                                    class="w-100"
                                                                    @click="this.removeEventToArchive(event.key)" />
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </Card>
                            </section>
                            <!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
                            <!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
                            <!--    </section>-->
                        </section>
                        <section v-else class="d-flex d-center">
                            <InlineMessage severity="warn" class="w-70">Данных нет</InlineMessage>
                        </section>
                    </TabPanel>
                </TabView>
            </template>
        </Card>
    </section>
</template>
