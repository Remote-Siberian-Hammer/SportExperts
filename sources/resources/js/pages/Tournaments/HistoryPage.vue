<script>
import {getUser} from "../../api/UserRequest";
import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
import {getTournamentHistoryRequest} from "../../api/TournamentHistoryRequest";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import {IDENTIFIER} from "../../common/fields";
import {MESSAGES} from "../../common/messages";

export default {
        data() {
            return {
                eventKey: null,
                user: null,
                history: [],
                currentDate: new Date(),
            };
        },
        components: {
            DataTable,
            Column,
            Tag
        },
        methods: {
            formatDate: (inputDate) => {
                const [year, month, day] = inputDate.split('-');
                return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`;
            },
            getUrl: function() {
                const urlParams = new URLSearchParams(window.location.search);
                this.eventKey = urlParams.get('event');
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
            getHistory: async function() {
                const attributes = {
                    event_key: this.eventKey
                };
                await getTournamentHistoryRequest(attributes)
                    .then(async (response) => {
                        console.log(response)
                        const data = await response.data.result.original;
                        this.history = await data.attributes;
                    })
                    .catch(async (error) => {
                        console.log(error);
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
        async beforeMount() {
            this.getUrl();
            await this.userIdentifier();
            await this.getHistory();
        }
    };
</script>

<template>
    <section class="mt-5 container">
        <br>
        <section class="mb-5">
            <h2 class="text-center">История изменений</h2>
        </section>
        <div class="w-100">
            <DataTable :value="history">
                <Column header="Администратор">
                    <template #body="slotProps">
                        <p>{{ slotProps.data.admins.first_name }} {{ slotProps.data.admins.last_name }}</p>
                    </template>
                </Column>
                <Column field="category"  header="Действие">
                    <template #body="slotProps">
                        <Tag :value="slotProps.data.status"></Tag>
                    </template>
                </Column>
                <Column field="description" header="Описание"></Column>
                <Column  header="Дата/Время">
                    <template #body="slotProps">
                        <section>{{ this.formatDate(slotProps.data.current_date) }}</section>
                        <section>{{ slotProps.data.current_time.slice(0, -3) }}</section>
                    </template>
                </Column>
            </DataTable>
        </div>
    </section>
</template>
