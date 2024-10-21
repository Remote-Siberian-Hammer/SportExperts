<script>
import {
    BASE_URL,
    IDENTIFIER,
    MESSAGES,
    ENDPOINTS,
    RESPONSE
} from "../../constant";
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import InputMask from 'primevue/inputmask';
import FloatLabel from 'primevue/floatlabel';
import Message from 'primevue/message';
import InlineMessage from 'primevue/inlinemessage';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Tag from 'primevue/tag';
import {listInvitedRequest} from "../../api/InvitedRequest";
import {createLogOptionRequest} from "../../api/CreateLogOptionRequest";
import AppAlertComponent from "../../components/AppAlertComponent.vue";

export default {
    data() {
        return {
            baseUrl: BASE_URL,
            noData: MESSAGES.NO_DATA,
            response: RESPONSE,
            route: ENDPOINTS,
            currentDate: new Date(),
            user: null,
            messageSuccess: null,
            messageError: null,
            invites: [],
            participants: [],
            expandedRows: {}
        };
    },
    components: {
        AppAlertComponent,
        Card,
        InputText,
        Button,
        InputMask,
        FloatLabel,
        InlineMessage,
        DataTable,
        Column,
        ColumnGroup,
        Row,
        Tag
    },
    methods: {
        formatDate: (inputDate) => {
            const [year, month, day] = inputDate.split('-');
            return `${day.padStart(2, '0')}.${month.padStart(2, '0')}.${year}`;
        },
        onRowExpand(event) {
            // this.$toast.add({ severity: 'info', summary: 'Product Expanded', detail: event.data.name, life: 3000 });
        },
        onRowCollapse(event) {
            // this.$toast.add({ severity: 'success', summary: 'Product Collapsed', detail: event.data.name, life: 3000 });
        },
        expandAll() {
            // this.expandedRows = this.invites.reduce((acc, p) => (acc[p.id] = true) && acc, {});
        },
        collapseAll() {
            this.expandedRows = null;
        },
        getUserInvites: async function()
        {
            let attributes = {
                who_user_id: window.$cookies.get(IDENTIFIER),
                enable_events: true
            };
            await listInvitedRequest(attributes)
                .then(async (response) => {
                    console.log(['invites', response]);
                    const data = await response.data.result.original;
                    this.invites = await data.attributes;
                })
                .catch(async (error) => {
                    console.log(error);
                    await createLogOptionRequest({
                        current_date: `${this.currentDate.getDate().toString().padStart(2, '0')}-${(this.currentDate.getMonth() + 1).toString().padStart(2, '0')}-${this.currentDate.getFullYear()}`,
                        current_time: `${this.currentDate.getHours().toString().padStart(2, '0')}:${this.currentDate.getMinutes().toString().padStart(2, '0')}:${this.currentDate.getSeconds().toString().padStart(2, '0')}`,
                        method: 'listInvitedRequest',
                        status: error.code,
                        request_data: attributes.toString(),
                        message: error.message
                    });
                    this.messageError = MESSAGES.NO_DATA;
                });
        }
    },
    async beforeMount() {
        await this.getUserInvites();
    }
}
</script>

<template>
    <section class="d-flex d-center">
        <div class="text-center mt-3 mb-3">
            <h2>Спортсмены которых вы пригласили.</h2>
        </div>
    </section>
    <AppAlertComponent
        :messageSuccess="this.messageSuccess"
        :messageError="this.messageError" />
    <section class="d-flex d-between container">
        <section class="w-100">
            <Card v-if="this.invites && this.invites.length">
                <template #content>
                    <DataTable v-model:expandedRows="expandedRows"
                               :value="this.invites"
                               dataKey="id"
                               @rowExpand="onRowExpand"
                               @rowCollapse="onRowCollapse"
                               tableStyle="min-width: 60rem">
                        <template #header>
                            <div class="flex flex-wrap justify-content-end gap-2">
                                <Button text icon="pi pi-cloud-download" label="Скачать документом" @click="collapseAll" />
                            </div>
                        </template>
                        <Column expander style="width: 5rem" />
                        <Column field="users.last_name" header="Фамилия" sortable></Column>
                        <Column field="users.first_name" header="Имя" sortable></Column>
                        <Column header="Дата рождения" sortable>
                            <template #body="slotProps">
                                {{ this.formatDate(slotProps.data.users.birth_date) }}
                            </template>
                        </Column>
                        <Column field="users.email" header="Почта"></Column>
                        <Column field="users.phone" header="Номер телефона"></Column>
                        <template #expansion="slotProps">
                            <h5>Мероприятия на которые записан спортсмен ({{ slotProps.data.events.length }} шт)</h5>
                            <DataTable stripedRows :value="slotProps.data.events">
                                <Column header="Название" sortable>
                                    <template #body="slotProps">
                                        <a :href="this.baseUrl + 'event/detail?id=' + slotProps.data.id">
                                            <Button :label="slotProps.data.name" severity="info" link />
                                        </a>
                                    </template>
                                </Column>
                                <Column header="Статус" sortable>
                                    <template #body="slotProps">
                                        <section v-if="slotProps.data.status === 'Active'">
                                            <Tag severity="success" value="Активен"></Tag>
                                        </section>
                                        <section v-else-if="slotProps.data.status === 'No active'">
                                            <Tag severity="info" value="Завершен"></Tag>
                                        </section>
                                        <section v-else>
                                            <Tag severity="warning" value="В архиве"></Tag>
                                        </section>
                                    </template>
                                </Column>
                                <Column header="Дата старта" sortable>
                                    <template #body="slotProps">
                                        {{ this.formatDate(slotProps.data.start_date) }}
                                    </template>
                                </Column>
                                <Column header="Время старта" sortable>
                                    <template #body="slotProps">
                                        {{ slotProps.data.start_time.slice(0, -3) }}
                                    </template>
                                </Column>
                            </DataTable>
                        </template>
                    </DataTable>
                </template>
            </Card>
            <section v-else class="d-flex d-center">
                <InlineMessage severity="info" class="w-50 mt-5">{{ this.noData }}</InlineMessage>
            </section>
        </section>
    </section>
    <!--    <section v-if="this.events && this.events[this.response.data].length > 9" class="mt-5 mb-5">-->
    <!--    <Paginator :rows="9" :totalRecords="120"></Paginator>-->
    <!--    </section>-->
</template>
