<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Covid-19 Complaint</span>
            <router-link slot="create" to="/covid_complaint/create" class="btn btn-primary">
                New Complaint
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                  
                    <td class="width-2">{{ props.item.complaint_no }}</td>
                    <td class="width-2">{{ props.item.complaint_datetime}}</td>
                    <td class="width-2">{{ props.item.mobile_no}}</td>                   
                    <td class="width-2">{{ props.item.complaint_name}}</td>
                    <td class="width-2">  <status :id="props.item.status_id"></status></td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'
    import Status from '../../components/status/Complaints.vue'

    export default {
        components: { Panel,Status },
        data() {
            return {
                resource: '/covid_complaint',
                heading: [
                   
                    {title: 'Complaint No', name: 'complaint_no', sort: true},
                    {title: 'Complaint at', name: 'complaint_datetime', sort: true},
                    {title: 'Mobile No', name: 'mobile_no', sort: true},                   
                    {title: 'Complaint', name: 'complaint_name', sort: true},
                    {title: 'Status', name: 'status_name', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/covid_complaint', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/covid_complaint', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`covid_complaint`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
