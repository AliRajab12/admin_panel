import { mapGetters } from 'vuex'
export default {
    data() {
        return {

        }
    },
    methods: {
        async callApi(method, url, dataObj) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response
            }
        },
        info(desc, title = "Hey") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        success(desc, title = "Great!") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        warning(desc, title = "Oops!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        error(desc, title = "Oops!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr(desc = "Something went wrong ! Please try again.", title = "Oops!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        checkUserPermission(key) {
            if (!this.userPermission) return true
            let isPermitted = false;
            for (let d of this.userPermission) {
                if (this.$route.name == d.name) {
                    if (d[key]) {
                        isPermitted = true;
                        break;
                    } else {
                        break
                    }
                }
            }
            return isPermitted
        }
    },
    computed: {
        ...mapGetters({
            'userPermission': 'getUserPermission'
        }),
        isReadPrimtted() {
            return this.checkUserPermission('read')
        },
        isWritePrimtted() {
            return this.checkUserPermission('write')
        },
        isUpdatePrimtted() {
            return this.checkUserPermission('update')
        },
        isDeletePrimtted() {
            return this.checkUserPermission('delete')
        },
    }
}