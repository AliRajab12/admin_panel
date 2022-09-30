<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Users <Button @click="addModal=true"><Icon type="md-add" />Add admin</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
                                <th>User type</th>
                                <th>Created at</th>
								<th>Action</th>
							</tr>
							<tr v-for="(user, i) in users" :key="i" v-if="users.length">
								<td>{{user.id}}</td>
								<td class="_table_name">{{user.fullName}}</td>
                                <td class="">{{user.email}}</td>
                                <td class="">{{user.userType}}</td>
								<td>{{user.created_at}}</td>
								<td>
                                    <Button type="info" size="small" @click="showEditModal(user,i)">Edit</Button>
                                    <Button type="error" size="small" @click="showDeletingModal(user,i)" :loading="user.isDeleting">Delete</Button>
								</td>
							</tr>
								
						</table>
					</div>
				</div>
                    <!-- tag adding model -->
                    <Modal
                        v-model="addModal"
                        title="Add admin"
                        :mask-closable="false"
                        :closable="false">
                        <div class="space">
                            <Input type="text" v-model="data.fullName" placeholder="Full name"/>
                        </div>
                        <div class="space">
                            <Input type="text" v-model="data.email" placeholder="Email"/>
                        </div>
                        <div class="space">
                            <Input type="password" v-model="data.password" placeholder="Password"/>
                        </div>
                        <div class="space">
                            <Select v-model="data.role_id" style="width:200px" placeholder="Select admin type">
                                <Option :value ="r.id" v-for="(r , i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                                <!-- <Option value ="Editor">Editor</Option> -->
                            </Select>
                        </div>
                        <div slot="footer">
                            <Button type="default" @click="addModal=false">Close</Button> 
                            <Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add admin' }}</Button>   
                        </div>
                        
                    </Modal>
                     <!-- User editing model -->
                    <Modal
                        v-model="editModal"
                        title="Edit Admin"
                        :mask-closable="false"
                        :closable="false">

                        <div class="space">
                            <Input type="text" v-model="editData.fullName" placeholder="Full name"/>
                        </div>
                        <div class="space">
                            <Input type="text" v-model="editData.email" placeholder="Email"/>
                        </div>
                        <div class="space">
                            <Input type="password" v-model="editData.password" placeholder="Password"/>
                        </div>
                        <div class="space">
                            <Select v-model="editData.role_id" style="width:200px" placeholder="Select admin type">
                                <Option value ="Admin">Admin</Option>
                                <Option value ="Editor">Editor</Option>
                            </Select>
                        </div>
                        <div slot="footer">
                            <Button type="default" @click="editModal=false">Close</Button> 
                            <Button type="primary" @click="editUser" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Admin' }}</Button>   
                        </div>
                        
                    </Modal>
                    <!-- delete alert modal -->
                    <Modal v-model="showDeleteModal" width="360">
                        <p slot="header" style="color:#f60;text-align:center">
                            <Icon type="ios-information-circle"></Icon>
                            <span>Delete confirmation</span>
                        </p>
                        <div style="text-align:center">
                            <p>Are you sure you want to delete user?.</p>
                        </div>
                        <div slot="footer">
                            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteUser">Delete</Button>
                        </div>
                    </Modal>
			</div>
		</div>
	</div>
</template>

<script>
export default {
    data(){
        return {
            data:{
                fullName: '',
                email: '',
                password: '',
                role_id : null
            },
            addModal : false,
            editModal : false,
            isAdding : false,
            users : [],
            editData:{
                tagName : ''
            },
            index  : -1,
            showDeleteModal : false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex:-1,
            roles : []
        }
    },
    methods : {
       async addAdmin(){
            if(this.data.fullName.trim()=='') return this.error('Full Name is required')
            if(this.data.email.trim()=='') return this.error('Email is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(!this.data.role_id) return this.error('User type is required')
            const res = await this.callApi('post','app/create_user',this.data)
            if(res.status===201){
                this.users.unshift(res.data)
                this.success('Admin user has been added successfully')
                this.addModal=false
                this.data.fullName=''
                this.data.email = ''
                this.data.password = ''
            }else{
                if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
                
            }
        },
        async editUser(){
            if(this.editData.fullName.trim()=='') return this.error('Full Name is required')
            if(this.editData.email.trim()=='') return this.error('Email is required')
            if(!this.editData.role_id) return this.error('User type is required')
            const res = await this.callApi('post','app/edit_user',this.editData)
            if(res.status===200){
                this.users[this.index] = this.editData
                this.success('Admin user has been edited successfully')
                this.editModal=false
            }else{
                if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
                
            }
        },
        showEditModal(user,index){
            let obj = {
                id : user.id,
                fullName : user.fullName,
                email: user.email,
                userType: user.userType
            }
            this.editData = obj
            this.editModal = true 
            this.index = index
        },
        async deleteUser(){
            this.isDeleting = true
            const res = await this.callApi('post','app/delete_user',this.deleteItem)
            if(res.status === 200){
                this.users.splice(this.deletingIndex,1)
                this.success('User has been deleted successfully')
            }else{
                this.swr()
            }
            this.isDeleting = false,
            this.showDeleteModal = false
        },
        showDeletingModal(user,i){
            this.deleteItem = user
            this.deletingIndex=i
            this.showDeleteModal = true
        }

    },
    async created(){
            const[res,resRole] = await Promise.all([
                this.callApi('get','app/get_users'),
                this.callApi('get','app/get_roles')
            ]);
            
            if(res.status == 200){
                this.users=res.data
            }else{
                this.swr()
            }
            if(resRole.status == 200){
                this.roles=resRole.data
            }else{
                this.swr()
            }
        },
    }
</script>