<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Assessment Details</span>
           
            <tr slot-scope="props">
                    <td class="width-3">{{ props.item.assessment_no }}</td>      
                    <td class="width-3">{{ props.item.owner_name }}</td>                    
                    <td class="width-1">{{ props.item.ward_no }}</td>
                    <td class="width-3">{{ props.item.street_no }}</td>
                    <td class="width-1">{{ props.item.door_no }}</td>
                    <td class="width-2">{{ props.item.mobile_no }}  {{ props.item.mobile_no_2 }}  {{ props.item.mobile_no_3 }}</td>
                   
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel },
        data() {
            return {
                resource: '/assessment',
                heading: [
                    {title: 'Assessment No', name: 'assessment_no', sort: true},
                    {title: 'Owner Name', name: 'owner_name', sort: true},
                    {title: 'Ward No', name: 'ward_no', sort: true},
                    {title: 'Street No', name: 'street_no', sort: false},                                  
                    {title: 'Door No', name: 'door_no', sort: true},                   
                    {title: 'Mobile No', name: 'mobile_no', sort: true},                   
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/assessment', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/assessment', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Assessment`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
