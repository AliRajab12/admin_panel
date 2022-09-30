<template>
    <div>
          
        <div class="container">
			<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                    <div class="login_header">
                        <h1>Login to the dashboard</h1>
                    </div>
                    <div class="space">
                            <Input type="email" v-model="data.email" placeholder="Email"/>
                            <p>ali@gmail.com</p>
                    </div>
                    <div class="space">
                            <Input type="password" v-model="data.password" placeholder="******"/>
                            <p>123456</p>
                    </div>
                    <div class="login_footer">
                        <Button type= "primary" @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? 'logging...': 'Login'}}</Button>
                    </div>
    </div>
        </div>
    </div>
</template>

<style scoped>
._1adminOverveiw_table_recent {
    margin: 0 auto;
    margin-top :220px;
}
.login_header{
    text-align:center;
    margin-bottom : 25px;
}
.login_footer{
    text-align:center;
}
</style>

<script>
export default{
    data(){
        return {
            data:{
                email: '',
                password: ''
            },
            isLoggin : false,
        }
    },
    methods:{
        async login(){
            if(this.data.email.trim()=='') return this.error('Email is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(this.data.password.length < 6 ) return this.error('Password must be minimum 6 character')
            this.isLoggin = true
            const res = await this.callApi('post','app/admin_login',this.data)
            if(res.status ===200){
                this.success(res.data.msg)
                window.location = '/'
            }else{
                if(res.status===401){
                    this.info(res.data.msg)
                }else if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }
                else{
                    this.swr()
                }
                    
                }
            this.isLoggin = false
        }
    }
}
</script>