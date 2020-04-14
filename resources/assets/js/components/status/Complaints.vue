<script type="text/javascript">
    export default {
        props: {
            id: {
                type: Number,
                required: true
            }
        },
        render: function (createElement) {
            const status = this.statusLookup(this.id)
            return createElement(
                'span',
                {
                    'class': ['status', `status-${status}`]
                },
                this.clean(status)
            )
        },
        methods: {
            statusLookup(id) {
                const list = {
                    1: 'Initiated',
                    2: 'Inprocess',                  
                    3: 'Invalid',
                    4: 'Rectified',
                    5: 'Reopened'
                }
                return list[id]
            },
            clean(str) {
                return str.replace(/(\-[a-z])/g, function($1){
                    return $1.replace('-',' ')
                })
            }
        }
    }
</script>
