<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Overview</span>
            </div>
            <div class="panel-overview">
                <div class="stats">
                    <div class="stat">
                        <div class="stat-inner">
                            <h2 class="stat-title">Today Complaints</h2>
                            <p class="stat-value">{{model.today_complaints}}</p>
                        </div>
                    </div>
                    <div class="stat">
                        <div class="stat-inner">
                            <h2 class="stat-title">Unrectified Complaints</h2>
                            <p class="stat-value">{{model.unrectified_complaints}}</p>
                        </div>
                    </div>
                    <div class="stat">
                        <div class="stat-inner">
                            <h2 class="stat-title">Inprocess Complaints</h2>
                            <p class="stat-value">{{model.Inprocess_complaints}}</p>
                        </div>
                    </div>
                    <div class="stat">
                        <div class="stat-inner stat-last">
                            <h2 class="stat-title">Rectified Complaints</h2>
                            <p class="stat-value">{{model.rectified_complaints}}</p>
                        </div>
                    </div>
                </div>              
            </div>
        </div>
        <div class="row">
            <div class="col col-8">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Complaints & Rectification</span>
                    </div>
                    <div class="panel-body">
                        <line-chart :datasets="datasets" :labels="labels"></line-chart>
                    </div>
                </div>
            </div>
                        <div class="col col-4" v-if="model.sms_details.length">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">SMS Details</span>
                    </div>
                    <div class="panel-body">
                        <table class="table table-link">
                            <thead>
                                <tr>
                                    <th>Complaint No</th>
                                    <th>Mobile No</th>
                                    <th>SMS At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="invoice in model.sms_details">
                                    <td>{{invoice.complaint_no}}</td>
                                    <td>{{invoice.mobile_no}}</td>
                                    <td>{{invoice.sms_date}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get } from '../../lib/api'
    import LineChart from '../../components/charts/Line.vue'
    export default {
        components: {LineChart},
        data() {
            return {
                show: false,
                datasets: [],
                labels: [],
                model: {
                    sms_details: []
                    
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/dashboard`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/dashboard`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Dashboard')
                Vue.set(this.$data, 'model', res.data.data)
                 Vue.set(this.$data, 'labels', res.data.covid_complaint_chart.labels)
                 Vue.set(this.$data, 'datasets', res.data.covid_complaint_chart.datasets)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
