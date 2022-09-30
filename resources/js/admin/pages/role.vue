<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management <Button @click="addModal=true"><Icon type="md-add" />Add a new role</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
							<tr>
								<th>ID</th>
								<th>Role type</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
							<tr v-for="(role, i) in roles" :key="i" v-if="roles.length">
								<td>{{role.id}}</td>
								<td class="_table_name">{{role.roleName}}</td>
								<td>{{role.created_at}}</td>
								<td>
                                    <Button type="info" size="small" @click="showEditModal(role,i)">Edit</Button>
                                    <Button type="error" size="small" @click="showDeletingModal(role,i)" :loading="role.isDeleting">Delete</Button>
								</td>
							</tr>
								
						</table>
					</div>
				</div>
                    <!-- tag adding model -->
                    <Modal
                        v-model="addModal"
                        title="Add Role"
                        :mask-closable="false"
                        :closable="false">

                        <Input v-model="data.roleName" placeholder="Role name"/>
                        <div slot="footer">
                            <Button type="default" @click="addModal=false">Close</Button> 
                            <Button type="primary" @click="addRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Role' }}</Button>   
                        </div>
                        
                    </Modal>
                     <!-- tag editing model -->
                    <Modal
                        v-model="editModal"
                        title="Edit Role"
                        :mask-closable="false"
                        :closable="false">

                        <Input v-model="editData.roleName" placeholder="Edit Role"/>
                        <div slot="footer">
                            <Button type="default" @click="editModal=false">Close</Button> 
                            <Button type="primary" @click="editRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Role' }}</Button>   
                        </div>
                        
                    </Modal>
                    <!-- delete alert modal -->
                    <Modal v-model="showDeleteModal" width="360">
                        <p slot="header" style="color:#f60;text-align:center">
                            <Icon type="ios-information-circle"></Icon>
                            <span>Delete confirmation</span>
                        </p>
                        <div style="text-align:center">
                            <p>Are you sure you want to delete role?.</p>
                        </div>
                        <div slot="footer">
                            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteRole">Delete</Button>
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
                roleName: ''
            },
            addModal : false,
            editModal : false,
            isAdding : false,
            roles : [],
            editData:{
                roleName : ''
            },
            index  : -1,
            showDeleteModal : false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex:-1
        }
    },
    methods : {
       async addRole(){
            if(this.data.roleName.trim()=='') return this.error('Role name is required')
            const res = await this.callApi('post','app/create_role',this.data)
            if(res.status===201){
                this.roles.unshift(res.data)
                this.success('Role has been added successfully')
                this.addModal=false
                this.data.roleName=''
            }else{
                if(res.status==422){
                    if(res.data.errors.roleName){
                        this.error(res.data.errors.roleName[0]);
                    }
                }else{
                    this.swr()
                }
                
            }
        },
        async editRole(){
            if(this.editData.roleName.trim()=='') return this.error('Role Name is required')
            const res = await this.callApi('post','app/edit_role',this.editData)
            if(res.status===200){
                this.roles[this.index].roleName = this.editData.roleName
                this.success('Role has been edited successfully')
                this.editModal=false
            }else{
                if(res.status==422){
                    if(res.data.errors.roleName){
                        this.error(res.data.errors.roleName[0]);
                    }
                }else{
                    this.swr()
                }
                
            }
        },
        showEditModal(role,index){
            let obj = {
                id : role.id,
                roleName : role.roleName
            }
            this.editData = obj
            this.editModal = true 
            this.index = index
        },
        async deleteRole(){
            this.isDeleting = true
            const res = await this.callApi('post','app/delete_role',this.deleteItem)
            if(res.status === 200){
                this.roles.splice(this.deletingIndex,1)
                this.success('Role has been deleted successfully')
            }else{
                this.swr()
            }
            this.isDeleting = false,
            this.showDeleteModal = false
        },
        showDeletingModal(role,i){
            this.deleteItem = role
            this.deletingIndex=i
            this.showDeleteModal = true
        }

    },
    async created(){
            const res= await this.callApi('get','app/get_roles')
            if(res.status == 200){
                this.roles=res.data
            }else{
                this.swr()
            }
        },
    }
</script>