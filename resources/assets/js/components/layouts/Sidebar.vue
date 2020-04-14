<template>
    <div class="sidebar">
        <div class="sidebar-inner">
            <div class="sidebar-links">
                <ul class="sidebar-list" v-for="list in activeList">
                    <li class="sidebar-break"></li>
                    <li v-for="link in list.links">
                        <router-link :to="link.path" class="sidebar-link">
                            <div class="sidebar-link_inner">
                                <span class="sidebar-text">{{ link.title }}</span>
                            </div>
                            <i class="fa fa-caret-right"></i>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        computed: {
            user() {
                return window.apex.user
            },
            activeList() {
                return this.lists.filter((item) => {
                    if(item.admin) {
                        return this.user.is_admin == true
                    } else {
                        return true
                    }
                })
            },
        },
        data() {
            return {
                lists: [
                    {
                        admin: false,
                        links: [
                            {path: '/', title: 'Dashboard', icon: 'dashboard'},
                            
                        ]
                    },
                    {
                        admin: false,
                        links: [
                            {path: '/covid_complaint', title: 'Covid-19 Complaints', icon: 'cubes'},
                             {path: '/covid_config', title: 'Covid-19 Shop Details', icon: 'file-o'},
                            {path: '/assessment', title: 'Assessment', icon: 'users'},
                        ]
                    },                   
                    {
                        admin: true,
                        links: [                            
                            {path: '/users', title: 'Users', icon: 'users'},
                        ]
                    }
                ]
            }
        }
    }
</script>
