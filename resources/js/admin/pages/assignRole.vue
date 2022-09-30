    <template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management
                        <Select v-model="data.id" style="width:200px" placeholder="Select admin type" @on-change="changeAdmin">
                                <Option :value ="r.id" v-for="(r , i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                                <!-- <Option value ="Editor">Editor</Option> -->
                        </Select>
                    </p>

					<div class="_overflow _table_div">
						<table class="_table">
							<tr>
								<th>Resource Name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
                                <th>Delete</th>
							</tr>
							<tr v-for="(resource, i) in resources" :key="i">
								<td>{{resource.resourceName}}</td>
								<td><Checkbox v-model="resource.read"></Checkbox></td>
                                <td><Checkbox v-model="resource.write"></Checkbox></td>
                                <td><Checkbox v-model="resource.update"></Checkbox></td>
                                <td><Checkbox v-model="resource.delete"></Checkbox></td>
							</tr>	
                            <!-- ITEMS -->
                            <div class="center_button">
                                <Button type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles">Assign</Button>
                            </div>
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
			</div>
		</div>
	</div>
</template>

<script>
export default {
    data(){
        return {
            data:{
                roleName: '',
                id : null
            },
            isSending : false,
            roles: [],
            resources: [
                {resourceName: 'Home', read : false, write : false, update : false, delete : false,name: '/',},
                {resourceName: 'Tags', read : false, write : false, update : false, delete : false,name: 'tags',icon:'md-bookmarks' },
                {resourceName: 'Category', read : false, write : false, update : false, delete : false,name: 'category',icon:'ios-list-box' },
                {resourceName: 'Create blogs', read : false, write : false, update : false, delete : false,name: 'createBlog',icon:'md-add-circle'},
                {resourceName: 'Blogs', read : false, write : false, update : false, delete : false,name: 'blogs' ,icon:'logo-rss'},
                {resourceName: 'Admin users', read : false, write : false, update : false, delete : false,name: 'adminusers',icon:'ios-people' },
                {resourceName: 'Role', read : false, write : false, update : false, delete : false,name: 'role' ,icon:'ios-hammer'},
                {resourceName: 'Assign Role', read : false, write : false, update : false, delete : false,name: 'assignRole',icon:'ios-list-box' },
                
            ],
            defaultResourcesPermission: [
                {resourceName: 'Home', read : false, write : false, update : false, delete : false,name: '/' },
                {resourceName: 'Tags', read : false, write : false, update : false, delete : false,name: 'tags',icon:'md-bookmarks' },
                {resourceName: 'Category', read : false, write : false, update : false, delete : false,name: 'category' ,icon:'ios-list-box'},
                {resourceName: 'Create blogs', read : false, write : false, update : false, delete : false,name: 'createBlog', icon:'md-add-circle'},
                {resourceName: 'Blogs', read : false, write : false, update : false, delete : false,name: 'blogs' ,icon:'logo-rss'},
                {resourceName: 'Admin users', read : false, write : false, update : false, delete : false,name: 'adminusers' ,icon:'ios-people'},
                {resourceName: 'Role', read : false, write : false, update : false, delete : false,name: 'role',icon:'ios-hammer' },
                {resourceName: 'Assign Role', read : false, write : false, update : false, delete : false,name: 'assignRole',icon:'ios-list-box' },
            ]
        }
    },
    methods : {
        async assignRoles(){
            let data  = JSON.stringify(this.resources)
            const res = await this.callApi('post','app/assign_roles',{'permission': data,id:this.data.id})
            if(res.status==200){
                this.success('Role has been assigned successfully')
                let index = this.roles.findIndex(role=>role.id == this.data.id)
                this.roles[index].permission = data
            }else{
                this.swr()
            }
        },
        changeAdmin(){
            let index = this.roles.findIndex(role=>role.id == this.data.id)
            let permission = this.roles[index].permission
            if(!permission){
                this.resources = this.defaultResourcesPermission
            }else{
                this.resources = JSON.parse(permission)
            }
        }
    },
    async created(){
            const res= await this.callApi('get','app/get_roles')
            if(res.status == 200){
                this.roles=res.data
                if(res.data.length){
                    this.data.id = res.data[0].id
                    if(res.data[0].permission){
                        this.resources =JSON.parse(res.data[0].permission)
                        // this.resources =this.defaultResourcesPermission
                    }
                 }
            }else{
                this.swr()
            }
        },
    }
</script>