<script>
    import AppAlertComponent from "../../components/AppAlertComponent.vue";
    import AppParticipantSearchFormComponent from "../../components/forms/AppParticipantSearchFormComponent.vue";
    import DataTable from "primevue/datatable";
    import Column from "primevue/column";

    export default {
        data() {
            return {
                messageSuccess: null,
                messageError: null,
                eventKey: null,
                participants: null
            };
        },
        components: {
            AppAlertComponent,
            AppParticipantSearchFormComponent,
            DataTable,
            Column
        },
        methods: {
            addMessageSuccess: function (data) { this.messageSuccess = data; },
            addMessageError: function (data) { this.messageError = data; },
            addParticipants: function (data) { this.participants = data; },
            getUrl: function() {
                const urlParams = new URLSearchParams(window.location.search);
                this.eventKey = urlParams.get('event');
            },
        },
        beforeMount() {
            this.getUrl();
        }
    }
</script>

<template>
    <section class="d-center">
        <section class="mt-5">
            <AppAlertComponent
                :messageSuccess="this.messageSuccess"
                :messageError="this.messageError" />
        </section>
        <section>
            <h2 class="text-center">Поиск спортсменов</h2>
        </section>
        <section style="
            margin: 0 auto;
            width: 30%;
            @media(max-width: 480px) {
                margin: 0 auto;
                width: 85%;
            }
        ">
            <AppParticipantSearchFormComponent
                :eventKeyProps="this.eventKey"
                @participantsEmit="addParticipants"/>
        </section>
        <section v-if="this.participants !== null" style="
                margin: 0 auto;
                width: 50%;
                @media(max-width: 480px) {
                    width: 95%;
                }
        ">
            <section class="mt-5 mb-3">
                <h3 class="text-center">Результаты поиска</h3>
            </section>
            <DataTable :value="this.participants" tableStyle="min-width: 50rem">
                <Column header="ФИ">
                    <template #body="slotProps">
                        {{ slotProps.data.first_name }} {{ slotProps.data.last_name }}
                    </template>
                </Column>
                <Column header="Контакты">
                    <template #body="slotProps">
                        <section><i class="pi pi-envelope" style="color: #3690cf; font-weight: 600;"></i> {{ slotProps.data.email }}</section>
                        <br>
                        <section><i class="pi pi-phone" style="color: #3690cf; font-weight: 600;"></i> {{ slotProps.data.phone }}</section>
                    </template>
                </Column>
                <!-- <Column field="category" header="Параметры">
                    <template #body="slotProps">
                        <section>{{ slotProps.data.email }}</section>
                        <section>{{ slotProps.data.phone }}</section>
                    </template>
                </Column> -->
                <!-- <Column field="quantity" header="Пригласил"></Column> -->
            </DataTable>
        </section>
    </section>
</template>
